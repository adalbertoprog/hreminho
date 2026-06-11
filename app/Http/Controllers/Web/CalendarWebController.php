<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Leave;
use App\Models\Project;
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
        $projects = Project::orderBy('name')->get(['id', 'name', 'reference']);

        return view('calendar.index', compact('trainings', 'employees', 'projects'));
    }

    public function events(Request $request): JsonResponse
    {
        $calStart    = $request->input('start');
        $calEnd      = $request->input('end');
        $employeeId  = $request->input('employee_id');
        $types       = $request->input('types', 'trainings,leaves,attendances,projects'); // comma-separated
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

        // ── 4. Obras / Equipas ───────────────────────────────────────────
        if (in_array('projects', $typesArr)) {
            $query = Project::with([
                'teams.leader:id,first_name,last_name,code',
                'teams.employees:id,first_name,last_name,code',
                'teams.vehicles:id,plate,brand,model',
            ])->whereIn('status', ['active', 'planned', 'completed']);

            if ($request->filled('project_id')) {
                $query->where('id', $request->project_id);
            }

            if ($calEnd)   $query->where(fn($q) => $q->whereNull('start_date')->orWhere('start_date', '<=', $calEnd));
            if ($calStart) $query->where(fn($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', $calStart));

            $projectColors = [
                'active'    => ['bg' => '#059669', 'border' => '#047857'],
                'planned'   => ['bg' => '#6366f1', 'border' => '#4f46e5'],
                'completed' => ['bg' => '#6b7280', 'border' => '#4b5563'],
            ];

            $query->get()->each(function ($p) use (&$events, $projectColors, $calStart, $calEnd) {
                $color     = $projectColors[$p->status] ?? $projectColors['active'];
                $startDate = $p->start_date?->format('Y-m-d') ?? now()->format('Y-m-d');
                $endDate   = $p->end_date
                    ? $p->end_date->copy()->addDay()->format('Y-m-d')
                    : null;

                // Evento da Obra (multi-dia)
                $events->push([
                    'id'              => 'project-' . $p->id,
                    'title'           => '🏗️ ' . $p->name,
                    'start'           => $startDate,
                    'end'             => $endDate,
                    'backgroundColor' => $color['bg'],
                    'borderColor'     => $color['border'],
                    'textColor'       => '#ffffff',
                    'extendedProps'   => [
                        'type'       => 'project',
                        'project_id' => $p->id,
                        'name'       => $p->name,
                        'reference'  => $p->reference,
                        'client'     => $p->client,
                        'location'   => $p->location,
                        'status'     => $p->status,
                        'start_date' => $p->start_date?->format('d/m/Y'),
                        'end_date'   => $p->end_date?->format('d/m/Y'),
                        'teams'      => $p->teams->map(fn($t) => [
                            'id'        => $t->id,
                            'name'      => $t->name,
                            'leader'    => $t->leader?->full_name,
                            'employees' => $t->employees->map(fn($e) => [
                                'name' => $e->full_name,
                                'code' => $e->code,
                                'role' => $e->pivot?->role,
                            ]),
                            'vehicles'  => $t->vehicles->map(fn($v) => [
                                'plate' => $v->plate,
                                'label' => trim("{$v->brand} {$v->model}") ?: $v->plate,
                            ]),
                        ]),
                    ],
                ]);

                // Eventos por equipa (mais compactos, mesmos dias da obra)
                foreach ($p->teams as $team) {
                    $events->push([
                        'id'              => 'team-' . $team->id,
                        'title'           => '👷 ' . $team->name . ($team->leader ? ' (' . $team->leader->first_name . ')' : ''),
                        'start'           => $startDate,
                        'end'             => $endDate,
                        'backgroundColor' => $color['bg'] . 'bb',
                        'borderColor'     => $color['border'],
                        'textColor'       => '#ffffff',
                        'extendedProps'   => [
                            'type'       => 'team',
                            'project_id' => $p->id,
                            'team_id'    => $team->id,
                            'team_name'  => $team->name,
                            'project'    => $p->name,
                            'leader'     => $team->leader?->full_name,
                            'employees'  => $team->employees->map(fn($e) => [
                                'name' => $e->full_name,
                                'code' => $e->code,
                                'role' => $e->pivot?->role,
                            ]),
                            'vehicles'   => $team->vehicles->map(fn($v) => [
                                'plate' => $v->plate,
                                'label' => trim("{$v->brand} {$v->model}") ?: $v->plate,
                            ]),
                        ],
                    ]);
                }
            });
        }

        return response()->json($events->values());
    }
}
