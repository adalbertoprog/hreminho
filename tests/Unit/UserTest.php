<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // ── Roles ────────────────────────────────────────────────────────────

    public function test_user_factory_creates_employee_role_by_default(): void
    {
        $user = User::factory()->make();

        $this->assertSame('employee', $user->role);
    }

    public function test_user_factory_admin_state(): void
    {
        $user = User::factory()->admin()->make();

        $this->assertSame('admin', $user->role);
    }

    public function test_user_factory_hr_state(): void
    {
        $user = User::factory()->hr()->make();

        $this->assertSame('HR', $user->role);
    }

    // ── must_change_password cast ────────────────────────────────────────

    public function test_must_change_password_defaults_to_false(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->must_change_password);
    }

    public function test_must_change_password_can_be_set_to_true(): void
    {
        $user = User::factory()->create(['must_change_password' => true]);

        $this->assertTrue($user->must_change_password);
    }

    public function test_must_change_password_is_cast_to_boolean(): void
    {
        $user = User::factory()->create(['must_change_password' => 1]);

        $this->assertIsBool($user->must_change_password);
        $this->assertTrue($user->must_change_password);
    }

    // ── Relação com Employee ─────────────────────────────────────────────

    public function test_user_has_one_employee(): void
    {
        $user     = User::factory()->create();
        $employee = Employee::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Employee::class, $user->employee);
        $this->assertSame($employee->id, $user->employee->id);
    }

    public function test_user_employee_is_null_when_not_associated(): void
    {
        $user = User::factory()->create();

        $this->assertNull($user->employee);
    }

    // ── Gates de autorização ─────────────────────────────────────────────

    public function test_admin_can_manage_hr(): void
    {
        $user = User::factory()->admin()->create();

        $this->assertTrue(Gate::forUser($user)->allows('manage-hr'));
    }

    public function test_hr_can_manage_hr(): void
    {
        $user = User::factory()->create(['role' => 'hr']);

        $this->assertTrue(Gate::forUser($user)->allows('manage-hr'));
    }

    public function test_employee_cannot_manage_hr(): void
    {
        $user = User::factory()->create(['role' => 'employee']);

        $this->assertFalse(Gate::forUser($user)->allows('manage-hr'));
    }

    public function test_admin_can_use_admin_only_gate(): void
    {
        $user = User::factory()->admin()->create();

        $this->assertTrue(Gate::forUser($user)->allows('admin-only'));
    }

    public function test_hr_cannot_use_admin_only_gate(): void
    {
        $user = User::factory()->create(['role' => 'hr']);

        $this->assertFalse(Gate::forUser($user)->allows('admin-only'));
    }

    public function test_employee_can_use_employee_portal_gate(): void
    {
        $user = User::factory()->create(['role' => 'employee']);

        $this->assertTrue(Gate::forUser($user)->allows('employee-portal'));
    }

    public function test_admin_cannot_use_employee_portal_gate(): void
    {
        $user = User::factory()->admin()->create();

        $this->assertFalse(Gate::forUser($user)->allows('employee-portal'));
    }

    // ── Password é hashed ────────────────────────────────────────────────

    public function test_password_is_hashed_on_create(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->assertNotSame('secret123', $user->password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('secret123', $user->password));
    }

    // ── Hidden ───────────────────────────────────────────────────────────

    public function test_password_and_remember_token_are_hidden(): void
    {
        $user = User::factory()->make()->toArray();

        $this->assertArrayNotHasKey('password', $user);
        $this->assertArrayNotHasKey('remember_token', $user);
    }
}
