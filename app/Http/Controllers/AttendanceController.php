<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * IDs dos funcionários que o utilizador actual pode gerir.
     * Admin/HR: todos. Manager: apenas os do(s) seu(s) dept/sector.
     */
    private function managedEmployeeIds(): ?array
    {
        $user = Auth::user();
        if (in_array($user->role, ['admin', 'hr'])) {
            return null; // sem restrição
        }

        // Manager: encontrar o employee ligado ao user
        $employee = $user->employee;
        if (!$employee) return [];

        $ids = collect();

        // Funcionários dos departamentos geridos
        $ids = $ids->merge(
            Employee::whereHas('department', fn($q) => $q->where('manager_id', $employee->id))
                    ->pluck('id')
        );

        // Funcionários dos sectores geridos
        $ids = $ids->merge(
            Employee::whereHas('sector', fn($q) => $q->where('manager_id', $employee->id))
                    ->pluck('id')
        );

        return $ids->unique()->values()->toArray();
    }

    public function index(Request $request): JsonResponse
    {
        $query = Attendance::with('employee')->whereHas('employee');

        // Restringir aos funcionários geridos (manager)
        $managed = $this->managedEmployeeIds();
        if ($managed !== null) {
            $query->whereIn('employee_id', $managed);
        }

        if ($request->filled('employee_id')) $query->where('employee_id', $request->employee_id);
        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('date_from'))   $query->whereDate('date', '>=', $request->date_from);
        if ($request->filled('date_to'))     $query->whereDate('date', '<=', $request->date_to);
        if ($request->filled('date'))        $query->whereDate('date', $request->date);

        $rows = $query->orderByDesc('date')->orderBy('employee_id')
                      ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $rows->map(fn($a) => $this->format($a)),
            'meta' => [
                'current_page' => $rows->currentPage(),
                'last_page'    => $rows->lastPage(),
                'from'         => $rows->firstItem(),
                'to'           => $rows->lastItem(),
                'total'        => $rows->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
            'check_in'    => 'nullable|date_format:H:i,H:i:s',
            'lunch_out'   => 'nullable|date_format:H:i,H:i:s',
            'lunch_in'    => 'nullable|date_format:H:i,H:i:s',
            'check_out'   => 'nullable|date_format:H:i,H:i:s',
            'status'      => 'nullable|in:present,absent,late,holiday,on_leave',
            'notes'       => 'nullable|string|max:500',
            'force'       => 'boolean',
        ]);

        // Verificar se o manager pode gerir este funcionário
        $this->authorizeEmployee($data['employee_id']);

        // Verificar registo existente na mesma data (sem force)
        if (empty($data['force'])) {
            $existing = Attendance::where('employee_id', $data['employee_id'])
                ->whereDate('date', $data['date'])
                ->first();

            if ($existing) {
                $statusLabel = [
                    'on_leave' => 'De Licença',
                    'absent'   => 'Ausente',
                    'holiday'  => 'Feriado',
                    'present'  => 'Presente',
                    'late'     => 'Atrasado',
                ];
                return response()->json([
                    'conflict' => true,
                    'message'  => 'Já existe um registo para este funcionário nesta data.',
                    'existing' => $this->format($existing->load('employee')),
                    'existing_status_label' => $statusLabel[$existing->status] ?? $existing->status,
                ], 409);
            }
        }

        unset($data['force']);
        $data = $this->resolveStatus($data);
        $attendance = Attendance::create($data);
        return response()->json(['data' => $this->format($attendance->load('employee'))], 201);
    }

    public function show(Attendance $attendance): JsonResponse
    {
        $this->authorizeEmployee($attendance->employee_id);
        return response()->json(['data' => $this->format($attendance->load('employee'))]);
    }

    public function update(Request $request, Attendance $attendance): JsonResponse
    {
        $this->authorizeEmployee($attendance->employee_id);

        $data = $request->validate([
            'employee_id' => 'sometimes|exists:employees,id',
            'date'        => 'sometimes|date',
            'check_in'    => 'nullable|date_format:H:i,H:i:s',
            'lunch_out'   => 'nullable|date_format:H:i,H:i:s',
            'lunch_in'    => 'nullable|date_format:H:i,H:i:s',
            'check_out'   => 'nullable|date_format:H:i,H:i:s',
            'status'      => 'nullable|in:present,absent,late,holiday,on_leave',
            'notes'       => 'nullable|string|max:500',
            'force'       => 'boolean',
        ]);

        unset($data['force']);
        $data = $this->resolveStatus($data, $attendance);
        $attendance->update($data);
        return response()->json(['data' => $this->format($attendance->fresh()->load('employee'))]);
    }

    public function destroy(Attendance $attendance): JsonResponse
    {
        $this->authorizeEmployee($attendance->employee_id);
        $attendance->delete();
        return response()->json(null, 204);
    }

    /**
     * Lança 403 se o utilizador (manager) não puder gerir este funcionário.
     */
    private function authorizeEmployee(int $employeeId): void
    {
        $managed = $this->managedEmployeeIds();
        if ($managed !== null && !in_array($employeeId, $managed)) {
            abort(403, 'Não tem permissão para gerir este funcionário.');
        }
    }

    /**
     * Calcular status automaticamente, excepto se for feriado ou licença.
     */
    private function resolveStatus(array $data, ?Attendance $existing = null): array
    {
        $status = $data['status'] ?? $existing?->status;

        // Se foi fornecido um status explícito (qualquer valor válido), respeitar
        if (!empty($data['status'])) {
            $data['status'] = $status;
            return $data;
        }

        // Sem status explícito → calcular automaticamente com base no check_in
        $checkIn    = $data['check_in'] ?? $existing?->check_in;
        $employeeId = $data['employee_id'] ?? $existing?->employee_id;
        $employee   = $employeeId ? Employee::find($employeeId) : null;

        $data['status'] = Attendance::computeStatus($checkIn, $employee);
        return $data;
    }

    private function format(Attendance $a): array
    {
        return [
            'id'                     => $a->id,
            'employee_id'            => $a->employee_id,
            'employee'               => $a->employee ? [
                'id'        => $a->employee->id,
                'full_name' => $a->employee->full_name,
                'code'      => $a->employee->code,
            ] : null,
            'date'                   => $a->date?->toDateString(),
            'check_in'               => $a->check_in  ? substr($a->check_in,  0, 5) : null,
            'lunch_out'              => $a->lunch_out ? substr($a->lunch_out, 0, 5) : null,
            'lunch_in'               => $a->lunch_in  ? substr($a->lunch_in,  0, 5) : null,
            'check_out'              => $a->check_out ? substr($a->check_out, 0, 5) : null,
            'status'                 => $a->status,
            'notes'                  => $a->notes,
            'leave_id'               => $a->leave_id,
            'worked_minutes'         => $a->worked_minutes,
            'worked_hours_formatted' => $a->worked_hours_formatted,
        ];
    }
}
