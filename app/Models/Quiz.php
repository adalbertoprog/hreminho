<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['training_id', 'title', 'description', 'passing_score'];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
