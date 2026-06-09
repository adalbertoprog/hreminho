<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizQuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiz_id'  => Quiz::factory(),
            'question' => $this->faker->sentence() . '?',
            'type'     => 'mc',
            'order'    => $this->faker->numberBetween(1, 10),
        ];
    }
}
