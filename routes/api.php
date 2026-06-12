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
use App\Http\Controllers\Web\EmployeeLeaveController;
use App\Http\Controllers\MandatoryTrainingController;
use App\Http\Controllers\TrainingSessionController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectCompanyController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->middleware('auth:web')->group(function () {

    // ── Rotas acessíveis a TODOS os utilizadores autenticados ─────────────
    // (admin, hr e employee)

    // Portal do funcionário — associação por código
    Route::post('employee-portal/associate', [EmployeeAssociationController::class, 'associate'])->name('employee-portal.associate');

    // Portal — funcionário submete pedido de licença (qualquer autenticado com employee)
    Route::post('employee-portal/leaves',                    [EmployeeLeaveController::class, 'store'])->name('employee-portal.leaves.store');
    Route::delete('employee-portal/leaves/{leaveId}',        [EmployeeLeaveController::class, 'cancel'])->name('employee-portal.leaves.cancel');

    // Portal — manager aprova/rejeita pedidos dos seus funcionários
    Route::middleware('can:manage-attendance')->group(function () {
        Route::put('employee-portal/leaves/{leaveId}/approve', [EmployeeLeaveController::class, 'approve'])->name('employee-portal.leaves.approve');
        Route::put('employee-portal/leaves/{leaveId}/reject',  [EmployeeLeaveController::class, 'reject'])->name('employee-portal.leaves.reject');
    });

    // Quiz — leitura e submissão (employee só vê quiz sem respostas corretas; ver QuizController::show)
    Route::get('trainings/{training}/quiz',    [QuizController::class, 'show'])->name('trainings.quiz.show');
    Route::post('quiz/{training}/attempt',     [QuizController::class, 'attempt'])->name('quiz.attempt');
    Route::get('quiz/{training}/my-attempts',  [QuizController::class, 'myAttempts'])->name('quiz.my-attempts');

    // Catálogo de formações (leitura) — employee precisa para o portal
    Route::get('trainings',          [TrainingController::class, 'index'])->name('trainings.index');
    Route::get('trainings/{training}', [TrainingController::class, 'show'])->name('trainings.show');

    // Vídeos (leitura) — employee precisa para o player
    Route::get('trainings/{training}/videos',  [TrainingVideoController::class, 'index'])->name('trainings.videos.index');
    Route::get('videos/{video}',               [TrainingVideoController::class, 'show'])->name('videos.show');

    // ── Presenças — admin, hr e manager ──────────────────────────────────
    Route::middleware('can:manage-attendance')->group(function () {
        Route::apiResource('attendances', AttendanceController::class);
        // Leitura de funcionários e settings necessária para a view de presenças
        Route::get('employees-for-attendance', [EmployeeController::class, 'index'])->name('employees.for-attendance');
        // Settings (leitura) — manager precisa para calcular preview de status
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.read');
    });

    // ── Rotas exclusivas de admin/hr (manage-hr) ──────────────────────────
    Route::middleware('can:manage-hr')->group(function () {

        // Funcionários
        Route::post('employees/bulk-create-users', [BulkUserController::class, 'createEmployeeUsers'])->name('employees.bulk-create-users');
        Route::apiResource('employees',   EmployeeController::class);

        // Estrutura organizacional
        Route::apiResource('departments', DepartmentController::class);
        Route::apiResource('positions',   PositionController::class);
        Route::apiResource('sectors',     SectorController::class);

        // Férias
        Route::apiResource('leaves',      LeaveController::class);

        // Gestão do catálogo de formações (escrita)
        Route::post('trainings',             [TrainingController::class, 'store'])->name('trainings.store');
        Route::put('trainings/{training}',   [TrainingController::class, 'update'])->name('trainings.update');
        Route::delete('trainings/{training}',[TrainingController::class, 'destroy'])->name('trainings.destroy');

        // Inscrições
        Route::get('enrollments',                  [TrainingController::class, 'enrollments'])->name('enrollments.index');
        Route::post('enrollments',                 [TrainingController::class, 'enroll'])->name('enrollments.store');
        Route::put('enrollments/{enrollment}',      [TrainingController::class, 'updateEnrollment'])->name('enrollments.update');
        Route::delete('enrollments/{enrollment}',   [TrainingController::class, 'destroyEnrollment'])->name('enrollments.destroy');
        Route::post('enrollments/{enrollment}/certificate', [TrainingController::class, 'uploadCertificate'])->name('enrollments.certificate');

        // Vídeos (escrita)
        Route::post('trainings/{training}/videos',   [TrainingVideoController::class, 'store'])->name('trainings.videos.store');
        Route::put('videos/{video}',                 [TrainingVideoController::class, 'update'])->name('videos.update');
        Route::delete('videos/{video}',              [TrainingVideoController::class, 'destroy'])->name('videos.destroy');

        // Quiz (gestão)
        Route::post('trainings/{training}/quiz',        [QuizController::class, 'store'])->name('trainings.quiz.store');
        Route::put('trainings/{training}/quiz',         [QuizController::class, 'update'])->name('trainings.quiz.update');
        Route::get('trainings/{training}/quiz/results', [QuizController::class, 'results'])->name('trainings.quiz.results');

        // Plano anual (annual-summary antes do index para evitar conflito de rotas)
        Route::get   ('training-sessions/annual-summary',        [TrainingSessionController::class, 'annualSummary'])->name('training-sessions.annual-summary');
        Route::get   ('training-sessions',                       [TrainingSessionController::class, 'index'])->name('training-sessions.index');
        Route::post  ('training-sessions',                       [TrainingSessionController::class, 'store'])->name('training-sessions.store');
        Route::put   ('training-sessions/{trainingSession}',     [TrainingSessionController::class, 'update'])->name('training-sessions.update');
        Route::delete('training-sessions/{trainingSession}',     [TrainingSessionController::class, 'destroy'])->name('training-sessions.destroy');

        // Formações obrigatórias (compliance antes de {mandatoryTraining} para evitar conflito)
        Route::get   ('mandatory-trainings/compliance',               [MandatoryTrainingController::class, 'compliance'])->name('mandatory-trainings.compliance');
        Route::get   ('mandatory-trainings',                          [MandatoryTrainingController::class, 'index'])->name('mandatory-trainings.index');
        Route::post  ('mandatory-trainings',                          [MandatoryTrainingController::class, 'store'])->name('mandatory-trainings.store');
        Route::put   ('mandatory-trainings/{mandatoryTraining}',      [MandatoryTrainingController::class, 'update'])->name('mandatory-trainings.update');
        Route::delete('mandatory-trainings/{mandatoryTraining}',      [MandatoryTrainingController::class, 'destroy'])->name('mandatory-trainings.destroy');
        Route::get   ('mandatory-trainings/{mandatoryTraining}/gaps', [MandatoryTrainingController::class, 'gaps'])->name('mandatory-trainings.gaps');

        // Configurações do sistema (leitura em manage-attendance; escrita apenas aqui)
        Route::put('settings',  [SettingsController::class, 'update'])->name('settings.update');

        // Feriados
        Route::get   ('holidays',          [HolidayController::class, 'index'])->name('holidays.index');
        Route::post  ('holidays',          [HolidayController::class, 'store'])->name('holidays.store');
        Route::put   ('holidays/{holiday}',[HolidayController::class, 'update'])->name('holidays.update');
        Route::delete('holidays/{holiday}',[HolidayController::class, 'destroy'])->name('holidays.destroy');

        // Utilizadores
        Route::apiResource('users', UserController::class);

        // Obras, Equipas e Viaturas
        Route::apiResource('vehicles', VehicleController::class);
        Route::apiResource('projects', ProjectController::class);
        Route::get ('projects/{project}/teams',                          [TeamController::class, 'index'])->name('projects.teams.index');
        Route::post('projects/{project}/teams',                          [TeamController::class, 'store'])->name('projects.teams.store');
        Route::put ('projects/{project}/teams/{team}',                   [TeamController::class, 'update'])->name('projects.teams.update');
        Route::delete('projects/{project}/teams/{team}',                 [TeamController::class, 'destroy'])->name('projects.teams.destroy');
        Route::post('projects/{project}/teams/{team}/employees',         [TeamController::class, 'addEmployee'])->name('projects.teams.employees.add');
        Route::delete('projects/{project}/teams/{team}/employees',       [TeamController::class, 'removeEmployee'])->name('projects.teams.employees.remove');
        Route::post('projects/{project}/teams/{team}/vehicles',          [TeamController::class, 'addVehicle'])->name('projects.teams.vehicles.add');
        Route::delete('projects/{project}/teams/{team}/vehicles',        [TeamController::class, 'removeVehicle'])->name('projects.teams.vehicles.remove');

        // Empresas subcontratadas (integração DocsElectro-Minho)
        Route::get   ('projects/{project}/companies',           [ProjectCompanyController::class, 'index'])->name('projects.companies.index');
        Route::post  ('projects/{project}/companies',           [ProjectCompanyController::class, 'store'])->name('projects.companies.store');
        Route::put   ('projects/{project}/companies/{company}', [ProjectCompanyController::class, 'update'])->name('projects.companies.update');
        Route::delete('projects/{project}/companies/{company}', [ProjectCompanyController::class, 'destroy'])->name('projects.companies.destroy');
        // Pesquisa de empresas no DocsEM (para o picker)
        Route::get   ('docsem/empresas',                        [ProjectCompanyController::class, 'searchDocsem'])->name('docsem.empresas.search');
        // Pesquisa de obras no DocsEM (para o picker de ligacao)
        Route::get   ('docsem/obras',                           [ProjectController::class, 'searchDocsemObras'])->name('docsem.obras.search');
        // Sincronizar obra com DocsEM (actualiza dados + importa empresas)
        Route::post  ('projects/{project}/sync-docsem',         [ProjectController::class, 'syncDocsem'])->name('projects.sync-docsem');

        // Relatórios
        Route::get('reports/completed-trainings', [ReportController::class, 'completedTrainings'])->name('reports.completed-trainings');
        Route::get('reports/employees-trainings', [ReportController::class, 'employeesWithTrainings'])->name('reports.employees-trainings');
        Route::get('reports/training-employees',  [ReportController::class, 'trainingWithEmployees'])->name('reports.training-employees');
        Route::get('reports/attendance',          [ReportController::class, 'attendance'])->name('reports.attendance');
        Route::get('reports/validity',            [ReportController::class, 'validityReport'])->name('reports.validity');
        Route::get('reports/gaps',                [ReportController::class, 'gapAnalysis'])->name('reports.gaps');
        Route::post('reports/send-email',         [ReportController::class, 'sendEmail'])->name('reports.send-email');
    });
});
