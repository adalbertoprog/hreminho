<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCompany;
use App\Services\DocsElectroMinhoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectCompanyController extends Controller
{
    public function __construct(private DocsElectroMinhoService $docsem) {}

    /**
     * GET /api/v1/projects/{project}/companies
     * Lista as empresas subcontratadas associadas a esta obra.
     */
    public function index(Project $project): JsonResponse
    {
        Gate::authorize('view-projects');

        $companies = $project->companies()->orderBy('empresa_nome')->get();

        return response()->json(['data' => $companies->map(fn($c) => $this->format($c))]);
    }

    /**
     * GET /api/v1/docsem/empresas
     * Pesquisa empresas no DocsElectro-Minho para o picker.
     */
    public function searchDocsem(Request $request): JsonResponse
    {
        Gate::authorize('manage-projects');

        if (! $this->docsem->estaConfigurado()) {
            return response()->json(['erro' => 'Integracao DocsElectroMinho nao configurada.'], 503);
        }

        $filtros = array_filter([
            'search'   => $request->input('search'),
            'tipo'     => $request->input('tipo'),
            'per_page' => $request->input('per_page', 200),
        ]);

        $result = $this->docsem->getEmpresas($filtros);

        if (isset($result['erro'])) {
            return response()->json(['erro' => $result['erro']], 502);
        }

        return response()->json($result);
    }

    /**
     * POST /api/v1/projects/{project}/companies
     * Associa uma empresa (do DocsEM) a esta obra.
     *
     * Body: { docsem_empresa_id, empresa_nome, empresa_nif?, data_entrada?, data_saida?, observacoes? }
     */
    public function store(Request $request, Project $project): JsonResponse
    {
        Gate::authorize('manage-projects');

        $data = $request->validate([
            'docsem_empresa_id' => 'required|integer',
            'empresa_nome'      => 'required|string|max:255',
            'empresa_nif'       => 'nullable|string|max:20',
            'data_entrada'      => 'nullable|date',
            'data_saida'        => 'nullable|date|after_or_equal:data_entrada',
            'observacoes'       => 'nullable|string',
        ]);

        // Verificar se ja existe
        $existing = $project->companies()
            ->where('docsem_empresa_id', $data['docsem_empresa_id'])
            ->first();

        if ($existing) {
            return response()->json(['erro' => 'Esta empresa ja esta associada a esta obra.'], 422);
        }

        $company = $project->companies()->create($data);

        return response()->json(['data' => $this->format($company)], 201);
    }

    /**
     * PUT /api/v1/projects/{project}/companies/{company}
     * Actualiza os dados da associacao (datas, observacoes).
     */
    public function update(Request $request, Project $project, ProjectCompany $company): JsonResponse
    {
        Gate::authorize('manage-projects');

        if ($company->project_id !== $project->id) {
            abort(404);
        }

        $data = $request->validate([
            'data_entrada' => 'nullable|date',
            'data_saida'   => 'nullable|date|after_or_equal:data_entrada',
            'observacoes'  => 'nullable|string',
        ]);

        $company->update($data);

        return response()->json(['data' => $this->format($company->fresh())]);
    }

    /**
     * DELETE /api/v1/projects/{project}/companies/{company}
     * Remove a associacao empresa <-> obra.
     */
    public function destroy(Project $project, ProjectCompany $company): JsonResponse
    {
        Gate::authorize('manage-projects');

        if ($company->project_id !== $project->id) {
            abort(404);
        }

        $company->delete();

        return response()->json(null, 204);
    }

    // ── helpers ──────────────────────────────────────────────────────────────

    private function format(ProjectCompany $c): array
    {
        return [
            'id'                => $c->id,
            'project_id'        => $c->project_id,
            'docsem_empresa_id' => $c->docsem_empresa_id,
            'empresa_nome'      => $c->empresa_nome,
            'empresa_nif'       => $c->empresa_nif,
            'data_entrada'      => $c->data_entrada?->format('Y-m-d'),
            'data_saida'        => $c->data_saida?->format('Y-m-d'),
            'observacoes'       => $c->observacoes,
            'employees_count'   => $c->employees_count ?? 0,
        ];
    }
}
