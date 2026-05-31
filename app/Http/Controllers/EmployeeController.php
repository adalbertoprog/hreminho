<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

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

        if ($request->boolean('all')) {
            return EmployeeResource::collection($query->get());
        }

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
            $data['profile_photo'] = $this->storePhoto($data['photo']);
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
            // Apagar foto antiga do storage (se for um path, não base64 legado)
            if ($employee->profile_photo && !str_starts_with($employee->profile_photo, 'data:') && strlen($employee->profile_photo) <= 500) {
                Storage::disk('public')->delete($employee->profile_photo);
            }
            $data['profile_photo'] = $this->storePhoto($data['photo']);
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
        // Apagar foto do storage ao eliminar o funcionário
        if ($employee->profile_photo && !str_starts_with($employee->profile_photo, 'data:') && strlen($employee->profile_photo) <= 500) {
            Storage::disk('public')->delete($employee->profile_photo);
        }
        $employee->delete();

        return response()->json(['message' => 'Funcionário excluído com sucesso.'], 200);
    }

    /**
     * Guarda uma foto (base64 ou URL de data) no storage público e devolve o path relativo.
     */
    private function storePhoto(string $photo): string
    {
        // Se for base64 data URI, gravar como ficheiro
        if (str_starts_with($photo, 'data:')) {
            $parts   = explode(',', $photo, 2);
            $imgData = base64_decode($parts[1] ?? '');
            preg_match('/data:image\/(\w+);/', $photo, $m);
            $ext      = $m[1] ?? 'jpg';
            $filename = 'employees/photos/' . uniqid() . '.' . $ext;
            Storage::disk('public')->put($filename, $imgData);
            return $filename;
        }

        // Se for base64 puro (sem prefixo data:), tentar gravar
        if (strlen($photo) > 500 && !str_contains($photo, '/')) {
            $imgData  = base64_decode($photo);
            $filename = 'employees/photos/' . uniqid() . '.jpg';
            Storage::disk('public')->put($filename, $imgData);
            return $filename;
        }

        // Já é um path de storage — devolver como está
        return $photo;
    }
}
