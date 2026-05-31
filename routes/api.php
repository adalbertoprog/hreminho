<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TrainingVideoController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\BulkUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Web\EmployeeAssociationController;
use App\Http\Controllers\MandatoryTrainingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->middleware('auth:web')->group(function () {

    // Rotas especificas de employees devem vir ANTES do apiResource para evitar conflitos
    Route::post('employees/bulk-create-users', [BulkUserController::class, 'createEmployeeUsers'])->name('employees.bulk-create-users');

    Route::apiResource('employees',   EmployeeController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('positions',   PositionController::class);
    Route::apiResource('sectors', SectorController::class);

    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('leaves',      LeaveController::class);

    // Formacoes (catalogo)
    Route::apiResource('trainings', TrainingController::class);
    // Inscricoes
    Route::get('enrollments',                    [TrainingController::class, 'enrollments'])->name('enrollments.index');
    Route::post('enrollments',                   [TrainingController::class, 'enroll'])->name('enrollments.store');
    Route::put('enrollments/{enrollment}',        [TrainingController::class, 'updateEnrollment'])->name('enrollments.update');
    Route::delete('enrollments/{enrollment}',     [TrainingController::class, 'destroyEnrollment'])->name('enrollments.destroy');

    // Videos e questionarios (gestao por admin/hr)
    Route::apiResource('trainings.videos', TrainingVideoController::class)->shallow();
    Route::get('trainings/{training}/quiz',         [QuizController::class, 'show'])->name('trainings.quiz.show');
    Route::post('trainings/{training}/quiz',        [QuizController::class, 'store'])->name('trainings.quiz.store');
    Route::put('trainings/{training}/quiz',         [QuizController::class, 'update'])->name('trainings.quiz.update');
    Route::get('trainings/{training}/quiz/results', [QuizController::class, 'results'])->name('trainings.quiz.results');
    Route::post('quiz/{training}/attempt',          [QuizController::class, 'attempt'])->name('quiz.attempt');
    Route::get('quiz/{training}/my-attempts',       [QuizController::class, 'myAttempts'])->name('quiz.my-attempts');

    // Formações obrigatórias (compliance antes de {mandatoryTraining} para evitar conflito)
    Route::get   ('mandatory-trainings/compliance',               [MandatoryTrainingController::class, 'compliance'])->name('mandatory-trainings.compliance');
    Route::get   ('mandatory-trainings',                          [MandatoryTrainingController::class, 'index'])->name('mandatory-trainings.index');
    Route::post  ('mandatory-trainings',                          [MandatoryTrainingController::class, 'store'])->name('mandatory-trainings.store');
    Route::put   ('mandatory-trainings/{mandatoryTraining}',      [MandatoryTrainingController::class, 'update'])->name('mandatory-trainings.update');
    Route::delete('mandatory-trainings/{mandatoryTraining}',      [MandatoryTrainingController::class, 'destroy'])->name('mandatory-trainings.destroy');
    Route::get   ('mandatory-trainings/{mandatoryTraining}/gaps', [MandatoryTrainingController::class, 'gaps'])->name('mandatory-trainings.gaps');

    // Utilizadores
    Route::apiResource('users', UserController::class);

    // Portal do funcionario — associacao por codigo
    Route::post('employee-portal/associate', [EmployeeAssociationController::class, 'associate'])->name('employee-portal.associate');

    // Relatorios
    Route::get('reports/completed-trainings',   [ReportController::class, 'completedTrainings'])->name('reports.completed-trainings');
    Route::get('reports/employees-trainings',   [ReportController::class, 'employeesWithTrainings'])->name('reports.employees-trainings');
    Route::get('reports/training-employees',    [ReportController::class, 'trainingWithEmployees'])->name('reports.training-employees');
    Route::get('reports/attendance',            [ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('reports/validity',              [ReportController::class, 'validityReport'])->name('reports.validity');
    Route::post('reports/send-email',           [ReportController::class, 'sendEmail'])->name('reports.send-email');
});
