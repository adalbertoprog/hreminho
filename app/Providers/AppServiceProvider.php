<?php

namespace App\Providers;

use App\Models\EmployeeTraining;
use App\Services\DocsElectroMinhoService;
use Illuminate\Support\Facades\Gate;
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

        // ── Gates de autorização por role ──────────────────────────────
        // Uso: Gate::allows('manage-hr') ou $this->authorize('manage-hr') nos controllers
        // Ou nas views: @can('manage-hr') ... @endcan

        // Acesso total de gestão RH (admin e hr)
        Gate::define('manage-hr', fn($user) => in_array($user->role, ['admin', 'hr']));

        // Gestão de presenças (admin, hr e manager)
        Gate::define('manage-attendance', fn($user) => in_array($user->role, ['admin', 'hr', 'manager']));

        // Apenas admin
        Gate::define('admin-only', fn($user) => $user->role === 'admin');

        // Portal do funcionário (apenas role employee)
        Gate::define('employee-portal', fn($user) => $user->role === 'employee');
    }
}
