<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveAttachment;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();

        $employees->each(function (Employee $employee) {
            // 1-3 leave requests per employee
            $leaves = Leave::factory()
                ->count(rand(1, 3))
                ->create(['employee_id' => $employee->id]);

            // Some leaves may have attachments
            $leaves->each(function (Leave $leave) {
                if (rand(0, 1)) {
                    LeaveAttachment::factory()
                        ->count(rand(1, 2))
                        ->create(['leave_id' => $leave->id]);
                }
            });
        });
    }
}
