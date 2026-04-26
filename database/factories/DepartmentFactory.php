<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        $departments = [
            'Human Resources', 'Engineering', 'Finance', 'Marketing',
            'Operations', 'Sales', 'Legal', 'Customer Support',
        ];

        return [
            'department'  => $this->faker->unique()->randomElement($departments),
            'description' => $this->faker->optional()->sentence(),
            'manager_id'  => null,
        ];
    }
}
