<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Training;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class StaffingCheckController extends Controller
{
    /**
     * POST /api/v1/staffing-check
     *
     * Body:
     *   start_date  : string  (YYYY-MM-DD) — início da empreitada
     *   end_date    : string  (YYYY-MM-DD) — fim da empreitada
     *   requirements: array de { training_id: int, quantity: int }
     */
    public function check(Request $request): JsonResponse
    {
        Gate::authorize('manage-hr');

        $data = $request->validate([
            'start_date'              => 'required|date',
            'end_date'                => 'required|date|after_or_equal:start_date',
            'requirements'            => 'required|array|min:1',
            'requirements.*.training_id' => 'required|integer|exists:trainings,id',
            'requirements.*.quantity'    => 'required|integer|min:1',
        ]);

        $start = Carbon::parse($data['start_date']);
        $end   = Carbon::parse($data['end_date']);

        // Todos os funcionários activos
        $activeEmployeeIds = Employee::where('status', 'active')->pluck('id');

        $results = [];

        foreach ($data['requirements'] as $req) {
            $trainingId = $req['training_id'];
            $needed     = $req['quantity'];
            $training   = Training::find($trainingId);

            // Todas as inscrições concluídas/activas para esta formação, de funcionários activos
            $enrollments = EmployeeTraining::with('employee')
                ->whereIn('employee_id', $activeEmployeeIds)
                ->where('training_id', $trainingId)
                ->whereIn('status', ['enrolled', 'completed'])
                ->get();

            $qualified       = [];  // válidos durante toda a empreitada
            $expiringDuring  = [];  // válidos no início mas expiram antes do fim
            $expiredBefore   = [];  // já expirados antes do início
            $noExpiry        = [];  // sem data de validade (considerar válidos indefinidamente)

            foreach ($enrollments as $en) {
                $expiry = $en->expiry_date;

                if ($expiry === null) {
                    // Sem validade definida → válido
                    $noExpiry[] = [
                        'id'         => $en->employee->id,
                        'name'       => $en->employee->full_name,
                        'code'       => $en->employee->code,
                        'end_date'   => $en->end_date?->format('Y-m-d'),
                        'expiry'     => null,
                        'department' => $en->employee->department?->department,
                    ];
                } elseif ($expiry->lt($start)) {
                    // Expira antes do início → não conta
                    $expiredBefore[] = [
                        'id'         => $en->employee->id,
                        'name'       => $en->employee->full_name,
                        'code'       => $en->employee->code,
                        'end_date'   => $en->end_date?->format('Y-m-d'),
                        'expiry'     => $expiry->format('Y-m-d'),
                        'department' => $en->employee->department?->department,
                    ];
                } elseif ($expiry->gte($start) && $expiry->lt($end)) {
                    // Válido no início mas expira durante a obra
                    $expiringDuring[] = [
                        'id'         => $en->employee->id,
                        'name'       => $en->employee->full_name,
                        'code'       => $en->employee->code,
                        'end_date'   => $en->end_date?->format('Y-m-d'),
                        'expiry'     => $expiry->format('Y-m-d'),
                        'expires_in_days' => (int) $start->diffInDays($expiry),
                        'department' => $en->employee->department?->department,
                    ];
                } else {
                    // Válido durante toda a obra
                    $qualified[] = [
                        'id'         => $en->employee->id,
                        'name'       => $en->employee->full_name,
                        'code'       => $en->employee->code,
                        'end_date'   => $en->end_date?->format('Y-m-d'),
                        'expiry'     => $expiry->format('Y-m-d'),
                        'department' => $en->employee->department?->department,
                    ];
                }
            }

            // Válidos = fully qualified + sem validade + expiring during (válidos no início)
            $available = count($qualified) + count($noExpiry) + count($expiringDuring);
            $gap       = max(0, $needed - $available);

            // Status geral
            if ($gap === 0 && count($expiringDuring) === 0) {
                $status = 'ok';
            } elseif ($gap === 0 && count($expiringDuring) > 0) {
                $status = 'warning';
            } else {
                $status = 'gap';
            }

            // Prazo para formar (dias úteis até ao início da obra)
            $daysUntilStart = (int) Carbon::today()->diffInDays($start, false);

            $results[] = [
                'training_id'     => $trainingId,
                'training_title'  => $training->title,
                'needed'          => $needed,
                'available'       => $available,
                'gap'             => $gap,
                'status'          => $status,
                'days_until_start'=> $daysUntilStart,
                'qualified'       => $qualified,
                'no_expiry'       => $noExpiry,
                'expiring_during' => $expiringDuring,
                'expired_before'  => $expiredBefore,
            ];
        }

        // Resumo global
        $totalGap      = array_sum(array_column($results, 'gap'));
        $hasWarnings   = collect($results)->contains('status', 'warning');
        $globalStatus  = $totalGap > 0 ? 'gap' : ($hasWarnings ? 'warning' : 'ok');

        return response()->json([
            'start_date'    => $start->format('Y-m-d'),
            'end_date'      => $end->format('Y-m-d'),
            'duration_days' => (int) $start->diffInDays($end) + 1,
            'global_status' => $globalStatus,
            'total_gap'     => $totalGap,
            'results'       => $results,
        ]);
    }
}
