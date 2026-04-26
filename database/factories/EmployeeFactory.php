<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        $contractTypes = ['full-time', 'part-time', 'freelance'];
        $statuses = ['active', 'inactive', 'terminated'];
        $genders = ['male', 'female', 'other'];
        $contractType = $this->faker->randomElement($contractTypes);

        return [
            'code'          => strtoupper($this->faker->unique()->bothify('EMP-####')),
            'first_name'    => $this->faker->firstName(),
            'last_name'     => $this->faker->lastName(),
            'email'         => $this->faker->unique()->safeEmail(),
            'phone'         => $this->faker->optional()->phoneNumber(),
            'date_of_birth' => $this->faker->dateTimeBetween('-55 years', '-20 years')->format('Y-m-d'),
            'gender'        => $this->faker->randomElement($genders),
            'nationality'   => $this->faker->country(),
            'address'       => $this->faker->address(),
            'work_location' => $this->faker->randomElement(['Sede', 'Filial Norte', 'Filial Sul', 'Remoto', 'Escritório Lisboa', 'Escritório Porto']),
            'profile_photo' => null,
            'position_id'   => Position::factory(),
            'department_id' => Department::factory(),
            'sector_id'     => null,
            'hire_date'     => $this->faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'status'        => $this->faker->randomElement($statuses),
            'contract_type' => $contractType,
            'end_date'      => in_array($contractType, ['part-time', 'freelance'])
                ? $this->faker->optional()->dateTimeBetween('now', '+2 years')?->format('Y-m-d')
                : null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => 'active']);
    }

    public function fullTime(): static
    {
        return $this->state(fn () => ['contract_type' => 'full-time', 'end_date' => null]);
    }
}
