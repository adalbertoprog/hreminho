<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EmployeeTraining;
use App\Models\Training;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

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

    /**
     * Retorna os eventos de formações no formato FullCalendar (JSON).
     * Filtros opcionais: training_id, employee_id, status
     */
    public function events(Request $request): JsonResponse
    {
        $query = EmployeeTraining::with(['employee:id,first_name,last_name,code', 'training:id,title,provider'])
            ->whereNotNull('start_date');

        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // Filtro de intervalo visível no calendário (FullCalendar envia start/end).
        // Um evento intersecta o intervalo se:
        //   start_date <= fim_do_calendário
        //   E (end_date >= início_do_calendário OU end_date é null — formações em curso)
        if ($request->filled('start') || $request->filled('end')) {
            $calStart = $request->input('start');
            $calEnd   = $request->input('end');

            if ($calEnd) {
                $query->where('start_date', '<=', $calEnd);
            }
            if ($calStart) {
                $query->where(function ($q) use ($calStart) {
                    $q->where('end_date', '>=', $calStart)
                      ->orWhereNull('end_date');
                });
            }
        }

        $enrollments = $query->get();

        $statusColors = [
            'enrolled'  => ['bg' => '#6366f1', 'border' => '#4f46e5'],
            'completed' => ['bg' => '#16a34a', 'border' => '#15803d'],
            'failed'    => ['bg' => '#dc2626', 'border' => '#b91c1c'],
        ];

        $events = $enrollments->map(function ($et) use ($statusColors) {
            $color = $statusColors[$et->status] ?? ['bg' => '#6366f1', 'border' => '#4f46e5'];

            $employeeName = $et->employee
                ? $et->employee->first_name . ' ' . $et->employee->last_name
                : 'Desconhecido';
            $trainingTitle = $et->training?->title ?? 'Formação';

            // FullCalendar: end é exclusivo, por isso adicionamos 1 dia
            $endDate = $et->end_date
                ? $et->end_date->copy()->addDay()->format('Y-m-d')
                : ($et->start_date ? $et->start_date->copy()->addDay()->format('Y-m-d') : null);

            return [
                'id'              => $et->id,
                'title'           => $trainingTitle . ' — ' . $employeeName,
                'start'           => $et->start_date->format('Y-m-d'),
                'end'             => $endDate,
                'backgroundColor' => $color['bg'],
                'borderColor'     => $color['border'],
                'textColor'       => '#ffffff',
                'extendedProps'   => [
                    'employee'       => $employeeName,
                    'employeeCode'   => $et->employee?->code ?? '',
                    'training'       => $trainingTitle,
                    'provider'       => $et->training?->provider ?? '',
                    'status'         => $et->status,
                    'score'          => $et->score,
                    'start_date'     => $et->start_date?->format('d/m/Y'),
                    'end_date'       => $et->end_date?->format('d/m/Y'),
                    'validity_months'=> $et->validity_months,
                    'expiry_date'    => $et->expiry_date?->format('d/m/Y'),
                    'validity_status'=> $et->validity_status,
                    'notes'          => $et->notes,
                ],
            ];
        });

        return response()->json($events->values());
    }
}
