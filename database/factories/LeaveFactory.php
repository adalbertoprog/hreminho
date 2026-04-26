<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveFactory extends Factory
{
    protected $model = Leave::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 year', '+1 month');
        $endDate   = $this->faker->dateTimeBetween($startDate, (clone $startDate)->modify('+14 days'));

        return [
            'employee_id'     => Employee::factory(),
            'leave_type'      => $this->faker->randomElement(['vacation', 'sick', 'unpaid']),
            'start_date'      => $startDate->format('Y-m-d'),
            'end_date'        => $endDate->format('Y-m-d'),
            'reason'          => $this->faker->sentence(),
            'status'          => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'manager_comment' => $this->faker->optional()->sentence(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status'          => 'approved',
            'manager_comment' => $this->faker->sentence(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status'          => 'pending',
            'manager_comment' => null,
        ]);
    }
}
