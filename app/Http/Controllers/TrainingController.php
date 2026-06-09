<?php

namespace App\Http\Controllers;

use App\Models\EmployeeTraining;
use App\Models\Training;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrainingController extends Controller
{
    // ── Formações (catálogo) ──────────────────────────

    public function index(Request $request): JsonResponse
    {
        $query = Training::withCount(['employeeTrainings' => fn($q) => $q->whereHas('employee')]);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('title','like',"%{$s}%")->orWhere('provider','like',"%{$s}%"));
        }

        // Ordenação
        $sort = $request->get('sort', 'title_asc');
        match ($sort) {
            'title_desc'      => $query->orderBy('title', 'desc'),
            'inscricoes_asc'  => $query->orderBy('employee_trainings_count', 'asc'),
            'inscricoes_desc' => $query->orderBy('employee_trainings_count', 'desc'),
            default           => $query->orderBy('title', 'asc'),
        };

        if ($request->boolean('all')) {
            $rows = $query->get();
            return response()->json([
                'data' => $rows->map(fn($t) => $this->formatTraining($t)),
            ]);
        }

        $rows = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $rows->map(fn($t) => $this->formatTraining($t)),
            'meta' => [
                'current_page' => $rows->currentPage(),
                'last_page'    => $rows->lastPage(),
                'from'         => $rows->firstItem(),
                'to'           => $rows->lastItem(),
                'total'        => $rows->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'provider'    => 'required|string|max:200',
            'has_video'   => 'boolean',
            'has_quiz'    => 'boolean',
        ]);
        $training = Training::create($data);
        return response()->json(['data' => $this->formatTraining($training->loadCount('employeeTrainings'))], 201);
    }

    public function show(Training $training): JsonResponse
    {
        return response()->json(['data' => $this->formatTraining($training->loadCount('employeeTrainings'))]);
    }

    public function update(Request $request, Training $training): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'sometimes|string|max:200',
            'description' => 'nullable|string',
            'provider'    => 'sometimes|string|max:200',
            'has_video'   => 'boolean',
            'has_quiz'    => 'boolean',
        ]);
        $training->update($data);
        return response()->json(['data' => $this->formatTraining($training->fresh()->loadCount('employeeTrainings'))]);
    }

    public function destroy(Training $training): JsonResponse
    {
        $training->delete();
        return response()->json(null, 204);
    }

    // ── Inscrições (employee_trainings) ─────────────────

    public function enrollments(Request $request): JsonResponse
    {
        $query = EmployeeTraining::with(['employee', 'training', 'trainingSession'])
            ->whereHas('employee'); // exclui inscrições de funcionários apagados (soft delete)
        if ($request->filled('training_id')) $query->where('training_id', $request->training_id);
        if ($request->filled('employee_id')) $query->where('employee_id', $request->employee_id);
        if ($request->filled('status'))      $query->where('status', $request->status);

        // Filtro por estado de validade
        if ($request->filled('validity_status')) {
            $today = now()->toDateString();
            match ($request->validity_status) {
                'expired'  => $query->whereNotNull('validity_months')->whereNotNull('end_date')
                                    ->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) < ?", [$today]),
                'expiring' => $query->whereNotNull('validity_months')->whereNotNull('end_date')
                                    ->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) >= ?", [$today])
                                    ->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) <= DATE_ADD(?, INTERVAL 30 DAY)", [$today]),
                'valid'    => $query->whereNotNull('validity_months')->whereNotNull('end_date')
                                    ->whereRaw("DATE_ADD(end_date, INTERVAL validity_months MONTH) > DATE_ADD(?, INTERVAL 30 DAY)", [$today]),
                'none'     => $query->where(fn($q) => $q->whereNull('validity_months')->orWhereNull('end_date')),
                default    => null,
            };
        }

        $rows = $query->orderByDesc('created_at')->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $rows->map(fn($e) => $this->formatEnrollment($e)),
            'meta' => [
                'current_page' => $rows->currentPage(),
                'last_page'    => $rows->lastPage(),
                'from'         => $rows->firstItem(),
                'to'           => $rows->lastItem(),
                'total'        => $rows->total(),
            ],
        ]);
    }

    public function enroll(Request $request): JsonResponse
    {
        $data = $request->validate([
            'employee_id'          => 'required|exists:employees,id',
            'training_id'          => 'required|exists:trainings,id',
            'training_session_id'  => 'nullable|exists:training_sessions,id',
            'status'               => 'nullable|in:enrolled,completed,failed',
            'score'                => 'nullable|numeric|min:0|max:100',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date|after_or_equal:start_date',
            'validity_months'      => 'nullable|integer|min:1|max:120',
            'notes'                => 'nullable|string|max:1000',
        ]);

        // Não permitir score se a formação ainda não terminou
        if (isset($data['score']) && isset($data['end_date']) && now()->startOfDay()->lt($data['end_date'])) {
            return response()->json(['message' => 'Não é possível atribuir pontuação antes da data de fim da formação.'], 422);
        }
        if (isset($data['score']) && !isset($data['end_date'])) {
            return response()->json(['message' => 'É necessário definir a data de fim para registar pontuação.'], 422);
        }

        $enrollment = EmployeeTraining::create($data);
        return response()->json(['data' => $this->formatEnrollment($enrollment->load(['employee','training']))], 201);
    }

    public function updateEnrollment(Request $request, EmployeeTraining $enrollment): JsonResponse
    {
        $data = $request->validate([
            'employee_id'          => 'sometimes|exists:employees,id',
            'training_id'          => 'sometimes|exists:trainings,id',
            'training_session_id'  => 'nullable|exists:training_sessions,id',
            'status'               => 'sometimes|in:enrolled,completed,failed',
            'score'                => 'nullable|numeric|min:0|max:100',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date|after_or_equal:start_date',
            'validity_months'      => 'nullable|integer|min:1|max:120',
            'notes'                => 'nullable|string|max:1000',
            'certificate_path'     => 'nullable|string',
        ]);

        // Remover certificado do disco se foi explicitamente apagado
        if (array_key_exists('certificate_path', $data) && $data['certificate_path'] === null && $enrollment->certificate_path) {
            Storage::disk('public')->delete($enrollment->certificate_path);
        }

        // Não permitir score se a formação ainda não terminou
        $endDate = $data['end_date'] ?? $enrollment->end_date?->toDateString();
        if (isset($data['score']) && $data['score'] !== null) {
            if (!$endDate) {
                return response()->json(['message' => 'É necessário definir a data de fim para registar pontuação.'], 422);
            }
            if (now()->startOfDay()->lt($endDate)) {
                return response()->json(['message' => 'Não é possível atribuir pontuação antes da data de fim da formação.'], 422);
            }
        }

        $enrollment->update($data);
        return response()->json(['data' => $this->formatEnrollment($enrollment->fresh()->load(['employee','training']))]);
    }

    public function uploadCertificate(Request $request, EmployeeTraining $enrollment): JsonResponse
    {
        $request->validate([
            'certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5 MB
        ]);

        // Apagar certificado anterior se existir
        if ($enrollment->certificate_path) {
            Storage::disk('public')->delete($enrollment->certificate_path);
        }

        $path = $request->file('certificate')->store('certificates', 'public');
        $enrollment->update(['certificate_path' => $path]);

        return response()->json([
            'certificate_path' => $path,
            'certificate_url'  => asset('storage/' . $path),
        ]);
    }

    public function destroyEnrollment(EmployeeTraining $enrollment): JsonResponse
    {
        // Apagar certificado do disco se existir
        if ($enrollment->certificate_path) {
            Storage::disk('public')->delete($enrollment->certificate_path);
        }
        $enrollment->delete();
        return response()->json(null, 204);
    }

    private function formatTraining(Training $t): array
    {
        return [
            'id'                      => $t->id,
            'title'                   => $t->title,
            'description'             => $t->description,
            'provider'                => $t->provider,
            'has_video'               => (bool) $t->has_video,
            'has_quiz'                => (bool) $t->has_quiz,
            'employee_trainings_count' => $t->employee_trainings_count ?? 0,
            'created_at'              => $t->created_at?->toDateTimeString(),
        ];
    }

    private function formatEnrollment(EmployeeTraining $e): array
    {
        return [
            'id'                   => $e->id,
            'employee_id'          => $e->employee_id,
            'training_id'          => $e->training_id,
            'training_session_id'  => $e->training_session_id,
            'employee'             => $e->employee ? ['id' => $e->employee->id, 'full_name' => $e->employee->full_name] : null,
            'training'             => $e->training  ? ['id' => $e->training->id,  'title'    => $e->training->title]    : null,
            'training_session'     => $e->training_session_id ? [
                'id'           => $e->training_session_id,
                'planned_date' => $e->trainingSession?->planned_date?->format('d/m/Y'),
                'location'     => $e->trainingSession?->location,
            ] : null,
            'status'           => $e->status,
            'score'            => $e->score,
            'start_date'       => $e->start_date?->toDateString(),
            'end_date'         => $e->end_date?->toDateString(),
            'validity_months'  => $e->validity_months,
            'expiry_date'      => $e->expiry_date?->toDateString(),
            'validity_status'  => $e->validity_status,
            'notes'            => $e->notes,
            'certificate_path' => $e->certificate_path,
            'certificate_url'  => $e->certificate_path ? asset('storage/' . $e->certificate_path) : null,
            'created_at'       => $e->created_at?->toDateTimeString(),
        ];
    }
}
