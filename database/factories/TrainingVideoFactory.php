<?php

namespace Database\Factories;

use App\Models\Training;
use App\Models\TrainingVideo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingVideoFactory extends Factory
{
    protected $model = TrainingVideo::class;

    public function definition(): array
    {
        return [
            'training_id' => Training::factory(),
            'title'       => $this->faker->sentence(4),
            'url'         => 'https://www.youtube.com/embed/' . $this->faker->regexify('[A-Za-z0-9_-]{11}'),
            'description' => $this->faker->optional()->sentence(),
            'order'       => $this->faker->numberBetween(1, 10),
            'is_uploaded' => false,
        ];
    }
}
