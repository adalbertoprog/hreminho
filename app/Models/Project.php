<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name', 'reference', 'client', 'location',
        'start_date', 'end_date', 'status', 'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    /** Todos os funcionários afectos a qualquer equipa desta obra (distinct). */
    public function employees()
    {
        return Employee::whereHas('teams', fn($q) => $q->where('teams.project_id', $this->id));
    }
}
