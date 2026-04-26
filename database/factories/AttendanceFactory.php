<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['present', 'absent', 'late', 'holiday', 'on_leave']);
        $hasCheckIn = in_array($status, ['present', 'late']);

        return [
            'employee_id' => Employee::factory(),
            'date'        => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'check_in'    => $hasCheckIn ? $this->faker->time('H:i:s', '10:00:00') : null,
            'check_out'   => $hasCheckIn ? $this->faker->time('H:i:s', '18:00:00') : null,
            'status'      => $status,
        ];
    }

    public function present(): static
    {
        return $this->state(fn () => [
            'status'    => 'present',
            'check_in'  => '08:00:00',
            'check_out' => '17:00:00',
        ]);
    }
}
