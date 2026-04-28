<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployeeController extends Controller
{
    /**
     * Listar todos os funcionários.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Employee::with(['position', 'department', 'sector']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        if ($request->filled('sector_id')) {
            $query->where('sector_id', $request->sector_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Ordenação: sort=name_asc (default), name_desc, code_asc, code_desc
        match ($request->get('sort', 'name_asc')) {
            'name_desc' => $query->orderBy('first_name', 'desc')->orderBy('last_name', 'desc'),
            'code_asc'  => $query->orderBy('code', 'asc'),
            'code_desc' => $query->orderBy('code', 'desc'),
            default     => $query->orderBy('first_name', 'asc')->orderBy('last_name', 'asc'),
        };

        $employees = $query->paginate($request->get('per_page', 15));

        return EmployeeResource::collection($employees);
    }

    /**
     * Criar um novo funcionário.
     */
    public function store(StoreEmployeeRequest $request): EmployeeResource
    {
        $data = $request->validated();
        if (isset($data['photo'])) {
            $data['profile_photo'] = $data['photo'];
            unset($data['photo']);
        }
        $employee = Employee::create($data);

        return new EmployeeResource($employee->load(['position', 'department', 'sector']));
    }

    /**
     * Exibir um funcionário específico.
     */
    public function show(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee->load(['position', 'department', 'sector']));
    }

    /**
     * Atualizar um funcionário.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): EmployeeResource
    {
        $data = $request->validated();
        if (isset($data['photo'])) {
            $data['profile_photo'] = $data['photo'];
            unset($data['photo']);
        }
        $employee->update($data);

        return new EmployeeResource($employee->fresh()->load(['position', 'department', 'sector']));
    }

    /**
     * Remover um funcionário.
     */
    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();

        return response()->json(['message' => 'Funcionário excluído com sucesso.'], 200);
    }
}
