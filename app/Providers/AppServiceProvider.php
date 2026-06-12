<?php

namespace App\Providers;

use App\Models\EmployeeTraining;
use App\Services\DocsElectroMinhoService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DocsElectroMinhoService::class, function () {
            return new DocsElectroMinhoService();
        });
    }

    public function boot(): void
    {
        Route::model('enrollment', EmployeeTraining::class);

        // Admin tem sempre acesso total
        Gate::before(fn($user) => $user->role === 'admin' ? true : null);

        // Gates fixos
        Gate::define('employee-portal', fn($user) => in_array($user->role, ['employee', 'manager']));
        Gate::define('admin-only',      fn($user) => $user->role === 'admin');

        // Gates compostos (compatibilidade com rotas existentes)
        Gate::define('manage-hr', fn($user) =>
            PermissionService::allows($user->role, 'edit_employees') ||
            PermissionService::allows($user->role, 'manage_trainings')
        );

        Gate::define('manage-attendance', fn($user) =>
            PermissionService::allows($user->role, 'manage_attendances')
        );

        // Gates granulares
        Gate::define('view-employees',     fn($u) => PermissionService::allows($u->role, 'view_employees'));
        Gate::define('edit-employees',     fn($u) => PermissionService::allows($u->role, 'edit_employees'));
        Gate::define('delete-employees',   fn($u) => PermissionService::allows($u->role, 'delete_employees'));
        Gate::define('view-attendances',   fn($u) => PermissionService::allows($u->role, 'view_attendances'));
        Gate::define('manage-attendances', fn($u) => PermissionService::allows($u->role, 'manage_attendances'));
        Gate::define('approve-leaves',     fn($u) => PermissionService::allows($u->role, 'approve_leaves'));
        Gate::define('view-all-leaves',    fn($u) => PermissionService::allows($u->role, 'view_all_leaves'));
        Gate::define('view-projects',      fn($u) => PermissionService::allows($u->role, 'view_projects'));
        Gate::define('manage-projects',    fn($u) => PermissionService::allows($u->role, 'manage_projects'));
        Gate::define('view-reports',       fn($u) => PermissionService::allows($u->role, 'view_reports'));
        Gate::define('manage-trainings',   fn($u) => PermissionService::allows($u->role, 'manage_trainings'));
    }
}
