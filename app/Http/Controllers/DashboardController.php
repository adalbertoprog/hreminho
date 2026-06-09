<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Leave;
use App\Models\Sector;
use App\Models\Training;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect employees to their own portal
        if (Auth::user()->role === 'employee') {
            return redirect()->route('employee.dashboard');
        }

        $today = Carbon::today();

        // Stats cards
        $stats = [
            'total_employees'   => Employee::where('status', 'active')->count(),
            'present_today'     => Attendance::whereHas('employee')->whereDate('date', $today)->whereIn('status', ['present', 'late'])->count(),
            'pending_leaves'    => Leave::whereHas('employee')->where('status', 'pending')->count(),
            'active_trainings'  => EmployeeTraining::whereHas('employee')->where('status', 'enrolled')->count(),
            'total_departments' => Department::count(),
            'on_leave_today'    => Leave::whereHas('employee')
                                    ->where('status', 'approved')
                                    ->whereDate('start_date', '<=', $today)
                                    ->whereDate('end_date', '>=', $today)
                                    ->count(),
        ];

        // Listas
        $recent_employees = Employee::with(['position', 'department'])
            ->latest()->take(5)->get();

        $pending_leaves = Leave::with('employee')
            ->whereHas('employee')
            ->where('status', 'pending')
            ->latest()->take(5)->get();

        $active_trainings = EmployeeTraining::with(['employee', 'training'])
            ->whereHas('employee')
            ->where('status', 'enrolled')
            ->latest()->take(5)->get();

        // Grafico 1: Funcionarios por departamento (Donut)
        $employees_by_dept = Department::withCount('employees')
            ->orderByDesc('employees_count')
            ->get(['id', 'department', 'employees_count'])
            ->filter(fn($d) => $d->employees_count > 0)
            ->values();

        $chart_dept = [
            'labels' => $employees_by_dept->pluck('department')->toArray(),
            'data'   => $employees_by_dept->pluck('employees_count')->toArray(),
        ];

        // Grafico 2: Funcionarios por setor (Barras horizontais)
        $employees_by_sector = Sector::withCount('employees')
            ->orderByDesc('employees_count')
            ->get(['id', 'sector', 'employees_count'])
            ->filter(fn($s) => $s->employees_count > 0)
            ->values();

        $chart_sector = [
            'labels' => $employees_by_sector->pluck('sector')->toArray(),
            'data'   => $employees_by_sector->pluck('employees_count')->toArray(),
        ];

        // Grafico 3: Funcionarios por formacao (Top 10)
        $top_trainings_emp = Training::withCount(['employeeTrainings' => fn($q) => $q->whereHas('employee')])
            ->orderByDesc('employee_trainings_count')
            ->get(['id', 'title', 'employee_trainings_count'])
            ->filter(fn($t) => $t->employee_trainings_count > 0)
            ->take(10)
            ->values();

        $chart_training_employees = [
            'labels' => $top_trainings_emp->map(fn($t) =>
                mb_strlen($t->title) > 30 ? mb_substr($t->title, 0, 30) . '...' : $t->title
            )->toArray(),
            'data'   => $top_trainings_emp->pluck('employee_trainings_count')->toArray(),
        ];

        // Grafico 4: Taxa de conclusao por mes (ultimos 6 meses)
        $months = collect(range(5, 0))->map(fn ($i) => Carbon::now()->subMonths($i));

        $completion_by_month = $months->map(function ($month) {
            $total     = EmployeeTraining::whereHas('employee')
                            ->whereYear('created_at', $month->year)
                            ->whereMonth('created_at', $month->month)->count();
            $completed = EmployeeTraining::whereHas('employee')
                            ->whereYear('created_at', $month->year)
                            ->whereMonth('created_at', $month->month)
                            ->where('status', 'completed')->count();
            return [
                'month'     => $month->format('M/y'),
                'total'     => $total,
                'completed' => $completed,
            ];
        });

        $chart_completion = [
            'labels'    => $completion_by_month->pluck('month')->toArray(),
            'enrolled'  => $completion_by_month->pluck('total')->toArray(),
            'completed' => $completion_by_month->pluck('completed')->toArray(),
        ];

        // Grafico 5: Top 6 formacoes - barras de progresso
        $top_trainings = Training::withCount(['employeeTrainings' => fn($q) => $q->whereHas('employee')])
            ->orderByDesc('employee_trainings_count')
            ->get(['id', 'title', 'employee_trainings_count'])
            ->filter(fn($t) => $t->employee_trainings_count > 0)
            ->take(6)
            ->values();

        $top_trainings_chart = $top_trainings->map(function ($training) {
            $completed = EmployeeTraining::whereHas('employee')
                            ->where('training_id', $training->id)
                            ->where('status', 'completed')->count();
            $rate = $training->employee_trainings_count > 0
                ? round(($completed / $training->employee_trainings_count) * 100)
                : 0;
            return [
                'title'     => mb_strlen($training->title) > 28 ? mb_substr($training->title, 0, 28) . '...' : $training->title,
                'total'     => $training->employee_trainings_count,
                'completed' => $completed,
                'rate'      => $rate,
            ];
        });

        $chart_top_trainings = [
            'labels'    => $top_trainings_chart->pluck('title')->toArray(),
            'totals'    => $top_trainings_chart->pluck('total')->toArray(),
            'completed' => $top_trainings_chart->pluck('completed')->toArray(),
            'rates'     => $top_trainings_chart->pluck('rate')->toArray(),
            'rows'      => $top_trainings_chart->values()->toArray(),
        ];

        return view('dashboard.index', compact(
            'stats',
            'recent_employees',
            'pending_leaves',
            'active_trainings',
            'chart_dept',
            'chart_sector',
            'chart_training_employees',
            'chart_completion',
            'chart_top_trainings',
        ));
    }
}
