<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'role'                => 'admin',
            'password'            => Hash::make('password'),
            'must_change_password' => false,
        ]);
    }

    private function hrUser(): User
    {
        return User::factory()->create([
            'role'                => 'hr',
            'password'            => Hash::make('password'),
            'must_change_password' => false,
        ]);
    }

    private function employeeUser(): User
    {
        return User::factory()->create([
            'role'                => 'employee',
            'password'            => Hash::make('password'),
            'must_change_password' => false,
        ]);
    }

    // ── Autenticação ──────────────────────────────────────────────────────

    public function test_unauthenticated_request_returns_401(): void
    {
        $this->getJson('/api/v1/employees')->assertUnauthorized();
    }

    public function test_employee_role_cannot_access_employees_api(): void
    {
        $this->actingAs($this->employeeUser())
             ->getJson('/api/v1/employees')
             ->assertForbidden();
    }

    // ── Index ─────────────────────────────────────────────────────────────

    public function test_admin_can_list_employees(): void
    {
        Employee::factory()->count(3)->create();

        $this->actingAs($this->admin())
             ->getJson('/api/v1/employees')
             ->assertOk()
             ->assertJsonStructure(['data', 'meta']);
    }

    public function test_hr_can_list_employees(): void
    {
        Employee::factory()->count(2)->create();

        $this->actingAs($this->hrUser())
             ->getJson('/api/v1/employees')
             ->assertOk();
    }

    public function test_index_filters_by_status(): void
    {
        Employee::factory()->create(['status' => 'active']);
        Employee::factory()->create(['status' => 'inactive']);

        $response = $this->actingAs($this->admin())
                         ->getJson('/api/v1/employees?status=active')
                         ->assertOk();

        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertSame('active', $data[0]['status']);
    }

    public function test_index_filters_by_department(): void
    {
        $dept  = Department::factory()->create();
        $other = Department::factory()->create();

        Employee::factory()->create(['department_id' => $dept->id]);
        Employee::factory()->create(['department_id' => $other->id]);

        $response = $this->actingAs($this->admin())
                         ->getJson("/api/v1/employees?department_id={$dept->id}")
                         ->assertOk();

        $this->assertCount(1, $response->json('data'));
    }

    public function test_index_searches_by_name(): void
    {
        Employee::factory()->create(['first_name' => 'Adalberto', 'last_name' => 'Silva']);
        Employee::factory()->create(['first_name' => 'Maria',     'last_name' => 'Costa']);

        $response = $this->actingAs($this->admin())
                         ->getJson('/api/v1/employees?search=Adalberto')
                         ->assertOk();

        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Adalberto', $response->json('data.0.first_name'));
    }

    public function test_index_searches_by_code(): void
    {
        Employee::factory()->create(['code' => 'FUN0001']);
        Employee::factory()->create(['code' => 'FUN0002']);

        $response = $this->actingAs($this->admin())
                         ->getJson('/api/v1/employees?search=FUN0001')
                         ->assertOk();

        $this->assertCount(1, $response->json('data'));
    }

    public function test_index_paginates_results(): void
    {
        Employee::factory()->count(20)->create();

        $response = $this->actingAs($this->admin())
                         ->getJson('/api/v1/employees?per_page=5')
                         ->assertOk();

        $this->assertCount(5, $response->json('data'));
        $this->assertSame(20, $response->json('meta.total'));
    }

    // ── Show ──────────────────────────────────────────────────────────────

    public function test_admin_can_show_employee(): void
    {
        $employee = Employee::factory()->create();

        $this->actingAs($this->admin())
             ->getJson("/api/v1/employees/{$employee->id}")
             ->assertOk()
             ->assertJsonFragment(['id' => $employee->id]);
    }

    public function test_show_returns_404_for_nonexistent_employee(): void
    {
        $this->actingAs($this->admin())
             ->getJson('/api/v1/employees/99999')
             ->assertNotFound();
    }

    // ── Store ─────────────────────────────────────────────────────────────

    public function test_admin_can_create_employee(): void
    {
        $dept     = Department::factory()->create();
        $position = Position::factory()->create();

        $response = $this->actingAs($this->admin())
             ->postJson('/api/v1/employees', [
                 'code'          => 'FUN0100',
                 'first_name'    => 'João',
                 'last_name'     => 'Teste',
                 'email'         => 'joao.teste@example.com',
                 'department_id' => $dept->id,
                 'position_id'   => $position->id,
                 'status'        => 'active',
                 'hire_date'     => '2024-01-01',
             ])
             ->assertCreated();

        $this->assertDatabaseHas('employees', ['code' => 'FUN0100', 'first_name' => 'João']);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->actingAs($this->admin())
             ->postJson('/api/v1/employees', [])
             ->assertUnprocessable()
             ->assertJsonValidationErrors(['first_name', 'last_name']);
    }

    public function test_store_validates_unique_code(): void
    {
        Employee::factory()->create(['code' => 'FUN0001']);

        $dept     = Department::factory()->create();
        $position = Position::factory()->create();

        $this->actingAs($this->admin())
             ->postJson('/api/v1/employees', [
                 'code'          => 'FUN0001',
                 'first_name'    => 'Outro',
                 'last_name'     => 'Funcionário',
                 'department_id' => $dept->id,
                 'position_id'   => $position->id,
                 'status'        => 'active',
             ])
             ->assertUnprocessable()
             ->assertJsonValidationErrors(['code']);
    }

    // ── Update ────────────────────────────────────────────────────────────

    public function test_admin_can_update_employee(): void
    {
        $employee = Employee::factory()->create(['first_name' => 'Original']);

        $this->actingAs($this->admin())
             ->putJson("/api/v1/employees/{$employee->id}", [
                 'first_name' => 'Actualizado',
                 'last_name'  => $employee->last_name,
                 'status'     => $employee->status,
             ])
             ->assertOk();

        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'first_name' => 'Actualizado']);
    }

    public function test_update_can_associate_user_id(): void
    {
        $employee = Employee::factory()->create(['user_id' => null]);
        $user     = $this->employeeUser();

        $this->actingAs($this->admin())
             ->putJson("/api/v1/employees/{$employee->id}", [
                 'first_name' => $employee->first_name,
                 'last_name'  => $employee->last_name,
                 'status'     => $employee->status,
                 'user_id'    => $user->id,
             ])
             ->assertOk();

        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'user_id' => $user->id]);
    }

    public function test_update_prevents_duplicate_user_association(): void
    {
        $user = $this->employeeUser();
        Employee::factory()->create(['user_id' => $user->id]);
        $other = Employee::factory()->create(['user_id' => null]);

        $this->actingAs($this->admin())
             ->putJson("/api/v1/employees/{$other->id}", [
                 'first_name' => $other->first_name,
                 'last_name'  => $other->last_name,
                 'status'     => $other->status,
                 'user_id'    => $user->id,
             ])
             ->assertUnprocessable()
             ->assertJsonValidationErrors(['user_id']);
    }

    // ── Destroy ───────────────────────────────────────────────────────────

    public function test_admin_can_soft_delete_employee(): void
    {
        $employee = Employee::factory()->create();

        $this->actingAs($this->admin())
             ->deleteJson("/api/v1/employees/{$employee->id}")
             ->assertNoContent();

        $this->assertSoftDeleted('employees', ['id' => $employee->id]);
    }

    // ── Bulk create users ─────────────────────────────────────────────────

    public function test_bulk_create_users_creates_accounts_for_employees_without_user(): void
    {
        // Funcionário com email mas sem conta
        Employee::factory()->create([
            'email'   => 'novo@example.com',
            'user_id' => null,
            'status'  => 'active',
        ]);

        $response = $this->actingAs($this->admin())
             ->postJson('/api/v1/employees/bulk-create-users')
             ->assertOk();

        $this->assertGreaterThanOrEqual(1, $response->json('created'));
        $this->assertDatabaseHas('users', ['email' => 'novo@example.com']);
    }

    public function test_employee_role_cannot_bulk_create_users(): void
    {
        $this->actingAs($this->employeeUser())
             ->postJson('/api/v1/employees/bulk-create-users')
             ->assertForbidden();
    }
}
