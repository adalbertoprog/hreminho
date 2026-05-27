<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Models\Training;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    // ── Read quiz (with questions + options, correct answers hidden for employees) ──

    public function show(Training $training): JsonResponse
    {
        $quiz = $training->quiz()->with('questions.options')->first();

        if (!$quiz) {
            return response()->json(['message' => 'Esta formação ainda não tem questionário.'], 404);
        }

        $user      = Auth::user();
        $isManager = in_array($user->role, ['admin', 'hr']);

        // Hide correct-answer flag from employees
        $data = $quiz->toArray();
        foreach ($data['questions'] as &$q) {
            foreach ($q['options'] as &$opt) {
                if (!$isManager) {
                    unset($opt['is_correct']);
                }
            }
        }

        return response()->json(['data' => $data]);
    }

    // ── Create quiz (admin/hr only) ──

    public function store(Request $request, Training $training): JsonResponse
    {
        $this->authorizeManager();

        if ($training->quiz) {
            return response()->json(['message' => 'Esta formação já tem um questionário. Use PUT para actualizar.'], 409);
        }

        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'passing_score' => 'sometimes|integer|min:0|max:100',
            'questions'     => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type'     => 'required|in:mc,tf',
            'questions.*.order'    => 'sometimes|integer',
            'questions.*.options'  => 'required|array|min:2',
            'questions.*.options.*.text'       => 'required|string',
            'questions.*.options.*.is_correct' => 'required|boolean',
            'questions.*.options.*.order'      => 'sometimes|integer',
        ]);

        $quiz = DB::transaction(function () use ($training, $data) {
            $quiz = $training->quiz()->create([
                'title'         => $data['title'],
                'description'   => $data['description'] ?? null,
                'passing_score' => $data['passing_score'] ?? 70,
            ]);

            foreach ($data['questions'] as $qi => $qData) {
                $question = $quiz->questions()->create([
                    'question' => $qData['question'],
                    'type'     => $qData['type'],
                    'order'    => $qData['order'] ?? ($qi + 1),
                ]);
                foreach ($qData['options'] as $oi => $oData) {
                    $question->options()->create([
                        'text'       => $oData['text'],
                        'is_correct' => $oData['is_correct'],
                        'order'      => $oData['order'] ?? ($oi + 1),
                    ]);
                }
            }

            return $quiz->load('questions.options');
        });

        return response()->json(['data' => $quiz], 201);
    }

    // ── Update quiz (admin/hr only) ──

    public function update(Request $request, Training $training): JsonResponse
    {
        $this->authorizeManager();

        $quiz = $training->quiz;
        if (!$quiz) {
            return response()->json(['message' => 'Questionário não encontrado.'], 404);
        }

        $data = $request->validate([
            'title'         => 'sometimes|string|max:255',
            'description'   => 'nullable|string',
            'passing_score' => 'sometimes|integer|min:0|max:100',
            'questions'     => 'sometimes|array|min:1',
            'questions.*.id'       => 'sometimes|integer|exists:quiz_questions,id',
            'questions.*.question' => 'required_with:questions|string',
            'questions.*.type'     => 'required_with:questions|in:mc,tf',
            'questions.*.order'    => 'sometimes|integer',
            'questions.*.options'  => 'required_with:questions|array|min:2',
            'questions.*.options.*.id'         => 'sometimes|integer|exists:quiz_options,id',
            'questions.*.options.*.text'       => 'required|string',
            'questions.*.options.*.is_correct' => 'required|boolean',
            'questions.*.options.*.order'      => 'sometimes|integer',
        ]);

        DB::transaction(function () use ($quiz, $data) {
            $quiz->update(array_filter([
                'title'         => $data['title'] ?? null,
                'description'   => $data['description'] ?? null,
                'passing_score' => $data['passing_score'] ?? null,
            ], fn($v) => !is_null($v)));

            if (!empty($data['questions'])) {
                // Full replace: delete existing questions and re-create
                $quiz->questions()->delete();
                foreach ($data['questions'] as $qi => $qData) {
                    $question = $quiz->questions()->create([
                        'question' => $qData['question'],
                        'type'     => $qData['type'],
                        'order'    => $qData['order'] ?? ($qi + 1),
                    ]);
                    foreach ($qData['options'] as $oi => $oData) {
                        $question->options()->create([
                            'text'       => $oData['text'],
                            'is_correct' => $oData['is_correct'],
                            'order'      => $oData['order'] ?? ($oi + 1),
                        ]);
                    }
                }
            }
        });

        return response()->json(['data' => $quiz->fresh()->load('questions.options')]);
    }

    // ── Submit an attempt (employee) ──

    public function attempt(Request $request, Training $training): JsonResponse
    {
        $quiz = $training->quiz()->with('questions.options')->first();

        if (!$quiz) {
            return response()->json(['message' => 'Esta formação não tem questionário.'], 404);
        }

        $request->validate([
            'answers'              => 'required|array',
            'answers.*.question_id' => 'required|integer|exists:quiz_questions,id',
            'answers.*.option_id'   => 'required|integer|exists:quiz_options,id',
        ]);

        $result = DB::transaction(function () use ($quiz, $request) {
            $attempt = QuizAttempt::create([
                'quiz_id'      => $quiz->id,
                'user_id'      => Auth::id(),
                'completed_at' => now(),
            ]);

            $total   = 0;
            $correct = 0;

            foreach ($request->answers as $ans) {
                $option = QuizOption::find($ans['option_id']);

                // Verify the option belongs to the question
                if (!$option || $option->question_id != $ans['question_id']) {
                    continue;
                }

                QuizAnswer::create([
                    'attempt_id'  => $attempt->id,
                    'question_id' => $ans['question_id'],
                    'option_id'   => $ans['option_id'],
                ]);

                $total++;
                if ($option->is_correct) {
                    $correct++;
                }
            }

            $score  = $total > 0 ? round(($correct / $total) * 100) : 0;
            $passed = $score >= $quiz->passing_score;

            $attempt->update([
                'score'  => $score,
                'passed' => $passed,
            ]);

            return [
                'attempt_id'    => $attempt->id,
                'score'         => $score,
                'passed'        => $passed,
                'passing_score' => $quiz->passing_score,
                'correct'       => $correct,
                'total'         => $total,
            ];
        });

        return response()->json(['data' => $result]);
    }

    // ── Results for a training (admin/hr) — best attempt per user ──

    public function results(Training $training): JsonResponse
    {
        $this->authorizeManager();

        $quiz = $training->quiz;
        if (!$quiz) {
            return response()->json(['data' => [], 'summary' => ['total' => 0, 'passed' => 0, 'avg_score' => null]]);
        }

        // All attempts for this quiz — no ordering needed, we process manually
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->with('user.employee')
            ->get();

        // Group by user_id: best score + most recent date + attempt count
        $byUser = [];
        foreach ($attempts as $att) {
            $uid     = $att->user_id;
            $attDate = $att->completed_at ?? $att->created_at;

            if (!isset($byUser[$uid])) {
                $byUser[$uid] = [
                    'user_id'      => $uid,
                    'name'         => $att->user?->name ?? '—',
                    'code'         => $att->user?->employee?->code ?? '—',
                    'best_score'   => $att->score,
                    'passed'       => (bool) $att->passed,
                    'last_attempt' => $attDate,
                    'attempts'     => 1,
                ];
            } else {
                $byUser[$uid]['attempts']++;
                // Keep the highest score (and its passed status)
                if ($att->score > $byUser[$uid]['best_score']) {
                    $byUser[$uid]['best_score'] = $att->score;
                    $byUser[$uid]['passed']     = (bool) $att->passed;
                }
                // Always track the most recent date
                if ($attDate > $byUser[$uid]['last_attempt']) {
                    $byUser[$uid]['last_attempt'] = $attDate;
                }
            }
        }

        $rows        = array_values($byUser);
        $totalUsers  = count($rows);
        $passedUsers = count(array_filter($rows, fn($r) => $r['passed']));
        $avgScore    = $totalUsers > 0
            ? round(array_sum(array_column($rows, 'best_score')) / $totalUsers, 1)
            : null;

        // Sort: passed first, then by score desc
        usort($rows, fn($a, $b) => $b['passed'] <=> $a['passed'] ?: $b['best_score'] <=> $a['best_score']);

        return response()->json([
            'data' => $rows,
            'summary' => [
                'total'        => $totalUsers,
                'passed'       => $passedUsers,
                'avg_score'    => $avgScore,
                'passing_score' => $quiz->passing_score,
                'quiz_title'   => $quiz->title,
            ],
        ]);
    }

    // ── My attempts history ──

    public function myAttempts(Training $training): JsonResponse
    {
        $quiz = $training->quiz;
        if (!$quiz) {
            return response()->json(['data' => []]);
        }

        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
                               ->where('user_id', Auth::id())
                               ->latest()
                               ->get(['id', 'score', 'passed', 'completed_at', 'created_at']);

        return response()->json(['data' => $attempts]);
    }

    // ── Helper ──

    private function authorizeManager(): void
    {
        $role = Auth::user()->role;
        if (!in_array($role, ['admin', 'hr'])) {
            abort(403, 'Acesso não autorizado.');
        }
    }
}
