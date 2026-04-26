<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();

        $employees->each(function (Employee $employee) {
            // Generate 30 attendance records per employee
            Attendance::factory()
                ->count(30)
                ->create(['employee_id' => $employee->id]);
        });
    }
}
