<?php

namespace Database\Factories;

use App\Models\Training;
use App\Models\TrainingSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingSessionFactory extends Factory
{
    protected $model = TrainingSession::class;

    public function definition(): array
    {
        $plannedDate = $this->faker->dateTimeBetween('now', '+6 months');

        return [
            'training_id'            => Training::factory(),
            'planned_date'           => $plannedDate->format('Y-m-d'),
            'planned_end_date'       => null,
            'location'               => $this->faker->optional()->city(),
            'max_participants'       => null,
            'estimated_participants' => null,
            'cost_per_person'        => null,
            'status'                 => 'planned',
            'notes'                  => null,
        ];
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => 'cancelled']);
    }
}
