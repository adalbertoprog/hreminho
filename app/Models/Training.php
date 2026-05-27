<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'provider',
        'has_video',
        'has_quiz',
    ];

    protected $casts = [
        'has_video' => 'boolean',
        'has_quiz'  => 'boolean',
    ];

    public function employeeTrainings()
    {
        return $this->hasMany(EmployeeTraining::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_trainings')
                    ->withPivot('status', 'certificate_path', 'score', 'start_date', 'end_date', 'notes')
                    ->withTimestamps();
    }

    public function videos()
    {
        return $this->hasMany(TrainingVideo::class)->orderBy('order');
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }
}
