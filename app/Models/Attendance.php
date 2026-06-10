<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SystemSetting;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'lunch_out',
        'lunch_in',
        'check_out',
        'status',
        'notes',
        'leave_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getWorkedMinutesAttribute(): ?int
    {
        if (!$this->check_in) return null;

        $toMinutes = fn($time) => $time
            ? (int) substr($time, 0, 2) * 60 + (int) substr($time, 3, 2)
            : null;

        $in   = $toMinutes($this->check_in);
        $out  = $toMinutes($this->check_out);
        $lOut = $toMinutes($this->lunch_out);
        $lIn  = $toMinutes($this->lunch_in);

        if ($in === null || $out === null) return null;

        if ($lOut !== null && $lIn !== null) {
            return max(0, ($lOut - $in)) + max(0, ($out - $lIn));
        }

        return max(0, $out - $in);
    }

    public function getWorkedHoursFormattedAttribute(): ?string
    {
        $mins = $this->worked_minutes;
        if ($mins === null) return null;
        return floor($mins / 60) . 'h' . str_pad($mins % 60, 2, '0', STR_PAD_LEFT);
    }

    public static function computeStatus(?string $checkIn, ?Employee $employee): string
    {
        if (!$checkIn) return 'absent';

        $globalDefault = SystemSetting::get('attendance_default_check_in', '09:00');
        $expected      = $employee?->expected_check_in ?? $globalDefault;
        $tolerance     = (int) SystemSetting::get('attendance_late_tolerance_minutes', 0);

        $toMins = fn($t) => (int) substr($t, 0, 2) * 60 + (int) substr($t, 3, 2);

        return $toMins($checkIn) <= ($toMins($expected) + $tolerance) ? 'present' : 'late';
    }
}
