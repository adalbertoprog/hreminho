<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Training;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeePortalController extends Controller
{
    /**
     * Dashboard / profile of the logged-in employee.
     * Matches the User account by e-mail to the Employee record.
     */
    public function dashboard()
    {
        $user     = Auth::user();
        // Prefer explicit user_id link; fall back to email match for legacy records
        $employee = Employee::with(['position', 'department', 'sector', 'trainings'])
                            ->where(function ($q) use ($user) {
                                $q->where('user_id', $user->id)
                                  ->orWhere(function ($q2) use ($user) {
                                      $q2->whereNull('user_id')
                                         ->where('email', $user->email);
                                  });
                            })
                            ->first();

        $trainings = Training::with('videos', 'quiz')
                            ->where(function ($q) {
                                $q->where('has_video', true)
                                  ->orWhere('has_quiz', true);
                            })
                            ->get();

        // Enrich each training with the user's best attempt (if any)
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

        // Presenças e licenças (só se o employee estiver associado)
        $recentAttendances = collect();
        $leaves            = collect();
        $attendanceSummary = [];

        if ($employee) {
            $recentAttendances = Attendance::where('employee_id', $employee->id)
                ->orderByDesc('date')
                ->take(30)
                ->get();

            $leaves = Leave::where('employee_id', $employee->id)
                ->orderByDesc('start_date')
                ->take(10)
                ->get();

            // Resumo do mês actual
            $monthStart = Carbon::now()->startOfMonth();
            $monthEnd   = Carbon::now()->endOfMonth();
            $monthAtts  = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->get();

            $attendanceSummary = [
                'present'  => $monthAtts->whereIn('status', ['present', 'late'])->count(),
                'absent'   => $monthAtts->where('status', 'absent')->count(),
                'late'     => $monthAtts->where('status', 'late')->count(),
                'on_leave' => $monthAtts->where('status', 'on_leave')->count(),
                'month'    => Carbon::now()->translatedFormat('F Y'),
            ];
        }

        return view('employee.dashboard', compact(
            'user', 'employee', 'trainings', 'quizStatuses',
            'recentAttendances', 'leaves', 'attendanceSummary'
        ));
    }

    /**
     * Single training page: video player + quiz.
     */
    public function training(Training $training)
    {
        $training->load('videos', 'quiz.questions.options');

        $user = Auth::user();

        $attempts = [];
        if ($training->quiz) {
            $attempts = QuizAttempt::where('quiz_id', $training->quiz->id)
                                   ->where('user_id', $user->id)
                                   ->latest()
                                   ->get();
        }

        return view('employee.training', compact('training', 'attempts'));
    }
}
