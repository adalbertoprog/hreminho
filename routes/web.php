<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Web\AttendanceWebController;
use App\Http\Controllers\Web\DepartmentWebController;
use App\Http\Controllers\Web\EmployeeWebController;
use App\Http\Controllers\Web\EmployeePortalController;
use App\Http\Controllers\Web\LeaveWebController;
use App\Http\Controllers\Web\PositionWebController;
use App\Http\Controllers\Web\SectorWebController;
use App\Http\Controllers\Web\TrainingWebController;
use App\Http\Controllers\Web\TrainingDashboardController;
use App\Http\Controllers\Web\TrainingPlanWebController;
use App\Http\Controllers\Web\ReportWebController;
use App\Http\Controllers\Web\DocsElectroMinhoWebController;
use App\Http\Controllers\Web\UserWebController;
use App\Http\Controllers\Web\PasswordWebController;
use App\Http\Controllers\Web\CalendarWebController;
use App\Http\Controllers\Web\SettingsWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('home'))->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Rotas de password isentas do middleware force.password.change (evitar redirect loop)
Route::middleware('auth')->group(function () {
    Route::get('/password/change', [PasswordWebController::class, 'changeForm'])->name('password.change');
    Route::put('/password',        [PasswordWebController::class, 'update'])->name('password.update');
});

Route::middleware(['auth', 'force.password.change'])->group(function () {

    // Dashboard — redireciona employees para o portal (ver DashboardController)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Rotas exclusivas admin/hr ─────────────────────────────────────────
    Route::middleware('can:manage-hr')->group(function () {
        Route::get('/employees',               [EmployeeWebController::class,   'index'])->name('employees.index');
        Route::post('/employees',              [EmployeeWebController::class,   'store'])->name('employees.store');
        Route::put('/employees/{employee}',    [EmployeeWebController::class,   'update'])->name('employees.update');
        Route::delete('/employees/{employee}', [EmployeeWebController::class,   'destroy'])->name('employees.destroy');

        Route::get('/departments',                 [DepartmentWebController::class, 'index'])->name('departments.index');
        Route::post('/departments',                [DepartmentWebController::class, 'store'])->name('departments.store');
        Route::put('/departments/{department}',    [DepartmentWebController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{department}', [DepartmentWebController::class, 'destroy'])->name('departments.destroy');

        Route::get('/positions',               [PositionWebController::class,   'index'])->name('positions.index');
        Route::post('/positions',              [PositionWebController::class,   'store'])->name('positions.store');
        Route::put('/positions/{position}',    [PositionWebController::class,   'update'])->name('positions.update');
        Route::delete('/positions/{position}', [PositionWebController::class,   'destroy'])->name('positions.destroy');

        Route::get('/sectors',     [SectorWebController::class,     'index'])->name('sectors.index');
        Route::get('/leaves',      [LeaveWebController::class,      'index'])->name('leaves.index');
        Route::get('/trainings',           [TrainingWebController::class,       'index'])->name('trainings.index');
        Route::get('/trainings/dashboard', [TrainingDashboardController::class, 'index'])->name('trainings.dashboard');
        Route::get('/trainings/plan',      [TrainingPlanWebController::class,   'index'])->name('trainings.plan');
        Route::get('/users',   [UserWebController::class,   'index'])->name('users.index');
        Route::get('/reports', [ReportWebController::class, 'index'])->name('reports.index');

        Route::get('/calendar',        [CalendarWebController::class, 'index'])->name('calendar.index');
        Route::get('/calendar/events', [CalendarWebController::class, 'events'])->name('calendar.events');

        Route::get('/settings', [SettingsWebController::class, 'index'])->name('settings.index');

        Route::prefix('docsem')->name('docsem.')->group(function () {
            Route::get('/',                               [DocsElectroMinhoWebController::class, 'index'])->name('index');
            Route::post('/sync',                          [DocsElectroMinhoWebController::class, 'syncTodos'])->name('sync');
            Route::post('/sync/{employee}',               [DocsElectroMinhoWebController::class, 'syncFuncionario'])->name('sync.employee');
            Route::get('/employee/{employee}/documentos', [DocsElectroMinhoWebController::class, 'documentosFuncionario'])->name('employee.documentos');
            Route::get('/ping',                           [DocsElectroMinhoWebController::class, 'ping'])->name('ping');
        });
    });

    // ── Presenças — acesso a admin, hr e manager ─────────────────────────
    Route::middleware('can:manage-attendance')->group(function () {
        Route::get('/attendances', [AttendanceWebController::class, 'index'])->name('attendances.index');
    });

    // ── Portal do funcionário ─────────────────────────────────────────────
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard',           [EmployeePortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/training/{training}', [EmployeePortalController::class, 'training'])->name('training');
    });
});
