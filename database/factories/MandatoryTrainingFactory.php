<?php

namespace Database\Factories;

use App\Models\MandatoryTraining;
use App\Models\Training;
use Illuminate\Database\Eloquent\Factories\Factory;

class MandatoryTrainingFactory extends Factory
{
    protected $model = MandatoryTraining::class;

    public function definition(): array
    {
        return [
            'training_id'   => Training::factory(),
            'target_type'   => 'all',
            'target_id'     => null,
            'deadline_days' => null,
            'notes'         => null,
        ];
    }
}
