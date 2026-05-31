<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MandatoryTraining extends Model
{
    protected $fillable = [
        'training_id',
        'target_type',
        'target_id',
        'deadline_days',
        'notes',
    ];

    protected $casts = [
        'deadline_days' => 'integer',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    /**
     * Devolve o nome do âmbito (departamento, cargo ou "Todos").
     */
    public function getTargetNameAttribute(): string
    {
        return match ($this->target_type) {
            'department' => Department::find($this->target_id)?->department ?? '—',
            'position'   => Position::find($this->target_id)?->position    ?? '—',
            default      => 'Todos os funcionários',
        };
    }

    /**
     * Devolve os IDs de funcionários abrangidos por esta regra.
     */
    public function affectedEmployeeIds(): Collection
    {
        return match ($this->target_type) {
            'department' => Employee::where('status', 'active')->where('department_id', $this->target_id)->pluck('id'),
            'position'   => Employee::where('status', 'active')->where('position_id', $this->target_id)->pluck('id'),
            default      => Employee::where('status', 'active')->pluck('id'),
        };
    }

    /**
     * IDs de funcionários abrangidos que já cumpriram a formação.
     * Considera:
     *  1. Inscrição em employee_trainings com status enrolled/completed
     *  2. QuizAttempt aprovado (passou o quiz mesmo sem inscrição formal)
     */
    public function doneEmployeeIds(Collection $affectedIds): Collection
    {
        // Via inscrição formal
        $viaEnrollment = EmployeeTraining::whereIn('employee_id', $affectedIds)
            ->where('training_id', $this->training_id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->pluck('employee_id');

        // Via quiz aprovado — obtém user_ids dos aprovados, depois cruza com employee.user_id
        $training = $this->training ?? Training::find($this->training_id);
        $quizId   = $training?->quiz?->id;

        $viaQuiz = collect();
        if ($quizId) {
            $approvedUserIds = QuizAttempt::where('quiz_id', $quizId)
                ->where('passed', true)
                ->pluck('user_id')
                ->unique();

            $viaQuiz = Employee::whereIn('user_id', $approvedUserIds)
                ->whereIn('id', $affectedIds)
                ->pluck('id');
        }

        return $viaEnrollment->merge($viaQuiz)->unique()->values();
    }
}
