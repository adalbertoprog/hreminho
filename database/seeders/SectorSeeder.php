<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            ['sector' => 'Recruitment',          'department' => 'Human Resources'],
            ['sector' => 'Payroll',               'department' => 'Human Resources'],
            ['sector' => 'Backend Development',   'department' => 'Engineering'],
            ['sector' => 'Frontend Development',  'department' => 'Engineering'],
            ['sector' => 'QA & Testing',          'department' => 'Engineering'],
            ['sector' => 'Accounting',            'department' => 'Finance'],
            ['sector' => 'Financial Planning',    'department' => 'Finance'],
            ['sector' => 'Digital Marketing',     'department' => 'Marketing'],
            ['sector' => 'Content & Social',      'department' => 'Marketing'],
            ['sector' => 'Logistics',             'department' => 'Operations'],
            ['sector' => 'Inside Sales',          'department' => 'Sales'],
            ['sector' => 'Technical Support',     'department' => 'Customer Support'],
        ];

        foreach ($sectors as $item) {
            $department = Department::where('department', $item['department'])->first();

            if ($department) {
                Sector::firstOrCreate(
                    ['sector' => $item['sector']],
                    ['sector' => $item['sector'], 'department_id' => $department->id]
                );
            }
        }
    }
}
