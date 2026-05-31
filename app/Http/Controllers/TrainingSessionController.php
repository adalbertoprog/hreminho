<?php

namespace App\Http\Controllers;

use App\Models\TrainingSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class TrainingSessionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('manage-hr');

        $query = TrainingSession::with('training:id,title,provider')->withCount([
            'enrollments as enrolled_count',
            'enrollments as completed_count' => fn($q) => $q->where('status', 'completed'),
        ]);

        if ($request->filled('year')) {
            $query->whereYear('planned_date', $request->year);
        }
        if ($request->filled('month')) {
            $query->whereMonth('planned_date', $request->month);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('training_id')) {
            $query->where('training_id', $request->training_id);
        }

        $sessions = $query->orderBy('planned_date')->get();

        return response()->json(['data' => $sessions->map(fn($s) => $this->format($s))]);
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('manage-hr');

        $data = $request->validate([
            'training_id'            => 'required|exists:trainings,id',
            'planned_date'           => 'required|date',
            'planned_end_date'       => 'nullable|date|after_or_equal:planned_date',
            'location'               => 'nullable|string|max:255',
            'max_participants'       => 'nullable|integer|min:1',
            'estimated_participants' => 'nullable|integer|min:1',
            'cost_per_person'        => 'nullable|numeric|min:0',
            'status'                 => 'nullable|in:planned,ongoing,completed,cancelled',
            'notes'                  => 'nullable|string|max:2000',
        ]);

        $session = TrainingSession::create($data);

        return response()->json(['data' => $this->format(
            $session->load('training')->loadCount([
                'enrollments as enrolled_count',
                'enrollments as completed_count' => fn($q) => $q->where('status', 'completed'),
            ])
        )], 201);
    }

    public function update(Request $request, TrainingSession $trainingSession): JsonResponse
    {
        Gate::authorize('manage-hr');

        $data = $request->validate([
            'training_id'            => 'sometimes|exists:trainings,id',
            'planned_date'           => 'sometimes|date',
            'planned_end_date'       => 'nullable|date|after_or_equal:planned_date',
            'location'               => 'nullable|string|max:255',
            'max_participants'       => 'nullable|integer|min:1',
            'estimated_participants' => 'nullable|integer|min:1',
            'cost_per_person'        => 'nullable|numeric|min:0',
            'status'                 => 'nullable|in:planned,ongoing,completed,cancelled',
            'notes'                  => 'nullable|string|max:2000',
        ]);

        $trainingSession->update($data);

        return response()->json(['data' => $this->format(
            $trainingSession->fresh()->load('training')->loadCount([
                'enrollments as enrolled_count',
                'enrollments as completed_count' => fn($q) => $q->where('status', 'completed'),
            ])
        )]);
    }

    public function destroy(TrainingSession $trainingSession): JsonResponse
    {
        Gate::authorize('manage-hr');
        $trainingSession->delete();
        return response()->json(null, 204);
    }

    /** Sumário anual — sessões por mês com contagens por estado */
    public function annualSummary(Request $request): JsonResponse
    {
        Gate::authorize('manage-hr');

        $year = $request->input('year', Carbon::now()->year);

        $sessions = TrainingSession::with('training:id,title,provider')
            ->withCount([
                'enrollments as enrolled_count',
                'enrollments as completed_count' => fn($q) => $q->where('status', 'completed'),
            ])
            ->whereYear('planned_date', $year)
            ->orderBy('planned_date')
            ->get();

        // Agrupar por mês
        $byMonth = collect(range(1, 12))->mapWithKeys(function ($m) use ($sessions) {
            $monthSessions = $sessions->filter(fn($s) => $s->planned_date->month === $m);
            return [$m => [
                'month'      => $m,
                'label'      => Carbon::createFromDate(2000, $m, 1)->locale('pt')->monthName,
                'total'      => $monthSessions->count(),
                'planned'    => $monthSessions->where('status', 'planned')->count(),
                'ongoing'    => $monthSessions->where('status', 'ongoing')->count(),
                'completed'  => $monthSessions->where('status', 'completed')->count(),
                'cancelled'  => $monthSessions->where('status', 'cancelled')->count(),
                'sessions'   => $monthSessions->map(fn($s) => $this->format($s))->values(),
            ]];
        });

        return response()->json([
            'year'    => (int) $year,
            'total'   => $sessions->count(),
            'by_month'=> $byMonth->values(),
            'by_status' => [
                'planned'   => $sessions->where('status', 'planned')->count(),
                'ongoing'   => $sessions->where('status', 'ongoing')->count(),
                'completed' => $sessions->where('status', 'completed')->count(),
                'cancelled' => $sessions->where('status', 'cancelled')->count(),
            ],
        ]);
    }

    private function format(TrainingSession $s): array
    {
        $costPerPerson = $s->cost_per_person !== null ? (float) $s->cost_per_person : null;
        $estimatedParticipants = $s->estimated_participants;
        $estimatedTotal = ($costPerPerson !== null && $estimatedParticipants !== null)
            ? round($costPerPerson * $estimatedParticipants, 2)
            : null;

        $enrolledCount   = (int) ($s->enrolled_count   ?? 0);
        $completedCount  = (int) ($s->completed_count  ?? 0);
        $targetCount     = $estimatedParticipants ?? $s->max_participants;
        $fillRate        = ($targetCount > 0) ? min(100, round(($enrolledCount / $targetCount) * 100)) : null;
        $realTotal       = ($costPerPerson !== null && $enrolledCount > 0)
            ? round($costPerPerson * $enrolledCount, 2)
            : null;

        return [
            'id'                     => $s->id,
            'training_id'            => $s->training_id,
            'training_title'         => $s->training?->title,
            'training_provider'      => $s->training?->provider,
            'planned_date'           => $s->planned_date?->format('Y-m-d'),
            'planned_date_fmt'       => $s->planned_date?->format('d/m/Y'),
            'planned_end_date'       => $s->planned_end_date?->format('Y-m-d'),
            'planned_end_fmt'        => $s->planned_end_date?->format('d/m/Y'),
            'duration_days'          => $s->duration_days,
            'location'               => $s->location,
            'max_participants'       => $s->max_participants,
            'estimated_participants' => $estimatedParticipants,
            'cost_per_person'        => $costPerPerson,
            'estimated_total'        => $estimatedTotal,
            'enrolled_count'         => $enrolledCount,
            'completed_count'        => $completedCount,
            'fill_rate'              => $fillRate,
            'real_total'             => $realTotal,
            'status'                 => $s->status,
            'notes'                  => $s->notes,
            'month'                  => $s->planned_date?->month,
            'year'                   => $s->planned_date?->year,
        ];
    }
}
