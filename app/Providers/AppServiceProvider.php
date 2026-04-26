<?php

namespace App\Providers;

use App\Models\EmployeeTraining;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Route model binding: {enrollment} -> EmployeeTraining
        Route::model('enrollment', EmployeeTraining::class);
    }
}
