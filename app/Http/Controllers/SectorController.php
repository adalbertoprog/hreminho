<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Sector::with(['department', 'manager'])
                       ->withCount('employees');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('sector', 'like', "%{$s}%");
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Listagem simples (sem paginação) usada pelos dropdowns
        if ($request->boolean('all')) {
            $rows = $query->orderBy('sector')->get();
            return response()->json(['data' => $rows->map(fn($s) => $this->format($s))]);
        }

        $rows = $query->orderBy('sector')->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $rows->map(fn($s) => $this->format($s)),
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
            'sector'        => 'required|string|max:200',
            'department_id' => 'required|exists:departments,id',
            'manager_id'    => 'nullable|exists:employees,id',
        ]);

        $sector = Sector::create($data);

        return response()->json(
            ['data' => $this->format($sector->load(['department', 'manager'])->loadCount('employees'))],
            201
        );
    }

    public function show(Sector $sector): JsonResponse
    {
        return response()->json(['data' => $this->format($sector->load(['department', 'manager'])->loadCount('employees'))]);
    }

    public function update(Request $request, Sector $sector): JsonResponse
    {
        $data = $request->validate([
            'sector'        => 'sometimes|string|max:200',
            'department_id' => 'sometimes|exists:departments,id',
            'manager_id'    => 'nullable|exists:employees,id',
        ]);

        $sector->update($data);

        return response()->json(['data' => $this->format($sector->fresh()->load(['department', 'manager'])->loadCount('employees'))]);
    }

    public function destroy(Sector $sector): JsonResponse
    {
        if ($sector->employees()->exists()) {
            return response()->json(['message' => 'Não é possível eliminar um setor com funcionários associados.'], 422);
        }

        $sector->delete();

        return response()->json(['message' => 'Setor eliminado com sucesso.']);
    }

    private function format(Sector $s): array
    {
        return [
            'id'              => $s->id,
            'sector'          => $s->sector,
            'department_id'   => $s->department_id,
            'manager_id'      => $s->manager_id,
            'department'      => $s->department ? ['id' => $s->department->id, 'department' => $s->department->department] : null,
            'manager'         => $s->manager    ? ['id' => $s->manager->id,    'full_name'  => $s->manager->full_name]     : null,
            'employees_count' => $s->employees_count ?? 0,
            'created_at'      => $s->created_at?->toDateTimeString(),
        ];
    }
}
