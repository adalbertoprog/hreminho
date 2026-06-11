<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        $brands = ['Renault', 'Mercedes', 'Ford', 'Volkswagen', 'Iveco', 'Fiat'];
        $models = ['Master', 'Sprinter', 'Transit', 'Crafter', 'Daily', 'Ducato'];

        return [
            'plate'  => strtoupper($this->faker->unique()->bothify('??-##-??')),
            'brand'  => $this->faker->randomElement($brands),
            'model'  => $this->faker->randomElement($models),
            'year'   => $this->faker->numberBetween(2010, 2024),
            'type'   => $this->faker->randomElement(['van', 'truck', 'car', 'other']),
            'status' => $this->faker->randomElement(['active', 'maintenance', 'inactive']),
            'notes'  => $this->faker->optional()->sentence(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => 'active']);
    }
}
