<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DepartmentController extends Controller
{
    /**
     * Listar todos os departamentos.
     */
    public function index(): AnonymousResourceCollection
    {
        $departments = Department::with('manager')
            ->orderBy('department')
            ->get();

        return DepartmentResource::collection($departments);
    }

    /**
     * Criar um novo departamento.
     */
    public function store(StoreDepartmentRequest $request): DepartmentResource
    {
        $department = Department::create($request->validated());

        return new DepartmentResource($department->load('manager'));
    }

    /**
     * Exibir um departamento específico.
     */
    public function show(Department $department): DepartmentResource
    {
        return new DepartmentResource($department->load('manager'));
    }

    /**
     * Atualizar um departamento.
     */
    public function update(UpdateDepartmentRequest $request, Department $department): DepartmentResource
    {
        $department->update($request->validated());

        return new DepartmentResource($department->fresh()->load('manager'));
    }

    /**
     * Remover um departamento.
     */
    public function destroy(Department $department): JsonResponse
    {
        if ($department->employees()->exists()) {
            return response()->json([
                'message' => 'Não é possível excluir este departamento pois existem funcionários vinculados a ele.',
            ], 422);
        }

        $department->delete();

        return response()->json(['message' => 'Departamento excluído com sucesso.'], 200);
    }
}
