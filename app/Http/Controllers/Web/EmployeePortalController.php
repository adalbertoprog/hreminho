<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Sector;
use App\Models\Training;
use App\Models\QuizAttempt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeePortalController extends Controller
{
    // ── helpers ──────────────────────────────────────────────────────────

    /** Devolve o employee ligado ao utilizador actual (ou null). */
    private function currentEmployee(): ?Employee
    {
        $user = Auth::user();
        return Employee::with(['position', 'department', 'sector', 'trainings'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere(function ($q2) use ($user) {
                      $q2->whereNull('user_id')->where('email', $user->email);
                  });
            })
            ->first();
    }

    // ── Dashboard ─────────────────────────────────────────────────────────

    public function dashboard()
    {
        $user     = Auth::user();
        $employee = $this->currentEmployee();

        $trainings = Training::with('videos', 'quiz')
            ->where(function ($q) {
                $q->where('has_video', true)->orWhere('has_quiz', true);
            })
            ->get();

        $quizStatuses = [];
        foreach ($trainings as $training) {
            if ($training->quiz) {
                $best = QuizAttempt::where('quiz_id', $training->quiz->id)
                    ->where('user_id', $user->id)
                    ->orderByDesc('score')
                    ->first();
                $quizStatuses[$training->id] = $best;
            }
        }

        $recentAttendances = collect();
        $attendanceSummary = [];
        $leaves            = collect();

        if ($employee) {
            $recentAttendances = Attendance::where('employee_id', $employee->id)
                ->orderByDesc('date')->take(30)->get();

            $monthStart = Carbon::now()->startOfMonth();
            $monthEnd   = Carbon::now()->endOfMonth();
            $monthAtts  = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$monthStart, $monthEnd])->get();

            $attendanceSummary = [
                'present'  => $monthAtts->whereIn('status', ['present', 'late'])->count(),
                'absent'   => $monthAtts->where('status', 'absent')->count(),
                'late'     => $monthAtts->where('status', 'late')->count(),
                'on_leave' => $monthAtts->where('status', 'on_leave')->count(),
                'month'    => Carbon::now()->translatedFormat('F Y'),
            ];

            // Resumo rápido de licenças para o widget no dashboard
            $leaves = Leave::where('employee_id', $employee->id)
                ->orderByDesc('start_date')->take(5)->get();
        }

        return view('employee.dashboard', compact(
            'user', 'employee', 'trainings', 'quizStatuses',
            'recentAttendances', 'attendanceSummary', 'leaves'
        ));
    }

    // ── Formação individual ───────────────────────────────────────────────

    public function training(Training $training)
    {
        $training->load('videos', 'quiz.questions.options');
        $user = Auth::user();
        $attempts = $training->quiz
            ? QuizAttempt::where('quiz_id', $training->quiz->id)
                ->where('user_id', $user->id)->latest()->get()
            : collect();
        return view('employee.training', compact('training', 'attempts'));
    }

    // ── Licenças & Férias do funcionário ─────────────────────────────────

    public function leaves()
    {
        $user     = Auth::user();
        $employee = $this->currentEmployee();

        $leaves = $employee
            ? Leave::where('employee_id', $employee->id)->orderByDesc('start_date')->get()
            : collect();

        return view('employee.leaves', compact('user', 'employee', 'leaves'));
    }

    // ── Obras & Equipas do funcionário ───────────────────────────────────

    public function projects()
    {
        $employee = $this->currentEmployee();

        $teams = collect();
        if ($employee) {
            $teams = $employee->teams()
                ->with([
                    'project:id,name,reference,client,location,start_date,end_date,status,notes',
                    'leader:id,first_name,last_name,code',
                    'employees:id,first_name,last_name,code',
                    'vehicles:id,plate,brand,model,type',
                ])
                ->get();
        }

        return view('employee.projects', compact('employee', 'teams'));
    }

    /**
     * Eventos FullCalendar para o portal do funcionário (obras das suas equipas).
     */
    public function projectEvents(Request $request): JsonResponse
    {
        $employee = $this->currentEmployee();
        if (!$employee) return response()->json([]);

        $calStart = $request->input('start');
        $calEnd   = $request->input('end');

        $teams = $employee->teams()
            ->with(['project:id,name,reference,client,location,start_date,end_date,status'])
            ->get();

        $statusColors = [
            'active'    => ['bg' => '#059669', 'border' => '#047857'],
            'planned'   => ['bg' => '#6366f1', 'border' => '#4f46e5'],
            'completed' => ['bg' => '#6b7280', 'border' => '#4b5563'],
            'cancelled' => ['bg' => '#ef4444', 'border' => '#dc2626'],
        ];

        $events = collect();
        foreach ($teams as $team) {
            $project = $team->project;
            if (!$project) continue;

            $start = $project->start_date?->format('Y-m-d') ?? now()->format('Y-m-d');
            $end   = $project->end_date ? $project->end_date->copy()->addDay()->format('Y-m-d') : null;

            // Skip if outside calendar range
            if ($calEnd   && $start > $calEnd)   continue;
            if ($calStart && $end   && $end < $calStart) continue;

            $color = $statusColors[$project->status] ?? $statusColors['active'];

            $events->push([
                'id'              => 'proj-' . $project->id . '-team-' . $team->id,
                'title'           => '🏗️ ' . $project->name . ' (' . $team->name . ')',
                'start'           => $start,
                'end'             => $end,
                'backgroundColor' => $color['bg'],
                'borderColor'     => $color['border'],
                'textColor'       => '#ffffff',
                'extendedProps'   => [
                    'project_name' => $project->name,
                    'team_name'    => $team->name,
                    'reference'    => $project->reference,
                    'client'       => $project->client,
                    'location'     => $project->location,
                    'status'       => $project->status,
                    'start_date'   => $project->start_date?->format('d/m/Y'),
                    'end_date'     => $project->end_date?->format('d/m/Y'),
                    'my_role'      => $team->employees
                                        ->where('id', $employee->id)
                                        ->first()?->pivot?->role,
                ],
            ]);
        }

        return response()->json($events->values());
    }

    // ── Aprovação pelo manager ────────────────────────────────────────────

    public function managerLeaves()
    {
        $user     = Auth::user();
        $employee = Employee::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere(function ($q2) use ($user) {
                  $q2->whereNull('user_id')->where('email', $user->email);
              });
        })->first();

        // Se for admin/hr: vê todos os pendentes; se for manager: só do seu dept/sector
        $query = Leave::with('employee.department', 'employee.sector')
            ->whereHas('employee');

        if ($user->role === 'manager' && $employee) {
            $deptIds   = Department::where('manager_id', $employee->id)->pluck('id');
            $sectorIds = Sector::where('manager_id',    $employee->id)->pluck('id');
            $empIds    = Employee::where(function ($q) use ($deptIds, $sectorIds) {
                $q->whereIn('department_id', $deptIds)
                  ->orWhereIn('sector_id',   $sectorIds);
            })->pluck('id');
            $query->whereIn('employee_id', $empIds);
        }

        $pending  = (clone $query)->where('status', 'pending')->orderBy('start_date')->get();
        $recent   = (clone $query)->whereIn('status', ['approved', 'rejected'])
                        ->orderByDesc('updated_at')->take(20)->get();

        return view('employee.manager-leaves', compact('user', 'pending', 'recent'));
    }
}
