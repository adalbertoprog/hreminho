<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Training;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    // ── 1. Formações concluídas ──────────────────────────────────────
    public function completedTrainings(Request $request): JsonResponse
    {
        $query = EmployeeTraining::with(['employee.sector', 'training'])
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
        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
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
        $query = Attendance::with(['employee.sector', 'employee.position']);

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
                'id'        => $r->id,
                'employee'  => $r->employee?->full_name ?? '—',
                'sector'    => $r->employee?->sector?->sector ?? '—',
                'date'      => $r->date?->toDateString(),
                'check_in'  => $r->check_in ? substr($r->check_in, 0, 5) : '—',
                'check_out' => $r->check_out ? substr($r->check_out, 0, 5) : '—',
                'status'    => $r->status,
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
                ->with(['employee.sector', 'employee.position']),
        ])->whereHas('employeeTrainings', fn($q) => $q->where('status', 'completed'));

        if ($request->filled('sector_id')) {
            $query->whereHas('employeeTrainings', fn($q) => $q->where('status', 'completed')
                ->whereHas('employee', fn($eq) => $eq->where('sector_id', $request->sector_id)));
        }
        if ($request->filled('training_id')) {
            $query->where('id', $request->training_id);
        }

        $rows = $query->orderBy('title')->get();

        return response()->json([
            'data' => $rows->map(fn($t) => [
                'id'       => $t->id,
                'title'    => $t->title,
                'provider' => $t->provider ?? '—',
                'total'    => $t->employeeTrainings->count(),
                'employees' => $t->employeeTrainings->map(fn($et) => [
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
        if ($request->filled('validity_status')) {
            match ($request->validity_status) {
                'expired'  => $query->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) < ?", [$today]),
                'expiring' => $query->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) >= ?", [$today])
                                    ->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) <= DATE_ADD(?, INTERVAL 30 DAY)", [$today]),
                'valid'    => $query->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) > DATE_ADD(?, INTERVAL 30 DAY)", [$today]),
                default    => null,
            };
        }

        $rows = $query->orderByRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) ASC")->get();

        // KPIs totais (sem filtro de estado — sobre o conjunto filtrado por func/setor/formação)
        $kpiQuery = EmployeeTraining::whereNotNull('validity_months')
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
