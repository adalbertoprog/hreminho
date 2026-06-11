<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;
    protected $fillable = ['project_id', 'name', 'leader_id', 'notes'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'leader_id');
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'team_employees')
                    ->withPivot('start_date', 'end_date', 'role')
                    ->withTimestamps();
    }

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'team_vehicles')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }

    /** Funcionários activos neste momento (sem end_date ou end_date >= hoje). */
    public function activeEmployees(): BelongsToMany
    {
        return $this->employees()
                    ->wherePivot(fn($q) => $q
                        ->whereNull('end_date')
                        ->orWhere('end_date', '>=', now()->toDateString())
                    );
    }
}
