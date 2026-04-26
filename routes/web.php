<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Web\AttendanceWebController;
use App\Http\Controllers\Web\DepartmentWebController;
use App\Http\Controllers\Web\EmployeeWebController;
use App\Http\Controllers\Web\LeaveWebController;
use App\Http\Controllers\Web\PositionWebController;
use App\Http\Controllers\Web\SectorWebController;
use App\Http\Controllers\Web\TrainingWebController;
use App\Http\Controllers\Web\UserWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('home'))->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
    Route::get('/attendances', [AttendanceWebController::class, 'index'])->name('attendances.index');
    Route::get('/leaves',      [LeaveWebController::class,      'index'])->name('leaves.index');
    Route::get('/trainings',   [TrainingWebController::class,   'index'])->name('trainings.index');
    Route::get('/users',       [UserWebController::class,       'index'])->name('users.index');
});
