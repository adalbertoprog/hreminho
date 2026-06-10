<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use App\Services\LeaveAttendanceSync;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeLeaveController extends Controller
{
    /** Resolve o Employee do utilizador autenticado, ou aborta 403. */
    private function resolveEmployee(): Employee
    {
        $user = Auth::user();
        $employee = Employee::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere(function ($q2) use ($user) {
                  $q2->whereNull('user_id')->where('email', $user->email);
              });
        })->first();

        if (! $employee) {
            abort(403, 'Conta não associada a nenhum funcionário.');
        }
        return $employee;
    }

    /** Funcionário submete novo pedido de licença. */
    public function store(Request $request): JsonResponse
    {
        $employee = $this->resolveEmployee();

        $data = $request->validate([
            'leave_type' => 'required|in:vacation,sick,unpaid',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string|max:1000',
        ]);

        $data['employee_id'] = $employee->id;
        $data['status']      = 'pending';
        $data['reason']      = $data['reason'] ?? '';

        $leave = Leave::create($data);

        return response()->json(['data' => $this->format($leave)], 201);
    }

    /** Funcionário cancela o seu próprio pedido pendente. */
    public function cancel(int $leaveId): JsonResponse
    {
        $employee = $this->resolveEmployee();
        $leave    = Leave::where('employee_id', $employee->id)->findOrFail($leaveId);

        if ($leave->status !== 'pending') {
            return response()->json(['message' => 'Só é possível cancelar pedidos pendentes.'], 422);
        }

        $leave->delete();
        return response()->json(null, 204);
    }

    /** Manager aprova pedido. */
    public function approve(Request $request, int $leaveId): JsonResponse
    {
        $leave = Leave::findOrFail($leaveId);
        $this->authorizeManager($leave);

        $data = $request->validate(['manager_comment' => 'nullable|string|max:1000']);

        $leave->update(['status' => 'approved', 'manager_comment' => $data['manager_comment'] ?? null]);
        $leave->refresh();
        (new LeaveAttendanceSync)->sync($leave);

        return response()->json(['data' => $this->format($leave->load('employee'))]);
    }

    /** Manager rejeita pedido. */
    public function reject(Request $request, int $leaveId): JsonResponse
    {
        $leave = Leave::findOrFail($leaveId);
        $this->authorizeManager($leave);

        $data = $request->validate(['manager_comment' => 'nullable|string|max:1000']);

        $leave->update(['status' => 'rejected', 'manager_comment' => $data['manager_comment'] ?? null]);

        return response()->json(['data' => $this->format($leave->load('employee'))]);
    }

    // ── Private helpers ──────────────────────────────────────────────────

    /**
     * Verifica que o utilizador pode gerir este leave:
     * admin/hr podem tudo; manager só dos seus dept/sector.
     */
    private function authorizeManager(Leave $leave): void
    {
        $user = Auth::user();
        if (in_array($user->role, ['admin', 'hr'])) {
            return;
        }

        $managerEmployee = Employee::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere(function ($q2) use ($user) {
                  $q2->whereNull('user_id')->where('email', $user->email);
              });
        })->first();

        if (! $managerEmployee) {
            abort(403);
        }

        $deptEmpIds   = Employee::whereHas('department', fn($q) => $q->where('manager_id', $managerEmployee->id))->pluck('id');
        $sectorEmpIds = Employee::whereHas('sector',     fn($q) => $q->where('manager_id', $managerEmployee->id))->pluck('id');
        $allowed      = $deptEmpIds->merge($sectorEmpIds)->unique();

        if (! $allowed->contains($leave->employee_id)) {
            abort(403, 'Não tem permissão para gerir este pedido.');
        }
    }

    private function format(Leave $l): array
    {
        return [
            'id'              => $l->id,
            'employee_id'     => $l->employee_id,
            'employee_name'   => $l->employee?->full_name,
            'leave_type'      => $l->leave_type,
            'start_date'      => $l->start_date?->format('Y-m-d'),
            'end_date'        => $l->end_date?->format('Y-m-d'),
            'reason'          => $l->reason,
            'status'          => $l->status,
            'manager_comment' => $l->manager_comment,
        ];
    }
}
