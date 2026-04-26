<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {

    Route::apiResource('employees',   EmployeeController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('positions',   PositionController::class);
    Route::apiResource('sectors', SectorController::class);

    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('leaves',      LeaveController::class);

    // Treinamentos (catálogo)
    Route::apiResource('trainings', TrainingController::class);
    // Inscrições
    Route::get('enrollments',                    [TrainingController::class, 'enrollments'])->name('enrollments.index');
    Route::post('enrollments',                   [TrainingController::class, 'enroll'])->name('enrollments.store');
    Route::put('enrollments/{enrollment}',        [TrainingController::class, 'updateEnrollment'])->name('enrollments.update');
    Route::delete('enrollments/{enrollment}',     [TrainingController::class, 'destroyEnrollment'])->name('enrollments.destroy');

    // Utilizadores
    Route::apiResource('users', UserController::class);

    // Relatórios
    Route::get('reports/completed-trainings',   [ReportController::class, 'completedTrainings'])->name('reports.completed-trainings');
    Route::get('reports/employees-trainings',   [ReportController::class, 'employeesWithTrainings'])->name('reports.employees-trainings');
    Route::get('reports/training-employees',    [ReportController::class, 'trainingWithEmployees'])->name('reports.training-employees');
    Route::get('reports/attendance',            [ReportController::class, 'attendance'])->name('reports.attendance');
    Route::post('reports/send-email',           [ReportController::class, 'sendEmail'])->name('reports.send-email');
});
