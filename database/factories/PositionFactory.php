<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        return [
            'position'    => $this->faker->unique()->words(3, true),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
