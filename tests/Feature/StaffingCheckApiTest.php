<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StaffingCheckApiTest extends TestCase
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

    /** Cria um funcionário activo com inscrição na formação dada. */
    private function makeEnrolledEmployee(Training $training, array $enrollOverrides = []): Employee
    {
        $emp = Employee::factory()->create(['status' => 'active']);
        EmployeeTraining::factory()->create(array_merge([
            'employee_id' => $emp->id,
            'training_id' => $training->id,
            'status'      => 'completed',
        ], $enrollOverrides));
        return $emp;
    }

    private function staffingPost(User $user, array $body): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($user)
            ->postJson('/api/v1/staffing-check', $body);
    }

    // ── Autorização ───────────────────────────────────────────────────────
    public function test_guest_cannot_access_staffing_check(): void
    {
        $training = Training::factory()->create();

        $this->postJson('/api/v1/staffing-check', [
            'start_date'  => '2026-08-01',
            'end_date'    => '2026-08-31',
            'requirements' => [['training_id' => $training->id, 'quantity' => 1]],
        ])->assertStatus(401);
    }
    public function test_employee_role_cannot_access_staffing_check(): void
    {
        $user     = $this->employee();
        $training = Training::factory()->create();

        $this->staffingPost($user, [
            'start_date'   => '2026-08-01',
            'end_date'     => '2026-08-31',
            'requirements' => [['training_id' => $training->id, 'quantity' => 1]],
        ])->assertStatus(403);
    }
    public function test_admin_can_access_staffing_check(): void
    {
        $training = Training::factory()->create();

        $this->staffingPost($this->admin(), [
            'start_date'   => '2026-08-01',
            'end_date'     => '2026-08-31',
            'requirements' => [['training_id' => $training->id, 'quantity' => 1]],
        ])->assertOk();
    }
    public function test_hr_can_access_staffing_check(): void
    {
        $training = Training::factory()->create();

        $this->staffingPost($this->hrUser(), [
            'start_date'   => '2026-08-01',
            'end_date'     => '2026-08-31',
            'requirements' => [['training_id' => $training->id, 'quantity' => 1]],
        ])->assertOk();
    }

    // ── Validação ─────────────────────────────────────────────────────────
    public function test_validation_fails_without_start_date(): void
    {
        $training = Training::factory()->create();

        $this->staffingPost($this->admin(), [
            'end_date'     => '2026-08-31',
            'requirements' => [['training_id' => $training->id, 'quantity' => 1]],
        ])->assertStatus(422)->assertJsonValidationErrors(['start_date']);
    }
    public function test_validation_fails_when_end_before_start(): void
    {
        $training = Training::factory()->create();

        $this->staffingPost($this->admin(), [
            'start_date'   => '2026-08-31',
            'end_date'     => '2026-08-01',
            'requirements' => [['training_id' => $training->id, 'quantity' => 1]],
        ])->assertStatus(422)->assertJsonValidationErrors(['end_date']);
    }
    public function test_validation_fails_without_requirements(): void
    {
        $this->staffingPost($this->admin(), [
            'start_date' => '2026-08-01',
            'end_date'   => '2026-08-31',
        ])->assertStatus(422)->assertJsonValidationErrors(['requirements']);
    }
    public function test_validation_fails_with_invalid_training_id(): void
    {
        $this->staffingPost($this->admin(), [
            'start_date'   => '2026-08-01',
            'end_date'     => '2026-08-31',
            'requirements' => [['training_id' => 99999, 'quantity' => 1]],
        ])->assertStatus(422)->assertJsonValidationErrors(['requirements.0.training_id']);
    }

    // ── Cenário: disponibilidade total (status ok) ─────────────────────────
    public function test_returns_ok_when_enough_qualified_technicians(): void
    {
        $training = Training::factory()->create();
        $start    = '2026-09-01';
        $end      = '2026-09-30';

        // 3 técnicos válidos durante toda a obra (expiry depois do fim)
        for ($i = 0; $i < 3; $i++) {
            $this->makeEnrolledEmployee($training, [
                'end_date'       => '2025-01-01',
                'validity_months'=> 36, // expira 2028-01-01 → depois do fim
            ]);
        }

        $res = $this->staffingPost($this->admin(), [
            'start_date'   => $start,
            'end_date'     => $end,
            'requirements' => [['training_id' => $training->id, 'quantity' => 3]],
        ])->assertOk()->json();

        $this->assertEquals('ok', $res['global_status']);
        $this->assertEquals(0,    $res['total_gap']);

        $result = $res['results'][0];
        $this->assertEquals('ok', $result['status']);
        $this->assertEquals(3, $result['available']);
        $this->assertEquals(0, $result['gap']);
    }

    // ── Cenário: técnicos sem validade definida contam como válidos ────────
    public function test_employees_without_expiry_count_as_qualified(): void
    {
        $training = Training::factory()->create();

        // Inscrição sem end_date → expiry_date = null → sempre válido
        $this->makeEnrolledEmployee($training, [
            'end_date'        => null,
            'validity_months' => null,
        ]);

        $res = $this->staffingPost($this->admin(), [
            'start_date'   => '2026-09-01',
            'end_date'     => '2026-09-30',
            'requirements' => [['training_id' => $training->id, 'quantity' => 1]],
        ])->assertOk()->json();

        $result = $res['results'][0];
        $this->assertEquals('ok', $result['status']);
        $this->assertEquals(1, $result['available']);
        $this->assertCount(1, $result['no_expiry']);
    }

    // ── Cenário: gap (insuficiente) ────────────────────────────────────────
    public function test_returns_gap_when_not_enough_technicians(): void
    {
        $training = Training::factory()->create();

        // Apenas 1 técnico válido, mas precisam-se 3
        $this->makeEnrolledEmployee($training, [
            'end_date'        => '2025-01-01',
            'validity_months' => 36,
        ]);

        $res = $this->staffingPost($this->admin(), [
            'start_date'   => '2026-09-01',
            'end_date'     => '2026-09-30',
            'requirements' => [['training_id' => $training->id, 'quantity' => 3]],
        ])->assertOk()->json();

        $this->assertEquals('gap', $res['global_status']);
        $this->assertEquals(2, $res['total_gap']);

        $result = $res['results'][0];
        $this->assertEquals('gap', $result['status']);
        $this->assertEquals(1, $result['available']);
        $this->assertEquals(2, $result['gap']);
    }

    // ── Cenário: warning (expira durante a obra) ───────────────────────────
    public function test_returns_warning_when_certificate_expires_during_project(): void
    {
        $training = Training::factory()->create();
        $start    = '2026-09-01';
        $end      = '2026-09-30';

        // Certificado expira a 15 de Setembro — depois do início mas antes do fim
        $this->makeEnrolledEmployee($training, [
            'end_date'        => '2024-09-15',
            'validity_months' => 24, // expira 2026-09-15 → dentro da obra
        ]);

        $res = $this->staffingPost($this->admin(), [
            'start_date'   => $start,
            'end_date'     => $end,
            'requirements' => [['training_id' => $training->id, 'quantity' => 1]],
        ])->assertOk()->json();

        // 1 técnico disponível (cobre o início), mas aviso de expiração
        $this->assertEquals('warning', $res['global_status']);
        $this->assertEquals(0, $res['total_gap']);

        $result = $res['results'][0];
        $this->assertEquals('warning', $result['status']);
        $this->assertEquals(1, $result['available']); // conta como disponível (válido no início)
        $this->assertEquals(0, $result['gap']);
        $this->assertCount(1, $result['expiring_during']);
    }

    // ── Cenário: certificado expirado antes do início ─────────────────────
    public function test_expired_certificates_before_start_are_not_counted(): void
    {
        $training = Training::factory()->create();

        // Certificado expirou em 2026-07-01 — antes do início (Setembro)
        $this->makeEnrolledEmployee($training, [
            'end_date'        => '2024-07-01',
            'validity_months' => 24, // expira 2026-07-01
        ]);

        $res = $this->staffingPost($this->admin(), [
            'start_date'   => '2026-09-01',
            'end_date'     => '2026-09-30',
            'requirements' => [['training_id' => $training->id, 'quantity' => 1]],
        ])->assertOk()->json();

        $this->assertEquals('gap', $res['global_status']);
        $result = $res['results'][0];
        $this->assertEquals(0, $result['available']);
        $this->assertEquals(1, $result['gap']);
        $this->assertCount(1, $result['expired_before']);
    }

    // ── Cenário: funcionários inactivos não contam ─────────────────────────
    public function test_inactive_employees_are_not_counted(): void
    {
        $training = Training::factory()->create();

        // Funcionário inactivo com certificado válido
        $emp = Employee::factory()->create(['status' => 'inactive']);
        EmployeeTraining::factory()->create([
            'employee_id'     => $emp->id,
            'training_id'     => $training->id,
            'status'          => 'completed',
            'end_date'        => '2025-01-01',
            'validity_months' => 36,
        ]);

        $res = $this->staffingPost($this->admin(), [
            'start_date'   => '2026-09-01',
            'end_date'     => '2026-09-30',
            'requirements' => [['training_id' => $training->id, 'quantity' => 1]],
        ])->assertOk()->json();

        $this->assertEquals('gap', $res['global_status']);
        $this->assertEquals(0, $res['results'][0]['available']);
    }

    // ── Cenário: múltiplas formações ───────────────────────────────────────
    public function test_handles_multiple_training_requirements(): void
    {
        $t1 = Training::factory()->create();
        $t2 = Training::factory()->create();

        // t1: 2 técnicos válidos — necessários 2 → ok
        for ($i = 0; $i < 2; $i++) {
            $this->makeEnrolledEmployee($t1, [
                'end_date' => '2025-01-01', 'validity_months' => 36,
            ]);
        }

        // t2: 0 técnicos → gap de 1
        $res = $this->staffingPost($this->admin(), [
            'start_date'   => '2026-09-01',
            'end_date'     => '2026-09-30',
            'requirements' => [
                ['training_id' => $t1->id, 'quantity' => 2],
                ['training_id' => $t2->id, 'quantity' => 1],
            ],
        ])->assertOk()->json();

        $this->assertEquals('gap', $res['global_status']);
        $this->assertEquals(1, $res['total_gap']);
        $this->assertEquals(2, count($res['results']));

        $r1 = collect($res['results'])->firstWhere('training_id', $t1->id);
        $r2 = collect($res['results'])->firstWhere('training_id', $t2->id);

        $this->assertEquals('ok',  $r1['status']);
        $this->assertEquals('gap', $r2['status']);
        $this->assertEquals(1, $r2['gap']);
    }

    // ── Estrutura da resposta ─────────────────────────────────────────────
    public function test_response_has_expected_structure(): void
    {
        $training = Training::factory()->create();

        $res = $this->staffingPost($this->admin(), [
            'start_date'   => '2026-09-01',
            'end_date'     => '2026-09-30',
            'requirements' => [['training_id' => $training->id, 'quantity' => 1]],
        ])->assertOk()->json();

        $this->assertArrayHasKey('global_status',   $res);
        $this->assertArrayHasKey('total_gap',        $res);
        $this->assertArrayHasKey('start_date',       $res);
        $this->assertArrayHasKey('end_date',         $res);
        $this->assertArrayHasKey('duration_days',    $res);
        $this->assertArrayHasKey('results',          $res);

        $result = $res['results'][0];
        foreach (['training_id','training_title','needed','available','gap','status',
                  'qualified','no_expiry','expiring_during','expired_before','days_until_start'] as $key) {
            $this->assertArrayHasKey($key, $result, "Chave em falta no resultado: $key");
        }
    }
    public function test_duration_days_is_calculated_correctly(): void
    {
        $training = Training::factory()->create();

        $res = $this->staffingPost($this->admin(), [
            'start_date'   => '2026-09-01',
            'end_date'     => '2026-09-10',
            'requirements' => [['training_id' => $training->id, 'quantity' => 1]],
        ])->assertOk()->json();

        $this->assertEquals(10, $res['duration_days']); // inclusivo: 1 a 10 = 10 dias
    }
}
