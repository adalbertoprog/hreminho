<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        $positions = [
            'Software Engineer', 'HR Specialist', 'Project Manager', 'Data Analyst',
            'Marketing Manager', 'Financial Analyst', 'Operations Manager', 'Sales Representative',
            'UX Designer', 'DevOps Engineer', 'Product Manager', 'Business Analyst',
        ];

        return [
            'position'    => $this->faker->unique()->randomElement($positions),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
