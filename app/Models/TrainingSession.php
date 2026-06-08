<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Models\EmployeeTraining;

class TrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id',
        'planned_date',
        'planned_end_date',
        'location',
        'max_participants',
        'estimated_participants',
        'cost_per_person',
        'status',
        'notes',
    ];

    protected $casts = [
        'planned_date'           => 'date',
        'planned_end_date'       => 'date',
        'max_participants'       => 'integer',
        'estimated_participants' => 'integer',
        'cost_per_person'        => 'decimal:2',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function enrollments()
    {
        return $this->hasMany(EmployeeTraining::class);
    }

    /** Duração em dias */
    public function getDurationDaysAttribute(): int
    {
        if (!$this->planned_end_date) return 1;
        return $this->planned_date->diffInDays($this->planned_end_date) + 1;
    }

    /** Estado calculado automaticamente se não for cancelled */
    public function getComputedStatusAttribute(): string
    {
        if ($this->status === 'cancelled') return 'cancelled';
        $today = Carbon::today();
        if ($this->planned_date->gt($today)) return 'planned';
        if ($this->planned_end_date && $this->planned_end_date->lt($today)) return 'completed';
        return 'ongoing';
    }
}
