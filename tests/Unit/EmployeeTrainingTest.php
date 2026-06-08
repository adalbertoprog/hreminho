<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Training;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class EmployeeTrainingTest extends TestCase
{
    use RefreshDatabase;

    // ── Accessor: expiry_date ────────────────────────────────────────────

    public function test_expiry_date_is_null_when_end_date_missing(): void
    {
        $et = EmployeeTraining::factory()->make([
            'end_date'        => null,
            'validity_months' => 12,
        ]);

        $this->assertNull($et->expiry_date);
    }

    public function test_expiry_date_is_null_when_validity_months_missing(): void
    {
        $et = EmployeeTraining::factory()->make([
            'end_date'        => '2024-01-01',
            'validity_months' => null,
        ]);

        $this->assertNull($et->expiry_date);
    }

    public function test_expiry_date_is_calculated_correctly(): void
    {
        $et = EmployeeTraining::factory()->make([
            'end_date'        => '2024-01-15',
            'validity_months' => 12,
        ]);

        $this->assertInstanceOf(Carbon::class, $et->expiry_date);
        $this->assertSame('2025-01-15', $et->expiry_date->toDateString());
    }

    public function test_expiry_date_handles_6_months(): void
    {
        $et = EmployeeTraining::factory()->make([
            'end_date'        => '2024-06-01',
            'validity_months' => 6,
        ]);

        $this->assertSame('2024-12-01', $et->expiry_date->toDateString());
    }

    // ── Accessor: validity_status ────────────────────────────────────────

    public function test_validity_status_is_null_without_expiry(): void
    {
        $et = EmployeeTraining::factory()->make([
            'end_date'        => null,
            'validity_months' => null,
        ]);

        $this->assertNull($et->validity_status);
    }

    public function test_validity_status_is_expired_when_past_expiry(): void
    {
        Carbon::setTestNow('2025-06-01');

        $et = EmployeeTraining::factory()->make([
            'end_date'        => '2023-01-01',
            'validity_months' => 12,
            // expiry = 2024-01-01 — já passou
        ]);

        $this->assertSame('expired', $et->validity_status);

        Carbon::setTestNow();
    }

    public function test_validity_status_is_expiring_within_30_days(): void
    {
        Carbon::setTestNow('2025-06-01');

        $et = EmployeeTraining::factory()->make([
            'end_date'        => '2024-05-20',
            'validity_months' => 12,
            // expiry = 2025-05-20 → já passou? não: 2025-06-20 (12 meses a partir de 2024-06-01 seria errado)
            // Vamos usar end_date tal que expiry seja 2025-06-20 (19 dias a partir de hoje)
        ]);

        // end_date = 2024-06-20, validity = 12 → expiry = 2025-06-20 (19 dias à frente)
        $et2 = EmployeeTraining::factory()->make([
            'end_date'        => '2024-06-20',
            'validity_months' => 12,
        ]);

        $this->assertSame('expiring', $et2->validity_status);

        Carbon::setTestNow();
    }

    public function test_validity_status_is_valid_when_more_than_30_days(): void
    {
        Carbon::setTestNow('2025-01-01');

        $et = EmployeeTraining::factory()->make([
            'end_date'        => '2025-01-01',
            'validity_months' => 12,
            // expiry = 2026-01-01 → 365 dias → valid
        ]);

        $this->assertSame('valid', $et->validity_status);

        Carbon::setTestNow();
    }

    // ── Relações ─────────────────────────────────────────────────────────

    public function test_employee_training_belongs_to_employee(): void
    {
        $employee = Employee::factory()->create();
        $training = Training::factory()->create();
        $et = EmployeeTraining::factory()->create([
            'employee_id' => $employee->id,
            'training_id' => $training->id,
        ]);

        $this->assertInstanceOf(Employee::class, $et->employee);
        $this->assertSame($employee->id, $et->employee->id);
    }

    public function test_employee_training_belongs_to_training(): void
    {
        $training = Training::factory()->create();
        $et = EmployeeTraining::factory()->create(['training_id' => $training->id]);

        $this->assertInstanceOf(Training::class, $et->training);
        $this->assertSame($training->id, $et->training->id);
    }

    // ── Casts ────────────────────────────────────────────────────────────

    public function test_score_is_cast_to_decimal(): void
    {
        $et = EmployeeTraining::factory()->make(['score' => '85.50']);

        // Cast decimal:2 devolve string em Laravel
        $this->assertSame('85.50', $et->score);
    }

    public function test_validity_months_is_cast_to_integer(): void
    {
        $et = EmployeeTraining::factory()->make(['validity_months' => '6']);

        $this->assertIsInt($et->validity_months);
    }

    // ── Factory states ───────────────────────────────────────────────────

    public function test_completed_factory_state_sets_correct_fields(): void
    {
        $et = EmployeeTraining::factory()->completed()->make();

        $this->assertSame('completed', $et->status);
        $this->assertNotNull($et->certificate_path);
        $this->assertNotNull($et->score);
    }
}
