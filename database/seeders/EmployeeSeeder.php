<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Sector;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $positions   = Position::all();
        $departments = Department::all();
        $sectors     = Sector::all();

        // Create 30 active full-time employees
        Employee::factory()
            ->count(30)
            ->active()
            ->fullTime()
            ->create([
                'position_id'   => fn () => $positions->random()->id,
                'department_id' => fn () => $departments->random()->id,
                'sector_id'     => fn () => $sectors->random()->id,
            ]);

        // Create 10 mixed-status employees
        Employee::factory()
            ->count(10)
            ->create([
                'position_id'   => fn () => $positions->random()->id,
                'department_id' => fn () => $departments->random()->id,
                'sector_id'     => fn () => $sectors->isNotEmpty() ? $sectors->random()->id : null,
            ]);

        // Assign a manager to each department
        $departments->each(function (Department $department) {
            $manager = Employee::where('department_id', $department->id)->first();
            if ($manager) {
                $department->update(['manager_id' => $manager->id]);
            }
        });

        // Assign a manager to each sector
        $sectors->each(function (Sector $sector) {
            $manager = Employee::where('department_id', $sector->department_id)->first();
            if ($manager) {
                $sector->update(['manager_id' => $manager->id]);
            }
        });
    }
}
