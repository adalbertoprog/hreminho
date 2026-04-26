<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Attendance::with('employee');

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
            'check_out'   => 'nullable|date_format:H:i,H:i:s',
            'status'      => 'required|in:present,absent,late,holiday,on_leave',
        ]);

        $attendance = Attendance::create($data);
        return response()->json(['data' => $this->format($attendance->load('employee'))], 201);
    }

    public function show(Attendance $attendance): JsonResponse
    {
        return response()->json(['data' => $this->format($attendance->load('employee'))]);
    }

    public function update(Request $request, Attendance $attendance): JsonResponse
    {
        $data = $request->validate([
            'employee_id' => 'sometimes|exists:employees,id',
            'date'        => 'sometimes|date',
            'check_in'    => 'nullable|date_format:H:i,H:i:s',
            'check_out'   => 'nullable|date_format:H:i,H:i:s',
            'status'      => 'sometimes|in:present,absent,late,holiday,on_leave',
        ]);

        $attendance->update($data);
        return response()->json(['data' => $this->format($attendance->fresh()->load('employee'))]);
    }

    public function destroy(Attendance $attendance): JsonResponse
    {
        $attendance->delete();
        return response()->json(['message' => 'Presença excluída com sucesso.']);
    }

    private function format(Attendance $a): array
    {
        return [
            'id'          => $a->id,
            'employee_id' => $a->employee_id,
            'employee'    => $a->employee ? ['id' => $a->employee->id, 'full_name' => $a->employee->full_name, 'code' => $a->employee->code] : null,
            'date'        => $a->date?->toDateString(),
            'check_in'    => $a->check_in,
            'check_out'   => $a->check_out,
            'status'      => $a->status,
            'created_at'  => $a->created_at?->toDateTimeString(),
        ];
    }
}
