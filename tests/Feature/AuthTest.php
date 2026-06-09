<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ─────────────────────────────────────────────────────────

    private function makeUser(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'password'            => Hash::make('password123'),
            'must_change_password' => false,
        ], $attrs));
    }

    // ── Login por e-mail ─────────────────────────────────────────────────

    public function test_admin_can_login_with_email(): void
    {
        $user = $this->makeUser(['role' => 'admin']);

        $response = $this->post('/login', [
            'login'    => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_hr_can_login_with_email(): void
    {
        $user = $this->makeUser(['role' => 'hr']);

        $this->post('/login', ['login' => $user->email, 'password' => 'password123'])
             ->assertRedirect(route('dashboard'));
    }

    public function test_employee_is_redirected_to_employee_dashboard_after_login(): void
    {
        $user = $this->makeUser(['role' => 'employee']);

        $this->post('/login', ['login' => $user->email, 'password' => 'password123'])
             ->assertRedirect(route('employee.dashboard'));
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = $this->makeUser();

        $this->post('/login', ['login' => $user->email, 'password' => 'wrong'])
             ->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    public function test_login_fails_with_nonexistent_email(): void
    {
        $this->post('/login', ['login' => 'nobody@example.com', 'password' => 'password123'])
             ->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    public function test_login_requires_login_field(): void
    {
        $this->post('/login', ['password' => 'password123'])
             ->assertSessionHasErrors('login');
    }

    public function test_login_requires_password_field(): void
    {
        $user = $this->makeUser();

        $this->post('/login', ['login' => $user->email])
             ->assertSessionHasErrors('password');
    }

    // ── Login por código de funcionário ──────────────────────────────────

    public function test_employee_can_login_with_employee_code(): void
    {
        $user     = $this->makeUser(['role' => 'employee']);
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'code'    => 'FUN0099',
        ]);

        $this->post('/login', ['login' => 'FUN0099', 'password' => 'password123'])
             ->assertRedirect(route('employee.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_employee_code_login_is_case_insensitive(): void
    {
        $user     = $this->makeUser(['role' => 'employee']);
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'code'    => 'FUN0099',
        ]);

        $this->post('/login', ['login' => 'fun0099', 'password' => 'password123'])
             ->assertRedirect(route('employee.dashboard'));
    }

    public function test_login_fails_with_nonexistent_employee_code(): void
    {
        $this->post('/login', ['login' => 'FUN9999', 'password' => 'password123'])
             ->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    public function test_login_fails_with_employee_code_without_user_association(): void
    {
        // Funcionário sem user_id associado
        Employee::factory()->create(['code' => 'FUN0001', 'user_id' => null]);

        $this->post('/login', ['login' => 'FUN0001', 'password' => 'password123'])
             ->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    // ── Logout ───────────────────────────────────────────────────────────

    public function test_authenticated_user_can_logout(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
             ->post('/logout')
             ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    // ── Redirect se já autenticado ────────────────────────────────────────

    public function test_authenticated_user_is_redirected_from_login_page(): void
    {
        $user = $this->makeUser(['role' => 'admin']);

        $this->actingAs($user)
             ->get('/login')
             ->assertRedirect(route('dashboard'));
    }

    // ── ForcePasswordChange middleware ────────────────────────────────────

    public function test_user_with_must_change_password_is_redirected(): void
    {
        $user = $this->makeUser(['must_change_password' => true]);

        $this->actingAs($user)
             ->get(route('dashboard'))
             ->assertRedirect(route('password.change'));
    }

    public function test_user_with_must_change_password_can_access_change_password_page(): void
    {
        $user = $this->makeUser(['must_change_password' => true]);

        $this->actingAs($user)
             ->get(route('password.change'))
             ->assertOk();
    }

    public function test_user_can_change_password_and_flag_is_cleared(): void
    {
        $user = $this->makeUser(['must_change_password' => true]);

        $this->actingAs($user)->put(route('password.update'), [
            'current_password'      => 'password123',
            'password'              => 'NovaPassword1!',
            'password_confirmation' => 'NovaPassword1!',
            'forced'                => '1',
        ])->assertRedirect();

        $this->assertFalse($user->fresh()->must_change_password);
    }

    public function test_normal_user_can_access_dashboard(): void
    {
        $user = $this->makeUser(['role' => 'admin']);

        $this->actingAs($user)
             ->get(route('dashboard'))
             ->assertOk();
    }
}
