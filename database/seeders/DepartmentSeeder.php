<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['department' => 'Human Resources', 'description' => 'Manages people, policies, and workplace culture.'],
            ['department' => 'Engineering',      'description' => 'Responsible for software development and infrastructure.'],
            ['department' => 'Finance',          'description' => 'Controls budgets, accounts and financial planning.'],
            ['department' => 'Marketing',        'description' => 'Drives brand awareness and lead generation.'],
            ['department' => 'Operations',       'description' => 'Ensures smooth day-to-day business operations.'],
            ['department' => 'Sales',            'description' => 'Generates revenue through client acquisition.'],
            ['department' => 'Legal',            'description' => 'Handles contracts, compliance and legal matters.'],
            ['department' => 'Customer Support', 'description' => 'Provides assistance to customers post-sale.'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['department' => $dept['department']], $dept);
        }
    }
}
