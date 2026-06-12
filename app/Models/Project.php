<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'reference', 'client', 'location',
        'start_date', 'end_date', 'status', 'notes',
        'docsem_obra_id', 'docsem_synced_at',
    ];

    protected $casts = [
        'start_date'       => 'date',
        'end_date'         => 'date',
        'docsem_synced_at' => 'datetime',
    ];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function companies(): HasMany
    {
        return $this->hasMany(ProjectCompany::class);
    }

    /** Todos os funcionarios afectos a qualquer equipa desta obra (distinct). */
    public function employees()
    {
        return Employee::whereHas('teams', fn($q) => $q->where('teams.project_id', $this->id));
    }
}
