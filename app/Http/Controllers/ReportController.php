<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\MandatoryTraining;
use App\Models\QuizAttempt;
use App\Models\Training;
use App\Models\TrainingSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    /** Expressão SQL para adicionar meses ao campo end_date — compatível com MySQL e SQLite. */
    private function expiryExpr(): string
    {
        return DB::connection()->getDriverName() === 'sqlite'
            ? "date(end_date, '+' || validity_months || ' months')"
            : "DATE_ADD(end_date, INTERVAL validity_months MONTH)";
    }

    /** Expressão SQL para adicionar dias a uma data literal — compatível com MySQL e SQLite. */
    private function addDaysExpr(string $date, int $days): string
    {
        return DB::connection()->getDriverName() === 'sqlite'
            ? "date('{$date}', '+{$days} days')"
            : "DATE_ADD('{$date}', INTERVAL {$days} DAY)";
    }
    // ── 1. Formações concluídas ──────────────────────────────────────
    public function completedTrainings(Request $request): JsonResponse
    {
        $query = EmployeeTraining::with(['employee.sector', 'training'])
            ->whereHas('employee')
            ->where('status', 'completed');

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }
        if ($request->filled('sector_id')) {
            $query->whereHas('employee', fn($q) => $q->where('sector_id', $request->sector_id));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('updated_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('updated_at', '<=', $request->date_to);
        }

        $rows = $query->orderBy('updated_at', 'desc')->get();

        return response()->json([
            'data' => $rows->map(fn($r) => [
                'id'           => $r->id,
                'employee'     => $r->employee ? $r->employee->full_name : '—',
                'sector'       => $r->employee?->sector?->sector ?? '—',
                'training'     => $r->training?->title ?? '—',
                'provider'     => $r->training?->provider ?? '—',
                'score'        => $r->score,
                'completed_at' => $r->updated_at?->toDateString(),
            ]),
            'total' => $rows->count(),
        ]);
    }

    // ── 2. Funcionários com formações concluídas ─────────────────────
    public function employeesWithTrainings(Request $request): JsonResponse
    {
        $query = Employee::with(['position', 'sector',
                'employeeTrainings' => fn($q) => $q->where('status', 'completed')->with('training')])
            ->whereHas('employeeTrainings', fn($q) => $q->where('status', 'completed'));

        if ($request->filled('sector_id')) {
            $query->where('sector_id', $request->sector_id);
        }
        $positionIds = array_filter((array) $request->input('position_id', []));
        if (!empty($positionIds)) {
            $query->whereIn('position_id', $positionIds);
        }

        $rows = $query->orderBy('first_name')->get();

        return response()->json([
            'data' => $rows->map(fn($e) => [
                'id'              => $e->id,
                'code'            => $e->code,
                'name'            => $e->full_name,
                'position'        => $e->position?->position ?? '—',
                'sector'          => $e->sector?->sector ?? '—',
                'total_completed' => $e->employeeTrainings->count(),
                'trainings'       => $e->employeeTrainings->map(fn($et) => [
                    'title'        => $et->training?->title ?? '—',
                    'score'        => $et->score,
                    'completed_at' => $et->updated_at?->toDateString(),
                ]),
            ]),
            'total' => $rows->count(),
        ]);
    }

    // ── 3. Relatório de assiduidade ─────────────────────────────────────
    public function attendance(Request $request): JsonResponse
    {
        $query = Attendance::with(['employee.sector', 'employee.position'])
            ->whereHas('employee');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('sector_id')) {
            $query->whereHas('employee', fn($q) => $q->where('sector_id', $request->sector_id));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->orderBy('date', 'desc')->get();

        // Summary by employee
        $summary = $rows->groupBy('employee_id')->map(function($group) {
            $emp = $group->first()->employee;
            return [
                'employee'  => $emp?->full_name ?? '—',
                'sector'    => $emp?->sector?->sector ?? '—',
                'position'  => $emp?->position?->position ?? '—',
                'present'   => $group->whereIn('status', ['present', 'late'])->count(),
                'absent'    => $group->where('status', 'absent')->count(),
                'late'      => $group->where('status', 'late')->count(),
                'total'     => $group->count(),
                'rate'      => $group->count() > 0
                    ? round($group->whereIn('status', ['present','late'])->count() / $group->count() * 100, 1)
                    : 0,
            ];
        })->values();

        return response()->json([
            'data'    => $rows->map(fn($r) => [
                'id'           => $r->id,
                'employee'     => $r->employee?->full_name ?? '—',
                'code'         => $r->employee?->code ?? '—',
                'sector'       => $r->employee?->sector?->sector ?? '—',
                'position'     => $r->employee?->position?->position ?? '—',
                'date'         => $r->date?->format('d/m/Y'),
                'check_in'     => $r->check_in  ? substr($r->check_in,  0, 5) : '—',
                'check_out'    => $r->check_out ? substr($r->check_out, 0, 5) : '—',
                'worked_hours' => $r->worked_hours_formatted ?? '—',
                'status'       => $r->status,
            ]),
            'summary' => $summary,
            'total'   => $rows->count(),
        ]);
    }

    // ── 4. Formações e funcionários que as possuem ──────────────────────
    public function trainingWithEmployees(Request $request): JsonResponse
    {
        $query = Training::with([
            'employeeTrainings' => fn($q) => $q->where('status', 'completed')
                ->whereHas('employee')
                ->with(['employee.sector', 'employee.position']),
        ])->whereHas('employeeTrainings', fn($q) => $q->where('status', 'completed')->whereHas('employee'));

        $trainingIds = array_filter((array) $request->input('training_id', []));
        if (!empty($trainingIds)) {
            $query->whereIn('id', $trainingIds);
        }

        $positionIds = array_filter((array) $request->input('position_id', []));

        if ($request->filled('sector_id') || !empty($positionIds)) {
            $query->whereHas('employeeTrainings', function ($q) use ($request, $positionIds) {
                $q->where('status', 'completed')
                  ->whereHas('employee', function ($eq) use ($request, $positionIds) {
                      if ($request->filled('sector_id')) {
                          $eq->where('sector_id', $request->sector_id);
                      }
                      if (!empty($positionIds)) {
                          $eq->whereIn('position_id', $positionIds);
                      }
                  });
            });
        }

        $rows = $query->orderBy('title')->get();

        // Filtrar os employeeTrainings por setor/função dentro de cada formação
        $rows->each(function ($t) use ($request, $positionIds) {
            $t->employeeTrainings = $t->employeeTrainings->filter(function ($et) use ($request, $positionIds) {
                if ($request->filled('sector_id') && $et->employee?->sector_id != $request->sector_id) return false;
                if (!empty($positionIds) && !in_array((string)($et->employee?->position_id), $positionIds)) return false;
                return true;
            });
        });

        return response()->json([
            'data' => $rows->map(fn($t) => [
                'id'       => $t->id,
                'title'    => $t->title,
                'provider' => $t->provider ?? '—',
                'total'    => $t->employeeTrainings->count(),
                'employees' => $t->employeeTrainings->values()->map(fn($et) => [
                    'name'         => $et->employee?->full_name ?? '—',
                    'code'         => $et->employee?->code ?? '—',
                    'sector'       => $et->employee?->sector?->sector ?? '—',
                    'position'     => $et->employee?->position?->position ?? '—',
                    'score'        => $et->score,
                    'completed_at' => $et->updated_at?->toDateString(),
                ]),
            ]),
            'total' => $rows->count(),
        ]);
    }

    // ── 5. Validade de formações ─────────────────────────────────────────
    public function validityReport(Request $request): JsonResponse
    {
        $today = now()->toDateString();

        $query = EmployeeTraining::with(['employee.sector', 'employee.position', 'training'])
            ->whereHas('employee')
            ->whereNotNull('validity_months')
            ->whereNotNull('end_date')
            ->where('status', 'completed');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }
        if ($request->filled('sector_id')) {
            $query->whereHas('employee', fn($q) => $q->where('sector_id', $request->sector_id));
        }

        // Filtro por estado de validade (SQL nativo para suportar cálculo de data)
        $expiry   = $this->expiryExpr();
        $soon     = $this->addDaysExpr($today, 30);
        if ($request->filled('validity_status')) {
            match ($request->validity_status) {
                'expired'  => $query->whereRaw("{$expiry} < ?", [$today]),
                'expiring' => $query->whereRaw("{$expiry} >= ?", [$today])
                                    ->whereRaw("{$expiry} <= {$soon}"),
                'valid'    => $query->whereRaw("{$expiry} > {$soon}"),
                default    => null,
            };
        }

        $rows = $query->orderByRaw("{$expiry} ASC")->get();

        // KPIs totais (sem filtro de estado — sobre o conjunto filtrado por func/setor/formação)
        $kpiQuery = EmployeeTraining::whereHas('employee')
            ->whereNotNull('validity_months')
            ->whereNotNull('end_date')
            ->where('status', 'completed');
        if ($request->filled('employee_id')) $kpiQuery->where('employee_id', $request->employee_id);
        if ($request->filled('training_id')) $kpiQuery->where('training_id', $request->training_id);
        if ($request->filled('sector_id'))   $kpiQuery->whereHas('employee', fn($q) => $q->where('sector_id', $request->sector_id));

        $kpiAll      = $kpiQuery->get();
        $kpiExpired  = $kpiAll->filter(fn($r) => $r->validity_status === 'expired')->count();
        $kpiExpiring = $kpiAll->filter(fn($r) => $r->validity_status === 'expiring')->count();
        $kpiValid    = $kpiAll->filter(fn($r) => $r->validity_status === 'valid')->count();

        return response()->json([
            'data' => $rows->map(fn($r) => [
                'id'              => $r->id,
                'employee'        => $r->employee?->full_name ?? '—',
                'employee_code'   => $r->employee?->code ?? '—',
                'sector'          => $r->employee?->sector?->sector ?? '—',
                'position'        => $r->employee?->position?->position ?? '—',
                'training'        => $r->training?->title ?? '—',
                'provider'        => $r->training?->provider ?? '—',
                'end_date'        => $r->end_date?->toDateString(),
                'validity_months' => $r->validity_months,
                'expiry_date'     => $r->expiry_date?->toDateString(),
                'validity_status' => $r->validity_status,
            ]),
            'kpi' => [
                'expired'  => $kpiExpired,
                'expiring' => $kpiExpiring,
                'valid'    => $kpiValid,
                'total'    => $kpiAll->count(),
            ],
            'total' => $rows->count(),
        ]);
    }

    // ── 6. Gap Analysis ─────────────────────────────────────────────────
    public function gapAnalysis(Request $request): JsonResponse
    {
        $today = Carbon::today();

        // ── 6a. Formações obrigatórias não cumpridas ─────────────────────
        //
        // Pré-carregamento único: todos os funcionários activos + relações + inscrições.
        // Evita N queries por regra (anteriormente: affectedEmployeeIds, doneEmployeeIds
        // e Employee::whereIn por cada regra obrigatória).
        $allActiveEmployees = Employee::where('status', 'active')
            ->with(['department', 'position', 'sector'])
            ->orderBy('first_name')
            ->get()
            ->keyBy('id'); // indexed por id para O(1) lookup

        // Inscrições válidas (enrolled ou completed) agrupadas por training_id
        $enrolledByTraining = EmployeeTraining::whereIn('employee_id', $allActiveEmployees->keys())
            ->whereIn('status', ['enrolled', 'completed'])
            ->get()
            ->groupBy('training_id')
            ->map(fn($group) => $group->pluck('employee_id')->unique()->values());

        // Quiz aprovados: user_id → employee_id (para funcionários activos com user_id definido)
        $userToEmployee = $allActiveEmployees
            ->filter(fn($e) => $e->user_id !== null)
            ->mapWithKeys(fn($e) => [$e->user_id => $e->id]);

        // Todas as quiz_ids relevantes de uma vez
        $mandatoryRules = MandatoryTraining::with('training.quiz')->get();

        $quizIds = $mandatoryRules
            ->map(fn($r) => $r->training?->quiz?->id)
            ->filter()
            ->unique()
            ->values();

        // Aprovados por quiz_id → Collection de employee_ids
        $approvedByQuiz = collect();
        if ($quizIds->isNotEmpty()) {
            $approvedByQuiz = QuizAttempt::whereIn('quiz_id', $quizIds)
                ->where('passed', true)
                ->get()
                ->groupBy('quiz_id')
                ->map(function ($attempts) use ($userToEmployee) {
                    return $attempts
                        ->pluck('user_id')
                        ->unique()
                        ->map(fn($uid) => $userToEmployee->get($uid))
                        ->filter()
                        ->values();
                });
        }

        $mandatoryGaps = $mandatoryRules->flatMap(function ($rule) use ($allActiveEmployees, $enrolledByTraining, $approvedByQuiz) {
            // Filtrar funcionários afectados em memória (sem query)
            $affectedEmployees = match ($rule->target_type) {
                'department' => $allActiveEmployees->filter(fn($e) => $e->department_id == $rule->target_id),
                'position'   => $allActiveEmployees->filter(fn($e) => $e->position_id == $rule->target_id),
                default      => $allActiveEmployees,
            };

            if ($affectedEmployees->isEmpty()) return collect();

            $affectedIds = $affectedEmployees->keys();

            // IDs que já cumpriram — via inscrição
            $doneViaEnrollment = ($enrolledByTraining->get($rule->training_id) ?? collect())
                ->intersect($affectedIds);

            // IDs que já cumpriram — via quiz aprovado
            $quizId = $rule->training?->quiz?->id;
            $doneViaQuiz = $quizId
                ? ($approvedByQuiz->get($quizId) ?? collect())->intersect($affectedIds)
                : collect();

            $doneIds   = $doneViaEnrollment->merge($doneViaQuiz)->unique();
            $missingIds = $affectedIds->diff($doneIds);

            if ($missingIds->isEmpty()) return collect();

            return $affectedEmployees
                ->only($missingIds->toArray())
                ->sortBy('first_name')
                ->map(fn($e) => [
                    'employee_id'    => $e->id,
                    'employee_code'  => $e->code,
                    'employee_name'  => $e->full_name,
                    'department'     => $e->department?->department ?? '—',
                    'position'       => $e->position?->position ?? '—',
                    'sector'         => $e->sector?->sector ?? '—',
                    'training_id'    => $rule->training_id,
                    'training_title' => $rule->training?->title ?? '—',
                    'target_type'    => $rule->target_type,
                    'target_id'      => $rule->target_id,
                    'target_name'    => $rule->target_name,
                    'deadline_days'  => $rule->deadline_days,
                ])
                ->values();
        })->values();

        // ── 6b. Certificados expirados ou a expirar (30 dias) ────────────
        $today_str = $today->toDateString();
        $soon_str  = $today->copy()->addDays(30)->toDateString();

        $expiryGap  = $this->expiryExpr();
        $soonGap    = $this->addDaysExpr($today_str, 30);
        $expiredRows = EmployeeTraining::with(['employee.department', 'employee.position', 'training'])
            ->whereHas('employee')
            ->whereNotNull('validity_months')
            ->whereNotNull('end_date')
            ->where('status', 'completed')
            ->whereRaw("{$expiryGap} <= {$soonGap}")
            ->orderByRaw("{$expiryGap} ASC")
            ->get()
            ->map(function ($r) use ($today) {
                $expiry    = $r->end_date->copy()->addMonths($r->validity_months);
                $daysLeft  = $today->diffInDays($expiry, false);
                return [
                    'employee_id'    => $r->employee_id,
                    'employee_code'  => $r->employee?->code ?? '—',
                    'employee_name'  => $r->employee?->full_name ?? '—',
                    'department'     => $r->employee?->department?->department ?? '—',
                    'position'       => $r->employee?->position?->position ?? '—',
                    'training_id'    => $r->training_id,
                    'training_title' => $r->training?->title ?? '—',
                    'expiry_date'    => $expiry->toDateString(),
                    'days_left'      => $daysLeft,
                    'status'         => $daysLeft < 0 ? 'expired' : 'expiring',
                ];
            });

        // ── 6c. Plano vs execução — sessões com preenchimento abaixo de 70% ──
        $year = $request->input('year', Carbon::now()->year);
        $planGaps = TrainingSession::with('training:id,title,provider')
            ->withCount([
                'enrollments as enrolled_count',
            ])
            ->whereYear('planned_date', $year)
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('estimated_participants')
            ->get()
            ->map(function ($s) {
                $enrolled  = (int) $s->enrolled_count;
                $target    = $s->estimated_participants;
                $fillRate  = $target > 0 ? round(($enrolled / $target) * 100) : 0;
                return [
                    'session_id'             => $s->id,
                    'training_title'         => $s->training?->title ?? '—',
                    'training_provider'      => $s->training?->provider ?? '—',
                    'planned_date'           => $s->planned_date?->toDateString(),
                    'planned_date_fmt'       => $s->planned_date?->format('d/m/Y'),
                    'location'               => $s->location,
                    'status'                 => $s->status,
                    'estimated_participants' => $target,
                    'enrolled_count'         => $enrolled,
                    'fill_rate'              => $fillRate,
                    'gap'                    => max(0, $target - $enrolled),
                ];
            })
            ->filter(fn($s) => $s['fill_rate'] < 70)
            ->sortBy('fill_rate')
            ->values();

        return response()->json([
            'mandatory_gaps' => [
                'data'  => $mandatoryGaps,
                'total' => $mandatoryGaps->count(),
            ],
            'expired_certificates' => [
                'data'     => $expiredRows,
                'total'    => $expiredRows->count(),
                'expired'  => $expiredRows->where('status', 'expired')->count(),
                'expiring' => $expiredRows->where('status', 'expiring')->count(),
            ],
            'plan_gaps' => [
                'data'  => $planGaps,
                'total' => $planGaps->count(),
                'year'  => (int) $year,
            ],
        ]);
    }

    // ── Enviar relatório por email ───────────────────────────────────────
    public function sendEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email'   => 'required|email',
            'type'    => 'required|in:completed_trainings,employees_trainings,attendance,training_employees,validity',
            'subject' => 'nullable|string|max:200',
            'html'    => 'required|string',
        ]);

        $subject = $request->subject ?: 'Relatório HREminho — ' . now()->format('d/m/Y');

        Mail::html($request->html, function ($message) use ($request, $subject) {
            $message->to($request->email)->subject($subject);
        });

        return response()->json(['message' => 'Email enviado com sucesso.']);
    }
}
