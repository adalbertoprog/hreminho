<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectorFactory extends Factory
{
    protected $model = Sector::class;

    public function definition(): array
    {
        $sectors = [
            'Recruitment', 'Payroll', 'Backend Development', 'Frontend Development',
            'Accounting', 'Digital Marketing', 'Logistics', 'Inside Sales',
            'Compliance', 'Technical Support', 'QA', 'Infrastructure',
        ];

        return [
            'sector'        => $this->faker->unique()->randomElement($sectors),
            'department_id' => Department::factory(),
            'manager_id'    => null,
        ];
    }
}
