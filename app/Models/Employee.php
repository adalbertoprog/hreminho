<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'nationality',
        'address',
        'work_location',
        'profile_photo',
        'position_id',
        'department_id',
        'sector_id',
        'hire_date',
        'status',
        'contract_type',
        'end_date',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date'     => 'date',
        'end_date'      => 'date',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function employeeTrainings()
    {
        return $this->hasMany(EmployeeTraining::class);
    }

    public function trainings()
    {
        return $this->belongsToMany(Training::class, 'employee_trainings')
                    ->withPivot('status', 'certificate_path', 'score', 'start_date', 'end_date', 'notes')
                    ->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo
            ? asset('storage/' . $this->profile_photo)
            : null;
    }
}
