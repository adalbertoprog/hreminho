<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\Training;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition(): array
    {
        return [
            'training_id'   => Training::factory(),
            'title'         => $this->faker->sentence(4),
            'description'   => $this->faker->optional()->sentence(),
            'passing_score' => 70,
        ];
    }
}
