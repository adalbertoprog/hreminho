<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Training;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
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
        $employee = Employee::with(['position', 'department', 'sector', 'trainings'])
                            ->where('email', $user->email)
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

        return view('employee.dashboard', compact('user', 'employee', 'trainings', 'quizStatuses'));
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
