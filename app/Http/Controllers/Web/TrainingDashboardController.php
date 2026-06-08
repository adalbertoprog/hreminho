<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\QuizAttempt;
use App\Models\MandatoryTraining;
use App\Models\Training;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TrainingDashboardController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-hr');

        $today = Carbon::today();

        // ── KPIs principais ────────────────────────────────────────────
        $totalTrainings    = Training::count();
        $totalEnrollments  = EmployeeTraining::whereHas('employee')->count();
        $totalCompleted    = EmployeeTraining::whereHas('employee')->where('status', 'completed')->count();
        $totalFailed       = EmployeeTraining::whereHas('employee')->where('status', 'failed')->count();
        $globalRate        = $totalEnrollments > 0 ? round(($totalCompleted / $totalEnrollments) * 100) : 0;

        $expiringCount = EmployeeTraining::whereHas('employee')
            ->whereNotNull('validity_months')
            ->whereNotNull('end_date')
            ->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) >= ?", [$today])
            ->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) <= DATE_ADD(?, INTERVAL 30 DAY)", [$today])
            ->count();

        $expiredCount = EmployeeTraining::whereHas('employee')
            ->whereNotNull('validity_months')
            ->whereNotNull('end_date')
            ->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) < ?", [$today])
            ->count();

        $kpis = compact(
            'totalTrainings', 'totalEnrollments', 'totalCompleted',
            'totalFailed', 'globalRate', 'expiringCount', 'expiredCount'
        );

        // ── Taxa de conclusão por departamento ─────────────────────────
        $depts = Department::with(['employees' => function ($q) {
            $q->where('status', 'active')->with('employeeTrainings');
        }])->get();

        $deptCompletion = $depts->map(function ($d) {
            $total     = $d->employees->sum(fn($e) => $e->employeeTrainings->count());
            $completed = $d->employees->sum(fn($e) => $e->employeeTrainings->where('status', 'completed')->count());
            $rate      = $total > 0 ? round(($completed / $total) * 100) : 0;
            return ['name' => $d->department, 'total' => $total, 'completed' => $completed, 'rate' => $rate];
        })->filter(fn($d) => $d['total'] > 0)->sortByDesc('rate')->values();

        // ── Formações com mais reprovações (quiz) ──────────────────────
        $highFailTrainings = Training::whereHas('quiz')
            ->with(['quiz' => function ($q) {
                $q->withCount([
                    'attempts',
                    'attempts as failed_count' => fn($q) => $q->where('passed', false),
                    'attempts as passed_count'  => fn($q) => $q->where('passed', true),
                ]);
            }])
            ->get()
            ->map(function ($t) {
                $total  = $t->quiz?->attempts_count  ?? 0;
                $failed = $t->quiz?->failed_count     ?? 0;
                $passed = $t->quiz?->passed_count     ?? 0;
                $rate   = $total > 0 ? round(($failed / $total) * 100) : 0;
                return ['id' => $t->id, 'title' => $t->title, 'total' => $total, 'failed' => $failed, 'passed' => $passed, 'fail_rate' => $rate];
            })
            ->filter(fn($t) => $t['total'] > 0)
            ->sortByDesc('fail_rate')
            ->take(6)
            ->values();

        // ── Formações com certificados a expirar ───────────────────────
        $expiringEnrollments = EmployeeTraining::with(['employee', 'training'])
            ->whereHas('employee')
            ->whereNotNull('validity_months')
            ->whereNotNull('end_date')
            ->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) >= ?", [$today])
            ->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) <= DATE_ADD(?, INTERVAL 60 DAY)", [$today])
            ->orderByRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) ASC")
            ->take(8)
            ->get()
            ->map(function ($e) {
                $expiry = $e->end_date->copy()->addMonths($e->validity_months);
                $days   = Carbon::today()->diffInDays($expiry, false);
                return [
                    'employee'  => $e->employee->full_name,
                    'code'      => $e->employee->code,
                    'training'  => $e->training->title,
                    'expiry'    => $expiry->format('d/m/Y'),
                    'days_left' => $days,
                ];
            });

        // ── Evolução mensal (12 meses) ─────────────────────────────────
        $months = collect(range(11, 0))->map(fn($i) => Carbon::now()->subMonths($i));
        $monthlyEvolution = $months->map(function ($m) {
            $enrolled  = EmployeeTraining::whereHas('employee')->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count();
            $completed = EmployeeTraining::whereHas('employee')->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->where('status', 'completed')->count();
            $failed    = EmployeeTraining::whereHas('employee')->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->where('status', 'failed')->count();
            return ['month' => $m->format('M/y'), 'enrolled' => $enrolled, 'completed' => $completed, 'failed' => $failed];
        });

        $chartEvolution = [
            'labels'    => $monthlyEvolution->pluck('month')->toArray(),
            'enrolled'  => $monthlyEvolution->pluck('enrolled')->toArray(),
            'completed' => $monthlyEvolution->pluck('completed')->toArray(),
            'failed'    => $monthlyEvolution->pluck('failed')->toArray(),
        ];

        // ── Taxa de conclusão por departamento (chart) ─────────────────
        $chartDept = [
            'labels' => $deptCompletion->pluck('name')->toArray(),
            'rates'  => $deptCompletion->pluck('rate')->toArray(),
            'totals' => $deptCompletion->pluck('total')->toArray(),
        ];

        // ── Top formações por taxa de aprovação no quiz ────────────────
        $topByApproval = Training::whereHas('quiz.attempts')
            ->with(['quiz' => function ($q) {
                $q->withCount([
                    'attempts',
                    'attempts as passed_count' => fn($q) => $q->where('passed', true),
                ]);
            }])
            ->get()
            ->map(function ($t) {
                $total  = $t->quiz?->attempts_count ?? 0;
                $passed = $t->quiz?->passed_count    ?? 0;
                $rate   = $total > 0 ? round(($passed / $total) * 100) : 0;
                $avg    = QuizAttempt::where('quiz_id', $t->quiz->id)->avg('score');
                return ['title' => $t->title, 'total' => $total, 'passed' => $passed, 'rate' => $rate, 'avg_score' => round($avg ?? 0, 1)];
            })
            ->filter(fn($t) => $t['total'] > 0)
            ->sortByDesc('rate')
            ->take(8)
            ->values();

        // ── Cumprimento de formações obrigatórias ──────────────────────
        $mandatoryCompliance = MandatoryTraining::with(['training', 'training.quiz'])->get()->map(function ($rule) {
            $affectedIds = $rule->affectedEmployeeIds();
            $doneIds     = $rule->doneEmployeeIds($affectedIds);
            $total   = $affectedIds->count();
            $done    = $doneIds->count();
            $missing = $total - $done;
            $rate    = $total > 0 ? round(($done / $total) * 100) : 0;
            return [
                'id'          => $rule->id,
                'training'    => $rule->training->title,
                'target_name' => $rule->target_name,
                'target_type' => $rule->target_type,
                'total'       => $total,
                'done'        => $done,
                'missing'     => $missing,
                'rate'        => $rate,
            ];
        })->sortBy('rate')->values();

        $complianceKpis = [
            'total_rules'    => $mandatoryCompliance->count(),
            'fully_done'     => $mandatoryCompliance->where('rate', 100)->count(),
            'critical'       => $mandatoryCompliance->where('rate', '<', 50)->count(),
            'total_missing'  => $mandatoryCompliance->sum('missing'),
        ];

        return view('trainings.dashboard', compact(
            'kpis',
            'deptCompletion',
            'highFailTrainings',
            'expiringEnrollments',
            'chartEvolution',
            'chartDept',
            'topByApproval',
            'mandatoryCompliance',
            'complianceKpis',
        ));
    }
}
