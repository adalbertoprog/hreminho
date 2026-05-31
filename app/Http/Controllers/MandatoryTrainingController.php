<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\MandatoryTraining;
use App\Models\Position;
use App\Models\Training;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MandatoryTrainingController extends Controller
{
    /** Lista todas as regras com dados de cumprimento */
    public function index(): JsonResponse
    {
        Gate::authorize('manage-hr');

        $rules = MandatoryTraining::with('training')->get()->map(fn($r) => $this->format($r));

        return response()->json(['data' => $rules]);
    }

    /** Cria uma nova regra */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('manage-hr');

        $data = $request->validate([
            'training_id'   => 'required|exists:trainings,id',
            'target_type'   => 'required|in:all,department,position',
            'target_id'     => 'nullable|integer',
            'deadline_days' => 'nullable|integer|min:1|max:3650',
            'notes'         => 'nullable|string|max:500',
        ]);

        // Validar target_id consoante target_type
        if ($data['target_type'] === 'department') {
            $request->validate(['target_id' => 'required|exists:departments,id']);
        } elseif ($data['target_type'] === 'position') {
            $request->validate(['target_id' => 'required|exists:positions,id']);
        } else {
            $data['target_id'] = null;
        }

        // Verificar unicidade manualmente (para melhor mensagem de erro)
        $exists = MandatoryTraining::where('training_id', $data['training_id'])
            ->where('target_type', $data['target_type'])
            ->where('target_id', $data['target_id'] ?? null)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Esta formação já é obrigatória para o âmbito selecionado.'], 422);
        }

        $rule = MandatoryTraining::create($data);

        return response()->json(['data' => $this->format($rule->load('training'))], 201);
    }

    /** Atualiza uma regra */
    public function update(Request $request, MandatoryTraining $mandatoryTraining): JsonResponse
    {
        Gate::authorize('manage-hr');

        $data = $request->validate([
            'deadline_days' => 'nullable|integer|min:1|max:3650',
            'notes'         => 'nullable|string|max:500',
        ]);

        $mandatoryTraining->update($data);

        return response()->json(['data' => $this->format($mandatoryTraining->load('training'))]);
    }

    /** Remove uma regra */
    public function destroy(MandatoryTraining $mandatoryTraining): JsonResponse
    {
        Gate::authorize('manage-hr');

        $mandatoryTraining->delete();

        return response()->json(null, 204);
    }

    /** Compliance: funcionários em falta para uma regra específica */
    public function gaps(MandatoryTraining $mandatoryTraining): JsonResponse
    {
        Gate::authorize('manage-hr');

        $affectedIds = $mandatoryTraining->scopeAffectedEmployeeIds();

        // IDs que já concluíram ou estão inscritos
        $doneIds = EmployeeTraining::whereIn('employee_id', $affectedIds)
            ->where('training_id', $mandatoryTraining->training_id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->pluck('employee_id')
            ->unique();

        $missingEmployees = Employee::whereIn('id', $affectedIds->diff($doneIds))
            ->with(['department', 'position'])
            ->orderBy('first_name')
            ->get()
            ->map(fn($e) => [
                'id'         => $e->id,
                'code'       => $e->code,
                'full_name'  => $e->full_name,
                'department' => $e->department?->department,
                'position'   => $e->position?->position,
                'hire_date'  => $e->hire_date?->toDateString(),
            ]);

        return response()->json([
            'data'    => $missingEmployees,
            'summary' => [
                'total'   => $affectedIds->count(),
                'done'    => $doneIds->count(),
                'missing' => $missingEmployees->count(),
                'rate'    => $affectedIds->count() > 0
                    ? round(($doneIds->count() / $affectedIds->count()) * 100)
                    : 0,
            ],
        ]);
    }

    /** Sumário global de cumprimento (para dashboard) */
    public function compliance(): JsonResponse
    {
        Gate::authorize('manage-hr');

        $rules = MandatoryTraining::with('training')->get();

        $summary = $rules->map(function ($rule) {
            $affectedIds = $rule->scopeAffectedEmployeeIds();
            $doneIds = EmployeeTraining::whereIn('employee_id', $affectedIds)
                ->where('training_id', $rule->training_id)
                ->whereIn('status', ['enrolled', 'completed'])
                ->pluck('employee_id')->unique();

            $total   = $affectedIds->count();
            $done    = $doneIds->count();
            $missing = $total - $done;
            $rate    = $total > 0 ? round(($done / $total) * 100) : 0;

            return [
                'id'           => $rule->id,
                'training'     => $rule->training->title,
                'target_type'  => $rule->target_type,
                'target_name'  => $rule->target_name,
                'deadline_days'=> $rule->deadline_days,
                'total'        => $total,
                'done'         => $done,
                'missing'      => $missing,
                'rate'         => $rate,
            ];
        });

        return response()->json(['data' => $summary]);
    }

    private function format(MandatoryTraining $r): array
    {
        $affectedIds = $r->scopeAffectedEmployeeIds();
        $doneIds = EmployeeTraining::whereIn('employee_id', $affectedIds)
            ->where('training_id', $r->training_id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->pluck('employee_id')->unique();

        $total   = $affectedIds->count();
        $done    = $doneIds->count();
        $rate    = $total > 0 ? round(($done / $total) * 100) : 0;

        return [
            'id'            => $r->id,
            'training_id'   => $r->training_id,
            'training_title'=> $r->training?->title,
            'target_type'   => $r->target_type,
            'target_id'     => $r->target_id,
            'target_name'   => $r->target_name,
            'deadline_days' => $r->deadline_days,
            'notes'         => $r->notes,
            'total'         => $total,
            'done'          => $done,
            'missing'       => $total - $done,
            'rate'          => $rate,
            'created_at'    => $r->created_at?->toDateTimeString(),
        ];
    }
}
