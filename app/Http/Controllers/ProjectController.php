<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCompany;
use App\Services\DocsElectroMinhoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function __construct(private DocsElectroMinhoService $docsem) {}

    public function index(Request $request): JsonResponse
    {
        $q = Project::withCount(['teams', 'companies'])
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
            'name'           => 'required|string|max:255',
            'reference'      => 'nullable|string|max:100|unique:projects,reference',
            'client'         => 'nullable|string|max:255',
            'location'       => 'nullable|string|max:255',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'status'         => 'nullable|in:planned,active,completed,cancelled',
            'notes'          => 'nullable|string',
            'docsem_obra_id' => 'nullable|integer',
        ]);

        $project = Project::create($data);
        return response()->json(['data' => $this->format($project->load('teams'))], 201);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'name'           => 'sometimes|required|string|max:255',
            'reference'      => 'nullable|string|max:100|unique:projects,reference,' . $project->id,
            'client'         => 'nullable|string|max:255',
            'location'       => 'nullable|string|max:255',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'status'         => 'nullable|in:planned,active,completed,cancelled',
            'notes'          => 'nullable|string',
            'docsem_obra_id' => 'nullable|integer',
        ]);

        $project->update($data);
        return response()->json(['data' => $this->format($project->fresh('teams'))]);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();
        return response()->json(null, 204);
    }

    // ── Proxy de pesquisa de obras no DocsEM ──────────────────────────────

    /**
     * GET /api/v1/docsem/obras
     * Pesquisa obras no DocsElectro-Minho para o picker.
     */
    public function searchDocsemObras(Request $request): JsonResponse
    {
        Gate::authorize('manage-projects');

        $filtros = array_filter([
            'search'   => $request->input('search'),
            'estado'   => $request->input('estado', 'ativas'),
            'per_page' => 100,
        ]);

        $result = $this->docsem->getObras($filtros);

        if (isset($result['erro'])) {
            return response()->json(['error' => $result['erro']], 502);
        }

        return response()->json($result);
    }

    // ── Sincronizacao com DocsEM ──────────────────────────────────────────

    /**
     * POST /api/v1/projects/{project}/sync-docsem
     *
     * Sincroniza os dados da obra e as empresas associadas a partir do DocsEM.
     * Requer que a obra tenha docsem_obra_id definido.
     */
    public function syncDocsem(Project $project): JsonResponse
    {
        Gate::authorize('manage-projects');

        if (! $project->docsem_obra_id) {
            return response()->json(['error' => 'Esta obra nao esta ligada ao DocsElectro-Minho.'], 422);
        }

        $obra = $this->docsem->getObra($project->docsem_obra_id);

        if (isset($obra['erro'])) {
            return response()->json(['error' => $obra['erro']], 502);
        }

        // Mapa de estados DocsEM -> HREminho
        $statusMap = [
            'planeamento' => 'planned',
            'em_curso'    => 'active',
            'concluida'   => 'completed',
            'suspensa'    => 'cancelled',
            'cancelada'   => 'cancelled',
        ];

        // Actualizar campos da obra
        $project->update([
            'name'             => $obra['nome'],
            'location'         => trim(($obra['localidade'] ?? '') . ' ' . ($obra['distrito'] ?? '')) ?: $project->location,
            'start_date'       => $obra['data_inicio']       ?? $project->start_date,
            'end_date'         => $obra['data_fim_prevista']  ?? $project->end_date,
            'status'           => $statusMap[$obra['estado']] ?? $project->status,
            'docsem_synced_at' => now(),
        ]);

        // Sincronizar empresas associadas
        $empresasSincronizadas = 0;
        $empresasIgnoradas     = 0;

        foreach ($obra['empresas'] ?? [] as $emp) {
            // Ignorar empresas sem ID ou nome
            if (empty($emp['id']) || empty($emp['nome'])) {
                $empresasIgnoradas++;
                continue;
            }

            // upsert — nao duplicar se ja existir
            ProjectCompany::updateOrCreate(
                [
                    'project_id'        => $project->id,
                    'docsem_empresa_id' => $emp['id'],
                ],
                [
                    'empresa_nome'    => $emp['nome'],
                    'empresa_nif'     => $emp['nif']              ?? null,
                    'data_entrada'    => $emp['data_entrada']      ?? null,
                    'data_saida'      => $emp['data_saida']        ?? null,
                    'employees_count' => $emp['employees_count']   ?? $emp['tecnicos_count'] ?? 0,
                ]
            );
            $empresasSincronizadas++;
        }

        return response()->json([
            'message'               => 'Sincronizacao concluida.',
            'data'                  => $this->format($project->fresh(['teams'])),
            'empresas_sincronizadas'=> $empresasSincronizadas,
            'empresas_ignoradas'    => $empresasIgnoradas,
        ]);
    }

    // ── helpers ──────────────────────────────────────────────────────────

    private function format(Project $p): array
    {
        return [
            'id'               => $p->id,
            'name'             => $p->name,
            'reference'        => $p->reference,
            'client'           => $p->client,
            'location'         => $p->location,
            'start_date'       => $p->start_date?->format('Y-m-d'),
            'end_date'         => $p->end_date?->format('Y-m-d'),
            'status'           => $p->status,
            'notes'            => $p->notes,
            'docsem_obra_id'   => $p->docsem_obra_id,
            'docsem_synced_at' => $p->docsem_synced_at?->toIso8601String(),
            'teams_count'      => $p->teams_count ?? $p->teams->count(),
            'companies_count'  => $p->companies_count ?? $p->companies()->count(),
            'teams'            => ($p->relationLoaded('teams') ? $p->teams : collect())->map(fn($t) => [
                'id'     => $t->id,
                'name'   => $t->name,
                'leader' => $t->leader?->full_name,
            ]),
        ];
    }
}
