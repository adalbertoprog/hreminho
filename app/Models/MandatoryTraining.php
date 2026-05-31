<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    public function scopeAffectedEmployeeIds(): \Illuminate\Support\Collection
    {
        return match ($this->target_type) {
            'department' => Employee::where('status', 'active')->where('department_id', $this->target_id)->pluck('id'),
            'position'   => Employee::where('status', 'active')->where('position_id', $this->target_id)->pluck('id'),
            default      => Employee::where('status', 'active')->pluck('id'),
        };
    }
}
