<?php

namespace App\Providers;

use App\Models\EmployeeTraining;
use App\Services\DocsElectroMinhoService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registar o serviço de integração como singleton
        $this->app->singleton(DocsElectroMinhoService::class, function () {
            return new DocsElectroMinhoService();
        });
    }

    public function boot(): void
    {
        // Route model binding: {enrollment} -> EmployeeTraining
        Route::model('enrollment', EmployeeTraining::class);
    }
}
