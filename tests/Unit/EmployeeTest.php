<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    // ── Accessor: full_name ──────────────────────────────────────────────

    public function test_full_name_concatenates_first_and_last_name(): void
    {
        $employee = Employee::factory()->make([
            'first_name' => 'João',
            'last_name'  => 'Silva',
        ]);

        $this->assertSame('João Silva', $employee->full_name);
    }

    public function test_full_name_trims_extra_whitespace(): void
    {
        $employee = Employee::factory()->make([
            'first_name' => 'Maria',
            'last_name'  => '',
        ]);

        $this->assertSame('Maria', $employee->full_name);
    }

    // ── Accessor: profile_photo_url ──────────────────────────────────────

    public function test_profile_photo_url_returns_null_when_no_photo(): void
    {
        $employee = Employee::factory()->make(['profile_photo' => null]);

        $this->assertNull($employee->profile_photo_url);
    }

    public function test_profile_photo_url_returns_asset_url_for_path(): void
    {
        $employee = Employee::factory()->make([
            'profile_photo' => 'employees/photos/abc123.jpg',
        ]);

        $this->assertStringContainsString('employees/photos/abc123.jpg', $employee->profile_photo_url);
        $this->assertStringStartsWith('http', $employee->profile_photo_url);
    }

    public function test_profile_photo_url_returns_raw_for_legacy_base64(): void
    {
        $fakeBase64 = 'data:image/jpeg;base64,' . base64_encode('fake-image-data');
        $employee   = Employee::factory()->make(['profile_photo' => $fakeBase64]);

        $this->assertSame($fakeBase64, $employee->profile_photo_url);
    }

    public function test_profile_photo_url_returns_raw_for_long_string_without_slash(): void
    {
        // Simula base64 puro sem prefixo data:
        $longBase64 = str_repeat('A', 600);
        $employee   = Employee::factory()->make(['profile_photo' => $longBase64]);

        $this->assertSame($longBase64, $employee->profile_photo_url);
    }

    // ── Soft deletes ─────────────────────────────────────────────────────

    public function test_employee_is_soft_deleted(): void
    {
        $employee = Employee::factory()->create();
        $id = $employee->id;

        $employee->delete();

        $this->assertNull(Employee::find($id));
        $this->assertNotNull(Employee::withTrashed()->find($id));
    }

    public function test_soft_deleted_employee_can_be_restored(): void
    {
        $employee = Employee::factory()->create();
        $id = $employee->id;

        $employee->delete();
        Employee::withTrashed()->find($id)->restore();

        $this->assertNotNull(Employee::find($id));
    }

    // ── Relações ─────────────────────────────────────────────────────────

    public function test_employee_belongs_to_position(): void
    {
        $position = Position::factory()->create();
        $employee = Employee::factory()->create(['position_id' => $position->id]);

        $this->assertInstanceOf(Position::class, $employee->position);
        $this->assertSame($position->id, $employee->position->id);
    }

    public function test_employee_belongs_to_department(): void
    {
        $department = Department::factory()->create();
        $employee   = Employee::factory()->create(['department_id' => $department->id]);

        $this->assertInstanceOf(Department::class, $employee->department);
        $this->assertSame($department->id, $employee->department->id);
    }

    public function test_employee_belongs_to_user(): void
    {
        $user     = User::factory()->create();
        $employee = Employee::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $employee->user);
        $this->assertSame($user->id, $employee->user->id);
    }

    // ── Status ───────────────────────────────────────────────────────────

    public function test_employee_factory_active_state_sets_status(): void
    {
        $employee = Employee::factory()->active()->make();

        $this->assertSame('active', $employee->status);
    }

    public function test_employee_factory_full_time_state(): void
    {
        $employee = Employee::factory()->fullTime()->make();

        $this->assertSame('full-time', $employee->contract_type);
        $this->assertNull($employee->end_date);
    }

    // ── Casts de datas ───────────────────────────────────────────────────

    public function test_hire_date_is_cast_to_carbon(): void
    {
        $employee = Employee::factory()->make(['hire_date' => '2020-01-15']);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $employee->hire_date);
        $this->assertSame('2020-01-15', $employee->hire_date->toDateString());
    }
}
