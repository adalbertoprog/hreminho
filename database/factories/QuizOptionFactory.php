<?php

namespace Database\Factories;

use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizOptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question_id' => QuizQuestion::factory(),
            'text'        => $this->faker->sentence(3),
            'is_correct'  => false,
            'order'       => $this->faker->numberBetween(1, 4),
        ];
    }
}
