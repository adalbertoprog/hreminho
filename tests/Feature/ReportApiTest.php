<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\MandatoryTraining;
use App\Models\Training;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReportApiTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin', 'password' => Hash::make('pass'), 'must_change_password' => false,
        ]);
    }

    private function employeeUser(): User
    {
        return User::factory()->create([
            'role' => 'employee', 'password' => Hash::make('pass'), 'must_change_password' => false,
        ]);
    }

    // ── Autorização geral ─────────────────────────────────────────────────

    public function test_employee_cannot_access_reports(): void
    {
        $this->actingAs($this->employeeUser())
             ->getJson('/api/v1/reports/completed-trainings')
             ->assertForbidden();
    }

    public function test_unauthenticated_cannot_access_reports(): void
    {
        $this->getJson('/api/v1/reports/completed-trainings')
             ->assertUnauthorized();
    }

    // ── Relatório: formações concluídas ───────────────────────────────────

    public function test_completed_trainings_report_returns_ok(): void
    {
        EmployeeTraining::factory()->create(['status' => 'completed', 'score' => 80]);

        $this->actingAs($this->admin())
             ->getJson('/api/v1/reports/completed-trainings')
             ->assertOk()
             ->assertJsonStructure(['data']);
    }

    public function test_completed_trainings_only_includes_completed_status(): void
    {
        EmployeeTraining::factory()->create(['status' => 'completed']);
        EmployeeTraining::factory()->create(['status' => 'enrolled']);

        $response = $this->actingAs($this->admin())
             ->getJson('/api/v1/reports/completed-trainings')
             ->assertOk();

        // O endpoint filtra por status=completed; o 'enrolled' não deve aparecer.
        // A resposta não devolve o campo 'status', mas deve ter exactamente 1 registo.
        $this->assertCount(1, $response->json('data'));
    }

    // ── Relatório: funcionários com formações ─────────────────────────────

    public function test_employees_trainings_report_returns_ok(): void
    {
        $this->actingAs($this->admin())
             ->getJson('/api/v1/reports/employees-trainings')
             ->assertOk();
    }

    // ── Relatório: formações com funcionários ─────────────────────────────

    public function test_training_employees_report_returns_ok(): void
    {
        $this->actingAs($this->admin())
             ->getJson('/api/v1/reports/training-employees')
             ->assertOk();
    }

    // ── Relatório: presenças ──────────────────────────────────────────────

    public function test_attendance_report_returns_ok(): void
    {
        $this->actingAs($this->admin())
             ->getJson('/api/v1/reports/attendance')
             ->assertOk();
    }

    // ── Relatório: validade de certificados ───────────────────────────────

    public function test_validity_report_returns_ok(): void
    {
        $this->actingAs($this->admin())
             ->getJson('/api/v1/reports/validity')
             ->assertOk();
    }

    // ── Relatório: lacunas ────────────────────────────────────────────────

    public function test_gap_analysis_returns_expected_keys(): void
    {
        $response = $this->actingAs($this->admin())
             ->getJson('/api/v1/reports/gaps')
             ->assertOk();

        $response->assertJsonStructure([
            'mandatory_gaps',
            'expired_certificates',
            'plan_gaps',
        ]);
    }

    public function test_gap_analysis_no_training_key_removed(): void
    {
        $response = $this->actingAs($this->admin())
             ->getJson('/api/v1/reports/gaps')
             ->assertOk();

        // Confirmar que a chave no_training foi removida
        $this->assertArrayNotHasKey('no_training', $response->json());
    }

    // ── Mandatory Trainings ───────────────────────────────────────────────

    public function test_admin_can_list_mandatory_trainings(): void
    {
        MandatoryTraining::factory()->count(2)->create();

        $this->actingAs($this->admin())
             ->getJson('/api/v1/mandatory-trainings')
             ->assertOk()
             ->assertJsonStructure(['data']);
    }

    public function test_admin_can_create_mandatory_training(): void
    {
        $training = Training::factory()->create();

        $this->actingAs($this->admin())
             ->postJson('/api/v1/mandatory-trainings', [
                 'training_id' => $training->id,
                 'target_type' => 'all',
             ])
             ->assertCreated();

        $this->assertDatabaseHas('mandatory_trainings', [
            'training_id' => $training->id,
            'target_type' => 'all',
        ]);
    }

    public function test_admin_can_delete_mandatory_training(): void
    {
        $rule = MandatoryTraining::factory()->create();

        $this->actingAs($this->admin())
             ->deleteJson("/api/v1/mandatory-trainings/{$rule->id}")
             ->assertNoContent();

        $this->assertDatabaseMissing('mandatory_trainings', ['id' => $rule->id]);
    }

    public function test_compliance_endpoint_returns_ok(): void
    {
        MandatoryTraining::factory()->create();

        $this->actingAs($this->admin())
             ->getJson('/api/v1/mandatory-trainings/compliance')
             ->assertOk();
    }

    public function test_gaps_endpoint_returns_employees_missing_training(): void
    {
        $training = Training::factory()->create();
        $rule     = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'all',
        ]);

        // Funcionário activo sem inscrição nesta formação
        Employee::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->admin())
             ->getJson("/api/v1/mandatory-trainings/{$rule->id}/gaps")
             ->assertOk();

        $this->assertGreaterThan(0, count($response->json('data')));
    }

    public function test_gaps_endpoint_excludes_enrolled_employees(): void
    {
        $training = Training::factory()->create();
        $rule     = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'all',
        ]);

        $employee = Employee::factory()->create(['status' => 'active']);
        EmployeeTraining::factory()->create([
            'employee_id' => $employee->id,
            'training_id' => $training->id,
            'status'      => 'completed',
        ]);

        $response = $this->actingAs($this->admin())
             ->getJson("/api/v1/mandatory-trainings/{$rule->id}/gaps")
             ->assertOk();

        $ids = collect($response->json('data'))->pluck('id');
        $this->assertNotContains($employee->id, $ids);
    }

    public function test_employee_cannot_access_mandatory_trainings(): void
    {
        $this->actingAs($this->employeeUser())
             ->getJson('/api/v1/mandatory-trainings')
             ->assertForbidden();
    }

    // ── Training Sessions ─────────────────────────────────────────────────

    public function test_admin_can_list_training_sessions(): void
    {
        TrainingSession::factory()->count(3)->create();

        $this->actingAs($this->admin())
             ->getJson('/api/v1/training-sessions')
             ->assertOk()
             ->assertJsonStructure(['data']);
    }

    public function test_annual_summary_returns_monthly_data(): void
    {
        $year = now()->year;

        $response = $this->actingAs($this->admin())
             ->getJson("/api/v1/training-sessions/annual-summary?year={$year}")
             ->assertOk();

        // Deve ter 12 meses
        $this->assertCount(12, $response->json('by_month'));
    }

    public function test_admin_can_create_training_session(): void
    {
        $training = Training::factory()->create();

        $this->actingAs($this->admin())
             ->postJson('/api/v1/training-sessions', [
                 'training_id' => $training->id,
                 'planned_date' => now()->addMonth()->toDateString(),
                 'status'      => 'planned',
             ])
             ->assertCreated();

        $this->assertDatabaseHas('training_sessions', ['training_id' => $training->id]);
    }

    public function test_admin_can_delete_training_session(): void
    {
        $session = TrainingSession::factory()->create();

        $this->actingAs($this->admin())
             ->deleteJson("/api/v1/training-sessions/{$session->id}")
             ->assertNoContent();

        $this->assertDatabaseMissing('training_sessions', ['id' => $session->id]);
    }
}
