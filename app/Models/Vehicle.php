<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vehicle extends Model
{
    protected $fillable = [
        'plate', 'brand', 'model', 'year', 'type', 'status', 'notes',
    ];

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_vehicles')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }
}
