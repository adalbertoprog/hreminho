<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->sentence(3, false),
            'reference'  => strtoupper($this->faker->unique()->bothify('OBR-####')),
            'client'     => $this->faker->company(),
            'location'   => $this->faker->city(),
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'end_date'   => $this->faker->optional()->dateTimeBetween('now', '+1 year')?->format('Y-m-d'),
            'status'     => $this->faker->randomElement(['planned', 'active', 'completed', 'cancelled']),
            'notes'      => $this->faker->optional()->sentence(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => 'active']);
    }

    public function planned(): static
    {
        return $this->state(fn () => ['status' => 'planned']);
    }
}
