<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name'       => 'Equipa ' . $this->faker->randomLetter(),
            'leader_id'  => null,
            'notes'      => $this->faker->optional()->sentence(),
        ];
    }

    public function withLeader(): static
    {
        return $this->state(fn () => ['leader_id' => Employee::factory()]);
    }
}
