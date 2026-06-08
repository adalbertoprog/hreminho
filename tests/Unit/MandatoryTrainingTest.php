<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\MandatoryTraining;
use App\Models\Position;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MandatoryTrainingTest extends TestCase
{
    use RefreshDatabase;

    // ── affectedEmployeeIds — target_type: all ───────────────────────────

    public function test_affected_employee_ids_returns_all_active_employees_when_target_is_all(): void
    {
        $training = Training::factory()->create();

        Employee::factory()->active()->count(3)->create();
        Employee::factory()->create(['status' => 'inactive']); // não deve aparecer

        $rule = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'all',
            'target_id'   => null,
        ]);

        $ids = $rule->affectedEmployeeIds();

        $this->assertCount(3, $ids);
    }

    // ── affectedEmployeeIds — target_type: department ────────────────────

    public function test_affected_employee_ids_filters_by_department(): void
    {
        $training   = Training::factory()->create();
        $department = Department::factory()->create();
        $other      = Department::factory()->create();

        Employee::factory()->active()->count(2)->create(['department_id' => $department->id]);
        Employee::factory()->active()->create(['department_id' => $other->id]); // não deve aparecer

        $rule = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'department',
            'target_id'   => $department->id,
        ]);

        $ids = $rule->affectedEmployeeIds();

        $this->assertCount(2, $ids);
    }

    // ── affectedEmployeeIds — target_type: position ──────────────────────

    public function test_affected_employee_ids_filters_by_position(): void
    {
        $training = Training::factory()->create();
        $position = Position::factory()->create();
        $other    = Position::factory()->create();

        Employee::factory()->active()->count(2)->create(['position_id' => $position->id]);
        Employee::factory()->active()->create(['position_id' => $other->id]);

        $rule = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'position',
            'target_id'   => $position->id,
        ]);

        $ids = $rule->affectedEmployeeIds();

        $this->assertCount(2, $ids);
    }

    // ── doneEmployeeIds — via inscrição formal ───────────────────────────

    public function test_done_employee_ids_counts_enrolled_and_completed(): void
    {
        $training = Training::factory()->create();
        $employees = Employee::factory()->active()->count(3)->create();

        // enrolled → conta
        EmployeeTraining::factory()->create([
            'employee_id' => $employees[0]->id,
            'training_id' => $training->id,
            'status'      => 'enrolled',
        ]);

        // completed → conta
        EmployeeTraining::factory()->create([
            'employee_id' => $employees[1]->id,
            'training_id' => $training->id,
            'status'      => 'completed',
        ]);

        // failed → não conta
        EmployeeTraining::factory()->create([
            'employee_id' => $employees[2]->id,
            'training_id' => $training->id,
            'status'      => 'failed',
        ]);

        $rule = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'all',
            'target_id'   => null,
        ]);

        $affectedIds = $rule->affectedEmployeeIds();
        $doneIds     = $rule->doneEmployeeIds($affectedIds);

        $this->assertCount(2, $doneIds);
        $this->assertTrue($doneIds->contains($employees[0]->id));
        $this->assertTrue($doneIds->contains($employees[1]->id));
        $this->assertFalse($doneIds->contains($employees[2]->id));
    }

    // ── doneEmployeeIds — via quiz aprovado ──────────────────────────────

    public function test_done_employee_ids_counts_via_approved_quiz_attempt(): void
    {
        $training = Training::factory()->create();
        $quiz     = Quiz::factory()->create(['training_id' => $training->id]);

        $user     = User::factory()->create();
        $employee = Employee::factory()->active()->create(['user_id' => $user->id]);

        // Tentativa aprovada
        QuizAttempt::factory()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'passed'  => true,
            'score'   => 85,
        ]);

        $rule = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'all',
            'target_id'   => null,
        ]);

        $affectedIds = $rule->affectedEmployeeIds();
        $doneIds     = $rule->doneEmployeeIds($affectedIds);

        $this->assertTrue($doneIds->contains($employee->id));
    }

    public function test_done_employee_ids_does_not_count_failed_quiz_attempt(): void
    {
        $training = Training::factory()->create();
        $quiz     = Quiz::factory()->create(['training_id' => $training->id]);

        $user     = User::factory()->create();
        $employee = Employee::factory()->active()->create(['user_id' => $user->id]);

        QuizAttempt::factory()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'passed'  => false,
            'score'   => 40,
        ]);

        $rule = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'all',
            'target_id'   => null,
        ]);

        $affectedIds = $rule->affectedEmployeeIds();
        $doneIds     = $rule->doneEmployeeIds($affectedIds);

        $this->assertFalse($doneIds->contains($employee->id));
    }

    // ── getTargetNameAttribute ───────────────────────────────────────────

    public function test_target_name_is_all_employees_when_target_type_is_all(): void
    {
        $rule = MandatoryTraining::factory()->make([
            'target_type' => 'all',
            'target_id'   => null,
        ]);

        $this->assertSame('Todos os funcionários', $rule->target_name);
    }

    public function test_target_name_returns_department_name(): void
    {
        $department = Department::factory()->create(['department' => 'Recursos Humanos']);
        $training   = Training::factory()->create();

        $rule = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'department',
            'target_id'   => $department->id,
        ]);

        $this->assertSame('Recursos Humanos', $rule->target_name);
    }

    public function test_target_name_returns_position_name(): void
    {
        $position = Position::factory()->create(['position' => 'Técnico de Segurança']);
        $training = Training::factory()->create();

        $rule = MandatoryTraining::factory()->create([
            'training_id' => $training->id,
            'target_type' => 'position',
            'target_id'   => $position->id,
        ]);

        $this->assertSame('Técnico de Segurança', $rule->target_name);
    }
}
