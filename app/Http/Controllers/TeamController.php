<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /** Lista equipas de uma obra. */
    public function index(Project $project): JsonResponse
    {
        $teams = $project->teams()
            ->with([
                'leader:id,first_name,last_name,code',
                'employees:id,first_name,last_name,code',
                'vehicles:id,plate,brand,model',
            ])
            ->get()
            ->map(fn($t) => $this->format($t));

        return response()->json(['data' => $teams]);
    }

    public function store(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'leader_id' => 'nullable|exists:employees,id',
            'notes'     => 'nullable|string',
        ]);

        $team = $project->teams()->create($data);
        $team->load('leader:id,first_name,last_name,code', 'employees', 'vehicles');

        return response()->json(['data' => $this->format($team)], 201);
    }

    public function update(Request $request, Project $project, Team $team): JsonResponse
    {
        abort_if($team->project_id !== $project->id, 404);

        $data = $request->validate([
            'name'      => 'sometimes|required|string|max:255',
            'leader_id' => 'nullable|exists:employees,id',
            'notes'     => 'nullable|string',
        ]);

        $team->update($data);
        $team->load('leader:id,first_name,last_name,code', 'employees', 'vehicles');

        return response()->json(['data' => $this->format($team)]);
    }

    public function destroy(Project $project, Team $team): JsonResponse
    {
        abort_if($team->project_id !== $project->id, 404);
        $team->delete();
        return response()->json(null, 204);
    }

    // ── Membros ──────────────────────────────────────────────────────────

    /** Adiciona ou actualiza funcionário na equipa. */
    public function addEmployee(Request $request, Project $project, Team $team): JsonResponse
    {
        abort_if($team->project_id !== $project->id, 404);

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'role'        => 'nullable|string|max:100',
        ]);

        $team->employees()->syncWithoutDetaching([
            $data['employee_id'] => [
                'start_date' => $data['start_date'] ?? null,
                'end_date'   => $data['end_date']   ?? null,
                'role'       => $data['role']        ?? null,
            ],
        ]);

        $team->load('employees:id,first_name,last_name,code', 'vehicles:id,plate,brand,model', 'leader:id,first_name,last_name,code');
        return response()->json(['data' => $this->format($team)]);
    }

    /** Remove funcionário da equipa. */
    public function removeEmployee(Request $request, Project $project, Team $team): JsonResponse
    {
        abort_if($team->project_id !== $project->id, 404);

        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $team->employees()->detach($request->employee_id);

        return response()->json(null, 204);
    }

    /** Adiciona viatura à equipa. */
    public function addVehicle(Request $request, Project $project, Team $team): JsonResponse
    {
        abort_if($team->project_id !== $project->id, 404);

        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $team->vehicles()->syncWithoutDetaching([
            $data['vehicle_id'] => [
                'start_date' => $data['start_date'] ?? null,
                'end_date'   => $data['end_date']   ?? null,
            ],
        ]);

        $team->load('employees:id,first_name,last_name,code', 'vehicles:id,plate,brand,model', 'leader:id,first_name,last_name,code');
        return response()->json(['data' => $this->format($team)]);
    }

    /** Remove viatura da equipa. */
    public function removeVehicle(Request $request, Project $project, Team $team): JsonResponse
    {
        abort_if($team->project_id !== $project->id, 404);

        $request->validate(['vehicle_id' => 'required|exists:vehicles,id']);
        $team->vehicles()->detach($request->vehicle_id);

        return response()->json(null, 204);
    }

    // ── helpers ──────────────────────────────────────────────────────────

    private function format(Team $t): array
    {
        return [
            'id'         => $t->id,
            'project_id' => $t->project_id,
            'name'       => $t->name,
            'notes'      => $t->notes,
            'leader'     => $t->leader ? [
                'id'   => $t->leader->id,
                'name' => $t->leader->full_name,
                'code' => $t->leader->code,
            ] : null,
            'employees'  => ($t->relationLoaded('employees') ? $t->employees : collect())->map(fn($e) => [
                'id'         => $e->id,
                'name'       => $e->full_name,
                'code'       => $e->code,
                'start_date' => $e->pivot?->start_date,
                'end_date'   => $e->pivot?->end_date,
                'role'       => $e->pivot?->role,
            ]),
            'vehicles'   => ($t->relationLoaded('vehicles') ? $t->vehicles : collect())->map(fn($v) => [
                'id'         => $v->id,
                'plate'      => $v->plate,
                'label'      => trim("{$v->brand} {$v->model}") ?: $v->plate,
                'start_date' => $v->pivot?->start_date,
                'end_date'   => $v->pivot?->end_date,
            ]),
        ];
    }
}
