<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizAttemptFactory extends Factory
{
    protected $model = QuizAttempt::class;

    public function definition(): array
    {
        $score  = $this->faker->numberBetween(0, 100);
        $passed = $score >= 70;

        return [
            'quiz_id'      => Quiz::factory(),
            'user_id'      => User::factory(),
            'score'        => $score,
            'passed'       => $passed,
            'completed_at' => now(),
        ];
    }
}
