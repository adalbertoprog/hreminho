<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['position' => 'Software Engineer',    'description' => 'Develops and maintains software systems.'],
            ['position' => 'HR Specialist',         'description' => 'Handles recruitment, onboarding and HR policies.'],
            ['position' => 'Project Manager',       'description' => 'Oversees projects from planning to delivery.'],
            ['position' => 'Data Analyst',          'description' => 'Analyzes data to generate business insights.'],
            ['position' => 'Marketing Manager',     'description' => 'Plans and executes marketing strategies.'],
            ['position' => 'Financial Analyst',     'description' => 'Evaluates financial data and prepares reports.'],
            ['position' => 'Operations Manager',    'description' => 'Manages daily operational processes.'],
            ['position' => 'Sales Representative',  'description' => 'Identifies and pursues sales opportunities.'],
            ['position' => 'UX Designer',           'description' => 'Designs user-centered digital experiences.'],
            ['position' => 'DevOps Engineer',       'description' => 'Manages CI/CD pipelines and infrastructure.'],
        ];

        foreach ($positions as $position) {
            Position::firstOrCreate(['position' => $position['position']], $position);
        }
    }
}
