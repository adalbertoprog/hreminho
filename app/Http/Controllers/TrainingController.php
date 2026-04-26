<?php

namespace App\Http\Controllers;

use App\Models\EmployeeTraining;
use App\Models\Training;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    // ── Treinamentos (catálogo) ──────────────────────────

    public function index(Request $request): JsonResponse
    {
        $query = Training::withCount('employeeTrainings');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('title','like',"%{$s}%")->orWhere('provider','like',"%{$s}%"));
        }
        $rows = $query->orderBy('title')->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $rows->map(fn($t) => $this->formatTraining($t)),
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
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'provider'    => 'required|string|max:200',
        ]);
        $training = Training::create($data);
        return response()->json(['data' => $this->formatTraining($training->loadCount('employeeTrainings'))], 201);
    }

    public function show(Training $training): JsonResponse
    {
        return response()->json(['data' => $this->formatTraining($training->loadCount('employeeTrainings'))]);
    }

    public function update(Request $request, Training $training): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'sometimes|string|max:200',
            'description' => 'nullable|string',
            'provider'    => 'sometimes|string|max:200',
        ]);
        $training->update($data);
        return response()->json(['data' => $this->formatTraining($training->fresh()->loadCount('employeeTrainings'))]);
    }

    public function destroy(Training $training): JsonResponse
    {
        $training->delete();
        return response()->json(['message' => 'Treinamento excluído com sucesso.']);
    }

    // ── Inscrições (employee_trainings) ─────────────────

    public function enrollments(Request $request): JsonResponse
    {
        $query = EmployeeTraining::with(['employee', 'training']);
        if ($request->filled('training_id')) $query->where('training_id', $request->training_id);
        if ($request->filled('employee_id')) $query->where('employee_id', $request->employee_id);
        if ($request->filled('status'))      $query->where('status', $request->status);

        $rows = $query->orderByDesc('created_at')->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $rows->map(fn($e) => $this->formatEnrollment($e)),
            'meta' => [
                'current_page' => $rows->currentPage(),
                'last_page'    => $rows->lastPage(),
                'from'         => $rows->firstItem(),
                'to'           => $rows->lastItem(),
                'total'        => $rows->total(),
            ],
        ]);
    }

    public function enroll(Request $request): JsonResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'training_id' => 'required|exists:trainings,id',
            'status'      => 'nullable|in:enrolled,completed,failed',
            'score'       => 'nullable|numeric|min:0|max:100',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'notes'       => 'nullable|string|max:1000',
        ]);
        $enrollment = EmployeeTraining::create($data);
        return response()->json(['data' => $this->formatEnrollment($enrollment->load(['employee','training']))], 201);
    }

    public function updateEnrollment(Request $request, EmployeeTraining $enrollment): JsonResponse
    {
        $data = $request->validate([
            'employee_id' => 'sometimes|exists:employees,id',
            'training_id' => 'sometimes|exists:trainings,id',
            'status'      => 'sometimes|in:enrolled,completed,failed',
            'score'       => 'nullable|numeric|min:0|max:100',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'notes'       => 'nullable|string|max:1000',
        ]);
        $enrollment->update($data);
        return response()->json(['data' => $this->formatEnrollment($enrollment->fresh()->load(['employee','training']))]);
    }

    public function destroyEnrollment(EmployeeTraining $enrollment): JsonResponse
    {
        $enrollment->delete();
        return response()->json(['message' => 'Inscrição excluída com sucesso.']);
    }

    private function formatTraining(Training $t): array
    {
        return [
            'id'                      => $t->id,
            'title'                   => $t->title,
            'description'             => $t->description,
            'provider'                => $t->provider,
            'employee_trainings_count' => $t->employee_trainings_count ?? 0,
            'created_at'              => $t->created_at?->toDateTimeString(),
        ];
    }

    private function formatEnrollment(EmployeeTraining $e): array
    {
        return [
            'id'          => $e->id,
            'employee_id' => $e->employee_id,
            'training_id' => $e->training_id,
            'employee'    => $e->employee ? ['id' => $e->employee->id, 'full_name' => $e->employee->full_name] : null,
            'training'    => $e->training  ? ['id' => $e->training->id,  'title'     => $e->training->title]    : null,
            'status'      => $e->status,
            'score'       => $e->score,
            'start_date'  => $e->start_date?->toDateString(),
            'end_date'    => $e->end_date?->toDateString(),
            'notes'       => $e->notes,
            'created_at'  => $e->created_at?->toDateTimeString(),
        ];
    }
}
