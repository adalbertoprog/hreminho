<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Training;
use Illuminate\Database\Seeder;

class EmployeeTrainingSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $trainings = Training::all();

        if ($trainings->isEmpty()) {
            return;
        }

        $employees->each(function (Employee $employee) use ($trainings) {
            // Assign 1-3 random trainings per employee (no duplicates)
            $assigned = $trainings->random(min(rand(1, 3), $trainings->count()));

            foreach ($assigned as $training) {
                EmployeeTraining::factory()->create([
                    'employee_id' => $employee->id,
                    'training_id' => $training->id,
                ]);
            }
        });
    }
}
