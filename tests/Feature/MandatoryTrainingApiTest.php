<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\MandatoryTraining;
use App\Models\Position;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MandatoryTrainingApiTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ───────────────────────────────────────────────────────────

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin', 'password' => Hash::make('pass'), 'must_change_password' => false,
        ]);
    }

    private function hrUser(): User
    {
        return User::factory()->create([
            'role' => 'hr', 'password' => Hash::make('pass'), 'must_change_password' => false,
        ]);
    }

    private function employee(): User
    {
        return User::factory()->create([
            'role' => 'employee', 'password' => Hash::make('pass'), 'must_change_password' => false,
        ]);
    }

    // ── Autorização ───────────────────────────────────────────────────────

    public function test_guest_cannot_list_mandatory_trainings(): void
    {
        $this->getJson('/api/v1/mandatory-trainings')->assertStatus(401);
    }

    public function test_employee_cannot_list_mandatory_trainings(): void
    {
        $this->actingAs($this->employee())
            ->getJson('/api/v1/mandatory-trainings')
            ->assertStatus(403);
    }

    public function test_admin_can_list_mandatory_trainings(): void
    {
        MandatoryTraining::factory()->create();

        $this->actingAs($this->admin())
            ->getJson('/api/v1/mandatory-trainings')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_hr_can_list_mandatory_trainings(): void
    {
        $this->actingAs($this->hrUser())
            ->getJson('/api/v1/mandatory-trainings')
            ->assertOk();
    }

    // ── Criar ─────────────────────────────────────────────────────────────

    public function test_admin_can_create_rule_for_all(): void
    {
        $training = Training::factory()->create();

        $res = $this->actingAs($this->admin())
            ->postJson('/api/v1/mandatory-trainings', [
                'training_id'   => $training->id,
                'target_type'   => 'all',
                'deadline_days' => 90,
            ])
            ->assertStatus(201)
            ->json('data');

        $this->assertEquals($training->id, $res['training_id']);
        $this->assertEquals('all', $res['target_type']);
        $this->assertEquals(90, $res['deadline_days']);
    }

    public function test_admin_can_create_rule_for_department(): void
    {
        $training   = Training::factory()->create();
        $department = Department::factory()->create();

        $res = $this->actingAs($this->admin())
            ->postJson('/api/v1/mandatory-trainings', [
                'training_id' => $training->id,
                'target_type' => 'department',
                'target_id'   => $department->id,
            ])
            ->assertStatus(201)
            ->json('data');

        $this->assertEquals('department', $res['target_type']);
        $this->assertEquals($department->id, $res['target_id']);
    }

    public function test_admin_can_create_rule_for_position(): void
    {
        $training = Training::factory()->create();
        $position = Position::factory()->create();

        $res = $this->actingAs($this->admin())
            ->postJson('/api/v1/mandatory-trainings', [
                'training_id' => $training->id,
                'target_type' => 'position',
                'target_id'   => $position->id,
            ])
            ->assertStatus(201)
            ->json('data');

        $this->assertEquals('position', $res['target_type']);
    }

    public function test_create_rule_requires_training_id(): void
    {
        $this->actingAs($this->admin())
            ->postJson('/api/v1/mandatory-trainings', ['target_type' => 'all'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['training_id']);
    }

    public function test_create_rule_requires_valid_target_type(): void
    {
        $training = Training::factory()->create();

        $this->actingAs($this->admin())
            ->postJson('/api/v1/mandatory-trainings', [
                'training_id' => $training->id,
                'target_type' => 'invalid',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['target_type']);
    }

    public function test_department_rule_requires_valid_target_id(): void
    {
        $training = Training::factory()->create();

        $this->actingAs($this->admin())
            ->postJson('/api/v1/mandatory-trainings', [
                'training_id' => $training->id,
                'target_type' => 'department',
                'target_id'   => 99999,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['target_id']);
    }

    public function test_duplicate_rule_returns_422(): void
    {
        $training = Training::factory()->create();

        MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'all',
            'target_id'   => null,
        ]);

        $this->actingAs($this->admin())
            ->postJson('/api/v1/mandatory-trainings', [
                'training_id' => $training->id,
                'target_type' => 'all',
            ])
            ->assertStatus(422);
    }

    // ── Actualizar ────────────────────────────────────────────────────────

    public function test_admin_can_update_rule(): void
    {
        $rule = MandatoryTraining::factory()->create(['deadline_days' => null]);

        $res = $this->actingAs($this->admin())
            ->putJson("/api/v1/mandatory-trainings/{$rule->id}", [
                'deadline_days' => 60,
                'notes'         => 'Actualizado nos testes',
            ])
            ->assertOk()
            ->json('data');

        $this->assertEquals(60, $res['deadline_days']);
        $this->assertEquals('Actualizado nos testes', $res['notes']);
    }

    // ── Eliminar ──────────────────────────────────────────────────────────

    public function test_admin_can_delete_rule(): void
    {
        $rule = MandatoryTraining::factory()->create();

        $this->actingAs($this->admin())
            ->deleteJson("/api/v1/mandatory-trainings/{$rule->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('mandatory_trainings', ['id' => $rule->id]);
    }

    public function test_employee_cannot_delete_rule(): void
    {
        $rule = MandatoryTraining::factory()->create();

        $this->actingAs($this->employee())
            ->deleteJson("/api/v1/mandatory-trainings/{$rule->id}")
            ->assertStatus(403);
    }

    // ── Gaps (funcionários em falta) ──────────────────────────────────────

    public function test_gaps_returns_missing_employees_for_all_rule(): void
    {
        $training = Training::factory()->create();
        $rule     = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'all',
        ]);

        // 2 funcionários activos; só 1 tem a formação concluída
        $empDone    = Employee::factory()->create(['status' => 'active']);
        $empMissing = Employee::factory()->create(['status' => 'active']);

        EmployeeTraining::factory()->create([
            'employee_id' => $empDone->id,
            'training_id' => $training->id,
            'status'      => 'completed',
        ]);

        $res = $this->actingAs($this->admin())
            ->getJson("/api/v1/mandatory-trainings/{$rule->id}/gaps")
            ->assertOk()
            ->json();

        $this->assertEquals(2, $res['summary']['total']);
        $this->assertEquals(1, $res['summary']['done']);
        $this->assertEquals(1, $res['summary']['missing']);
        $this->assertEquals(50, $res['summary']['rate']);

        $missingIds = collect($res['data'])->pluck('id')->toArray();
        $this->assertContains($empMissing->id, $missingIds);
        $this->assertNotContains($empDone->id, $missingIds);
    }

    public function test_gaps_for_department_rule_only_affects_department_employees(): void
    {
        $training   = Training::factory()->create();
        $department = Department::factory()->create();
        $rule       = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'department',
            'target_id'   => $department->id,
        ]);

        // Funcionário no departamento alvo (sem formação)
        Employee::factory()->create(['status' => 'active', 'department_id' => $department->id]);
        // Funcionário noutro departamento (não deve aparecer nos gaps)
        $otherDept = Department::factory()->create();
        Employee::factory()->create(['status' => 'active', 'department_id' => $otherDept->id]);

        $res = $this->actingAs($this->admin())
            ->getJson("/api/v1/mandatory-trainings/{$rule->id}/gaps")
            ->assertOk()
            ->json();

        $this->assertEquals(1, $res['summary']['total']);
        $this->assertEquals(1, $res['summary']['missing']);
    }

    public function test_gaps_returns_zero_missing_when_all_done(): void
    {
        $training = Training::factory()->create();
        $rule     = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'all',
        ]);

        $emp = Employee::factory()->create(['status' => 'active']);
        EmployeeTraining::factory()->create([
            'employee_id' => $emp->id,
            'training_id' => $training->id,
            'status'      => 'completed',
        ]);

        $res = $this->actingAs($this->admin())
            ->getJson("/api/v1/mandatory-trainings/{$rule->id}/gaps")
            ->assertOk()
            ->json();

        $this->assertEquals(0, $res['summary']['missing']);
        $this->assertEquals(100, $res['summary']['rate']);
    }

    // ── Compliance (sumário global) ───────────────────────────────────────

    public function test_compliance_returns_all_rules_with_rates(): void
    {
        $training = Training::factory()->create();
        MandatoryTraining::factory()->create(['training_id' => $training->id, 'target_type' => 'all']);

        $res = $this->actingAs($this->admin())
            ->getJson('/api/v1/mandatory-trainings/compliance')
            ->assertOk()
            ->json('data');

        $this->assertCount(1, $res);
        foreach (['id', 'training', 'target_type', 'total', 'done', 'missing', 'rate'] as $key) {
            $this->assertArrayHasKey($key, $res[0], "Chave em falta: $key");
        }
    }

    public function test_employee_cannot_access_compliance(): void
    {
        $this->actingAs($this->employee())
            ->getJson('/api/v1/mandatory-trainings/compliance')
            ->assertStatus(403);
    }
}
