<?php

namespace Database\Factories;

use App\Models\Training;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingFactory extends Factory
{
    protected $model = Training::class;

    public function definition(): array
    {
        $titles = [
            'Laravel Advanced Techniques', 'Leadership & Management', 'Data Analysis with Python',
            'Communication Skills', 'Cybersecurity Fundamentals', 'Project Management (PMP)',
            'Excel for Finance', 'UX Design Principles', 'DevOps & CI/CD', 'Agile & Scrum',
            'HR Best Practices', 'Customer Service Excellence', 'Cloud Computing Basics',
        ];

        return [
            'title'       => $this->faker->unique()->randomElement($titles),
            'description' => $this->faker->optional()->paragraph(),
            'provider'    => $this->faker->randomElement([
                'Udemy', 'Coursera', 'LinkedIn Learning', 'Internal Training',
                'Alura', 'FGV', 'Sebrae', 'Harvard Online',
            ]),
        ];
    }
}
