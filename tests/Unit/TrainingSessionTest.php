<?php

namespace Tests\Unit;

use App\Models\Training;
use App\Models\TrainingSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TrainingSessionTest extends TestCase
{
    use RefreshDatabase;

    // ── Accessor: duration_days ──────────────────────────────────────────

    public function test_duration_days_is_one_when_no_end_date(): void
    {
        $session = TrainingSession::factory()->make([
            'planned_date'     => '2025-06-10',
            'planned_end_date' => null,
        ]);

        $this->assertSame(1, $session->duration_days);
    }

    public function test_duration_days_calculates_correctly(): void
    {
        $session = TrainingSession::factory()->make([
            'planned_date'     => '2025-06-10',
            'planned_end_date' => '2025-06-12',
        ]);

        $this->assertSame(3, $session->duration_days);
    }

    public function test_duration_days_is_one_for_same_day_session(): void
    {
        $session = TrainingSession::factory()->make([
            'planned_date'     => '2025-06-10',
            'planned_end_date' => '2025-06-10',
        ]);

        $this->assertSame(1, $session->duration_days);
    }

    // ── Accessor: computed_status ────────────────────────────────────────

    public function test_computed_status_is_cancelled_regardless_of_dates(): void
    {
        Carbon::setTestNow('2025-06-01');

        $session = TrainingSession::factory()->make([
            'status'       => 'cancelled',
            'planned_date' => '2025-05-01', // no passado
        ]);

        $this->assertSame('cancelled', $session->computed_status);

        Carbon::setTestNow();
    }

    public function test_computed_status_is_planned_when_future_date(): void
    {
        Carbon::setTestNow('2025-06-01');

        $session = TrainingSession::factory()->make([
            'status'           => 'planned',
            'planned_date'     => '2025-07-01',
            'planned_end_date' => null,
        ]);

        $this->assertSame('planned', $session->computed_status);

        Carbon::setTestNow();
    }

    public function test_computed_status_is_completed_when_end_date_is_past(): void
    {
        Carbon::setTestNow('2025-06-10');

        $session = TrainingSession::factory()->make([
            'status'           => 'planned',
            'planned_date'     => '2025-05-01',
            'planned_end_date' => '2025-05-03',
        ]);

        $this->assertSame('completed', $session->computed_status);

        Carbon::setTestNow();
    }

    public function test_computed_status_is_ongoing_when_started_but_not_ended(): void
    {
        Carbon::setTestNow('2025-06-05');

        $session = TrainingSession::factory()->make([
            'status'           => 'planned',
            'planned_date'     => '2025-06-04',
            'planned_end_date' => '2025-06-10',
        ]);

        $this->assertSame('ongoing', $session->computed_status);

        Carbon::setTestNow();
    }

    public function test_computed_status_is_ongoing_when_no_end_date_and_started(): void
    {
        Carbon::setTestNow('2025-06-05');

        // sem planned_end_date e planned_date no passado → ongoing
        $session = TrainingSession::factory()->make([
            'status'           => 'planned',
            'planned_date'     => '2025-06-04',
            'planned_end_date' => null,
        ]);

        $this->assertSame('ongoing', $session->computed_status);

        Carbon::setTestNow();
    }

    // ── Relação ──────────────────────────────────────────────────────────

    public function test_training_session_belongs_to_training(): void
    {
        $training = Training::factory()->create();
        $session  = TrainingSession::factory()->create(['training_id' => $training->id]);

        $this->assertInstanceOf(Training::class, $session->training);
        $this->assertSame($training->id, $session->training->id);
    }

    // ── Campos financeiros ───────────────────────────────────────────────

    public function test_cost_per_person_is_stored_and_retrieved(): void
    {
        $session = TrainingSession::factory()->create(['cost_per_person' => '125.50']);

        $fresh = TrainingSession::find($session->id);

        // cast decimal:2 devolve string
        $this->assertSame('125.50', $fresh->cost_per_person);
    }

    public function test_estimated_participants_is_integer(): void
    {
        $session = TrainingSession::factory()->create(['estimated_participants' => 20]);

        $this->assertIsInt($session->estimated_participants);
        $this->assertSame(20, $session->estimated_participants);
    }

    // ── Factory state: cancelled ─────────────────────────────────────────

    public function test_cancelled_factory_state(): void
    {
        $session = TrainingSession::factory()->cancelled()->make();

        $this->assertSame('cancelled', $session->status);
    }
}
