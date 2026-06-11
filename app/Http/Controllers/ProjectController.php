<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = Project::withCount('teams')
            ->with(['teams.leader:id,first_name,last_name']);

        if ($request->status) {
            $q->where('status', $request->status);
        }
        if ($request->search) {
            $term = '%' . $request->search . '%';
            $q->where(fn($s) => $s->where('name', 'like', $term)
                                  ->orWhere('client', 'like', $term)
                                  ->orWhere('reference', 'like', $term));
        }

        return response()->json(['data' => $q->orderByDesc('start_date')->get()->map(fn($p) => $this->format($p))]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'reference'  => 'nullable|string|max:100|unique:projects,reference',
            'client'     => 'nullable|string|max:255',
            'location'   => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'nullable|in:planned,active,completed,cancelled',
            'notes'      => 'nullable|string',
        ]);

        $project = Project::create($data);
        return response()->json(['data' => $this->format($project->load('teams'))], 201);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'name'       => 'sometimes|required|string|max:255',
            'reference'  => 'nullable|string|max:100|unique:projects,reference,' . $project->id,
            'client'     => 'nullable|string|max:255',
            'location'   => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'nullable|in:planned,active,completed,cancelled',
            'notes'      => 'nullable|string',
        ]);

        $project->update($data);
        return response()->json(['data' => $this->format($project->fresh('teams'))]);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();
        return response()->json(null, 204);
    }

    // ── helpers ──────────────────────────────────────────────────────────

    private function format(Project $p): array
    {
        return [
            'id'          => $p->id,
            'name'        => $p->name,
            'reference'   => $p->reference,
            'client'      => $p->client,
            'location'    => $p->location,
            'start_date'  => $p->start_date?->format('Y-m-d'),
            'end_date'    => $p->end_date?->format('Y-m-d'),
            'status'      => $p->status,
            'notes'       => $p->notes,
            'teams_count' => $p->teams_count ?? $p->teams->count(),
            'teams'       => ($p->relationLoaded('teams') ? $p->teams : collect())->map(fn($t) => [
                'id'     => $t->id,
                'name'   => $t->name,
                'leader' => $t->leader?->full_name,
            ]),
        ];
    }
}
