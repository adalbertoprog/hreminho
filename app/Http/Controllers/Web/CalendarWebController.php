<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Leave;
use App\Models\Training;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarWebController extends Controller
{
    public function index(): View
    {
        $trainings = Training::orderBy('title')->get(['id', 'title']);
        $employees = Employee::where('status', 'active')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'code']);

        return view('calendar.index', compact('trainings', 'employees'));
    }

    public function events(Request $request): JsonResponse
    {
        $calStart    = $request->input('start');
        $calEnd      = $request->input('end');
        $employeeId  = $request->input('employee_id');
        $types       = $request->input('types', 'trainings,leaves,attendances'); // comma-separated
        $typesArr    = array_map('trim', explode(',', $types));

        $events = collect();

        // ── 1. Formações ─────────────────────────────────────────────────
        if (in_array('trainings', $typesArr)) {
            $query = EmployeeTraining::with([
                'employee:id,first_name,last_name,code',
                'training:id,title,provider',
            ])->whereNotNull('start_date');

            if ($employeeId)            $query->where('employee_id', $employeeId);
            if ($request->filled('training_id')) $query->where('training_id', $request->training_id);
            if ($request->filled('status'))      $query->where('status', $request->status);

            if ($calEnd)   $query->where('start_date', '<=', $calEnd);
            if ($calStart) $query->where(fn($q) => $q->where('end_date', '>=', $calStart)->orWhereNull('end_date'));

            $statusColors = [
                'enrolled'  => ['bg' => '#6366f1', 'border' => '#4f46e5'],
                'completed' => ['bg' => '#16a34a', 'border' => '#15803d'],
                'failed'    => ['bg' => '#dc2626', 'border' => '#b91c1c'],
            ];

            $query->get()->each(function ($et) use (&$events, $statusColors) {
                $color    = $statusColors[$et->status] ?? ['bg' => '#6366f1', 'border' => '#4f46e5'];
                $empName  = $et->employee ? $et->employee->first_name . ' ' . $et->employee->last_name : 'Desconhecido';
                $title    = $et->training?->title ?? 'Formação';
                $endDate  = $et->end_date
                    ? $et->end_date->copy()->addDay()->format('Y-m-d')
                    : $et->start_date->copy()->addDay()->format('Y-m-d');

                $events->push([
                    'id'              => 'training-' . $et->id,
                    'title'           => '📚 ' . $title . ' — ' . $empName,
                    'start'           => $et->start_date->format('Y-m-d'),
                    'end'             => $endDate,
                    'backgroundColor' => $color['bg'],
                    'borderColor'     => $color['border'],
                    'textColor'       => '#ffffff',
                    'extendedProps'   => [
                        'type'            => 'training',
                        'enrollment_id'   => $et->id,
                        'employee'        => $empName,
                        'employeeCode'    => $et->employee?->code ?? '',
                        'employee_id'     => $et->employee_id,
                        'training'        => $title,
                        'training_id'     => $et->training_id,
                        'provider'        => $et->training?->provider ?? '',
                        'status'          => $et->status,
                        'score'           => $et->score,
                        'start_date'      => $et->start_date?->format('d/m/Y'),
                        'end_date'        => $et->end_date?->format('d/m/Y'),
                        'end_date_raw'    => $et->end_date?->format('Y-m-d'),
                        'validity_months' => $et->validity_months,
                        'expiry_date'     => $et->expiry_date?->format('d/m/Y'),
                        'validity_status' => $et->validity_status,
                        'notes'           => $et->notes,
                    ],
                ]);
            });
        }

        // ── 2. Licenças / Férias ─────────────────────────────────────────
        if (in_array('leaves', $typesArr)) {
            $query = Leave::with('employee:id,first_name,last_name,code')
                ->whereIn('status', ['approved', 'pending']);

            if ($employeeId) $query->where('employee_id', $employeeId);
            if ($calEnd)     $query->where('start_date', '<=', $calEnd);
            if ($calStart)   $query->where('end_date', '>=', $calStart);

            $leaveColors = [
                'vacation' => ['bg' => '#0891b2', 'border' => '#0e7490'], // cyan
                'sick'     => ['bg' => '#d97706', 'border' => '#b45309'], // âmbar
                'unpaid'   => ['bg' => '#7c3aed', 'border' => '#6d28d9'], // violeta
            ];
            $leaveLabel = [
                'vacation' => 'Férias',
                'sick'     => 'Doença',
                'unpaid'   => 'N. Rem.',
            ];
            $statusOpacity = ['pending' => 'opacity:.65', 'approved' => ''];

            $query->get()->each(function ($l) use (&$events, $leaveColors, $leaveLabel) {
                $color   = $leaveColors[$l->leave_type] ?? ['bg' => '#0891b2', 'border' => '#0e7490'];
                $empName = $l->employee ? $l->employee->first_name . ' ' . $l->employee->last_name : 'Desconhecido';
                $icon    = $l->leave_type === 'vacation' ? '🏖️' : ($l->leave_type === 'sick' ? '🤒' : '📋');
                $pending = $l->status === 'pending' ? ' (Pendente)' : '';
                $endDate = $l->end_date->copy()->addDay()->format('Y-m-d');

                $events->push([
                    'id'              => 'leave-' . $l->id,
                    'title'           => $icon . ' ' . ($leaveLabel[$l->leave_type] ?? $l->leave_type) . ' — ' . $empName . $pending,
                    'start'           => $l->start_date->format('Y-m-d'),
                    'end'             => $endDate,
                    'backgroundColor' => $color['bg'],
                    'borderColor'     => $color['border'],
                    'textColor'       => '#ffffff',
                    'opacity'         => $l->status === 'pending' ? 0.65 : 1,
                    'extendedProps'   => [
                        'type'        => 'leave',
                        'leave_id'    => $l->id,
                        'employee'    => $empName,
                        'employeeCode'=> $l->employee?->code ?? '',
                        'employee_id' => $l->employee_id,
                        'leave_type'  => $l->leave_type,
                        'leave_type_label' => $leaveLabel[$l->leave_type] ?? $l->leave_type,
                        'status'      => $l->status,
                        'start_date'  => $l->start_date->format('d/m/Y'),
                        'end_date'    => $l->end_date->format('d/m/Y'),
                        'reason'      => $l->reason,
                        'manager_comment' => $l->manager_comment,
                    ],
                ]);
            });
        }

        // ── 3. Presenças relevantes (ausências e atrasos) ────────────────
        if (in_array('attendances', $typesArr)) {
            $query = Attendance::with('employee:id,first_name,last_name,code')
                ->whereIn('status', ['absent', 'late']);

            if ($employeeId) $query->where('employee_id', $employeeId);
            if ($calStart)   $query->whereDate('date', '>=', $calStart);
            if ($calEnd)     $query->whereDate('date', '<=', $calEnd);

            $attColors = [
                'absent' => ['bg' => '#ef4444', 'border' => '#dc2626'],
                'late'   => ['bg' => '#f59e0b', 'border' => '#d97706'],
            ];
            $attLabel = ['absent' => 'Ausente', 'late' => 'Atrasado'];
            $attIcon  = ['absent' => '❌', 'late' => '⚠️'];

            $query->get()->each(function ($a) use (&$events, $attColors, $attLabel, $attIcon) {
                $color   = $attColors[$a->status] ?? ['bg' => '#ef4444', 'border' => '#dc2626'];
                $empName = $a->employee ? $a->employee->first_name . ' ' . $a->employee->last_name : 'Desconhecido';
                $icon    = $attIcon[$a->status] ?? '';
                $endDate = $a->date->copy()->addDay()->format('Y-m-d');

                $events->push([
                    'id'              => 'attendance-' . $a->id,
                    'title'           => $icon . ' ' . ($attLabel[$a->status] ?? $a->status) . ' — ' . $empName,
                    'start'           => $a->date->format('Y-m-d'),
                    'end'             => $endDate,
                    'backgroundColor' => $color['bg'],
                    'borderColor'     => $color['border'],
                    'textColor'       => '#ffffff',
                    'extendedProps'   => [
                        'type'         => 'attendance',
                        'attendance_id'=> $a->id,
                        'employee'     => $empName,
                        'employeeCode' => $a->employee?->code ?? '',
                        'employee_id'  => $a->employee_id,
                        'status'       => $a->status,
                        'status_label' => $attLabel[$a->status] ?? $a->status,
                        'date'         => $a->date->format('d/m/Y'),
                        'check_in'     => $a->check_in ? substr($a->check_in, 0, 5) : null,
                        'check_out'    => $a->check_out ? substr($a->check_out, 0, 5) : null,
                        'notes'        => $a->notes,
                    ],
                ]);
            });
        }

        return response()->json($events->values());
    }
}
