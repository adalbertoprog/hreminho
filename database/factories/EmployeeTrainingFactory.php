<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Training;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeTrainingFactory extends Factory
{
    protected $model = EmployeeTraining::class;

    public function definition(): array
    {
        $status    = $this->faker->randomElement(['enrolled', 'completed', 'failed']);
        $startDate = $this->faker->dateTimeBetween('-2 years', 'now');
        $endDate   = $this->faker->optional()->dateTimeBetween($startDate, (clone $startDate)->modify('+6 months'));

        return [
            'employee_id'      => Employee::factory(),
            'training_id'      => Training::factory(),
            'status'           => $status,
            'certificate_path' => $status === 'completed'
                ? 'certificates/' . $this->faker->slug(2) . '.pdf'
                : null,
            'score'            => in_array($status, ['completed', 'failed'])
                ? $this->faker->randomFloat(2, 0, 100)
                : null,
            'start_date'       => $startDate->format('Y-m-d'),
            'end_date'         => $endDate?->format('Y-m-d'),
            'notes'            => $this->faker->optional()->sentence(),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status'           => 'completed',
            'score'            => $this->faker->randomFloat(2, 60, 100),
            'certificate_path' => 'certificates/' . $this->faker->slug(2) . '.pdf',
        ]);
    }
}
