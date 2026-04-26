<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Leave::with('employee');

        if ($request->filled('employee_id')) $query->where('employee_id', $request->employee_id);
        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('leave_type'))  $query->where('leave_type', $request->leave_type);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('employee', fn($q) =>
                $q->where('first_name','like',"%{$s}%")
                  ->orWhere('last_name','like',"%{$s}%")
            );
        }

        $rows = $query->orderByDesc('created_at')
                      ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $rows->map(fn($l) => $this->format($l)),
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
            'employee_id'     => 'required|exists:employees,id',
            'leave_type'      => 'required|in:vacation,sick,unpaid',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'reason'          => 'required|string|max:1000',
            'status'          => 'nullable|in:pending,approved,rejected',
            'manager_comment' => 'nullable|string|max:1000',
        ]);

        $leave = Leave::create($data);
        return response()->json(['data' => $this->format($leave->load('employee'))], 201);
    }

    public function show(Leave $leave): JsonResponse
    {
        return response()->json(['data' => $this->format($leave->load('employee'))]);
    }

    public function update(Request $request, Leave $leave): JsonResponse
    {
        $data = $request->validate([
            'employee_id'     => 'sometimes|exists:employees,id',
            'leave_type'      => 'sometimes|in:vacation,sick,unpaid',
            'start_date'      => 'sometimes|date',
            'end_date'        => 'sometimes|date|after_or_equal:start_date',
            'reason'          => 'sometimes|string|max:1000',
            'status'          => 'nullable|in:pending,approved,rejected',
            'manager_comment' => 'nullable|string|max:1000',
        ]);

        $leave->update($data);
        return response()->json(['data' => $this->format($leave->fresh()->load('employee'))]);
    }

    public function destroy(Leave $leave): JsonResponse
    {
        $leave->delete();
        return response()->json(['message' => 'Licença excluída com sucesso.']);
    }

    private function format(Leave $l): array
    {
        return [
            'id'              => $l->id,
            'employee_id'     => $l->employee_id,
            'employee'        => $l->employee ? ['id' => $l->employee->id, 'full_name' => $l->employee->full_name, 'code' => $l->employee->code] : null,
            'leave_type'      => $l->leave_type,
            'start_date'      => $l->start_date?->toDateString(),
            'end_date'        => $l->end_date?->toDateString(),
            'reason'          => $l->reason,
            'status'          => $l->status,
            'manager_comment' => $l->manager_comment,
            'created_at'      => $l->created_at?->toDateTimeString(),
        ];
    }
}
