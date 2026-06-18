<?php

namespace Tests\Feature;

use App\Models\Training;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TrainingSessionApiTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ───────────────────────────────────────────────────────────

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin', 'password' => Hash::make('pass'), 'must_change_password' => false,
        ]);
    }

    private function employee(): User
    {
        return User::factory()->create([
            'role' => 'employee', 'password' => Hash::make('pass'), 'must_change_password' => false,
        ]);
    }

    private function makeSession(array $attrs = []): TrainingSession
    {
        return TrainingSession::factory()->create(array_merge([
            'planned_date' => '2026-09-15',
            'status'       => 'planned',
        ], $attrs));
    }

    // ── Autorização ───────────────────────────────────────────────────────

    public function test_guest_cannot_list_sessions(): void
    {
        $this->getJson('/api/v1/training-sessions')->assertStatus(401);
    }

    public function test_employee_cannot_list_sessions(): void
    {
        $this->actingAs($this->employee())
            ->getJson('/api/v1/training-sessions')
            ->assertStatus(403);
    }

    public function test_admin_can_list_sessions(): void
    {
        $this->makeSession();

        $this->actingAs($this->admin())
            ->getJson('/api/v1/training-sessions')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    // ── Filtros ───────────────────────────────────────────────────────────

    public function test_sessions_can_be_filtered_by_year(): void
    {
        $this->makeSession(['planned_date' => '2025-06-01']);
        $this->makeSession(['planned_date' => '2026-06-01']);

        $res = $this->actingAs($this->admin())
            ->getJson('/api/v1/training-sessions?year=2025')
            ->assertOk()
            ->json('data');

        $this->assertCount(1, $res);
        $this->assertEquals(2025, $res[0]['year']);
    }

    public function test_sessions_can_be_filtered_by_status(): void
    {
        $this->makeSession(['status' => 'planned',   'planned_date' => '2026-07-01']);
        $this->makeSession(['status' => 'completed',  'planned_date' => '2026-08-01']);

        $res = $this->actingAs($this->admin())
            ->getJson('/api/v1/training-sessions?status=completed')
            ->assertOk()
            ->json('data');

        $this->assertCount(1, $res);
        $this->assertEquals('completed', $res[0]['status']);
    }

    // ── Criar ─────────────────────────────────────────────────────────────

    public function test_admin_can_create_session(): void
    {
        $training = Training::factory()->create();

        $res = $this->actingAs($this->admin())
            ->postJson('/api/v1/training-sessions', [
                'training_id'  => $training->id,
                'planned_date' => '2026-10-01',
                'location'     => 'Sala A',
                'status'       => 'planned',
            ])
            ->assertStatus(201)
            ->json('data');

        $this->assertEquals($training->id, $res['training_id']);
        $this->assertEquals('2026-10-01', $res['planned_date']);
        $this->assertEquals('Sala A', $res['location']);
        $this->assertDatabaseHas('training_sessions', ['training_id' => $training->id, 'location' => 'Sala A']);
    }

    public function test_create_session_requires_training_id(): void
    {
        $this->actingAs($this->admin())
            ->postJson('/api/v1/training-sessions', ['planned_date' => '2026-10-01'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['training_id']);
    }

    public function test_create_session_requires_planned_date(): void
    {
        $training = Training::factory()->create();

        $this->actingAs($this->admin())
            ->postJson('/api/v1/training-sessions', ['training_id' => $training->id])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['planned_date']);
    }

    public function test_end_date_must_be_after_start_date(): void
    {
        $training = Training::factory()->create();

        $this->actingAs($this->admin())
            ->postJson('/api/v1/training-sessions', [
                'training_id'      => $training->id,
                'planned_date'     => '2026-10-10',
                'planned_end_date' => '2026-10-05', // antes do início
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['planned_end_date']);
    }

    public function test_session_with_end_date_calculates_duration_days(): void
    {
        $training = Training::factory()->create();

        $res = $this->actingAs($this->admin())
            ->postJson('/api/v1/training-sessions', [
                'training_id'      => $training->id,
                'planned_date'     => '2026-10-01',
                'planned_end_date' => '2026-10-05',
            ])
            ->assertStatus(201)
            ->json('data');

        $this->assertEquals(5, $res['duration_days']);
    }

    public function test_session_calculates_estimated_total(): void
    {
        $training = Training::factory()->create();

        $res = $this->actingAs($this->admin())
            ->postJson('/api/v1/training-sessions', [
                'training_id'            => $training->id,
                'planned_date'           => '2026-10-01',
                'cost_per_person'        => 50.00,
                'estimated_participants' => 4,
            ])
            ->assertStatus(201)
            ->json('data');

        $this->assertEquals(200.0, $res['estimated_total']);
    }

    // ── Actualizar ────────────────────────────────────────────────────────

    public function test_admin_can_update_session(): void
    {
        $session = $this->makeSession();

        $res = $this->actingAs($this->admin())
            ->putJson("/api/v1/training-sessions/{$session->id}", [
                'status'   => 'completed',
                'location' => 'Sala B',
            ])
            ->assertOk()
            ->json('data');

        $this->assertEquals('completed', $res['status']);
        $this->assertEquals('Sala B', $res['location']);
    }

    public function test_update_session_validates_status(): void
    {
        $session = $this->makeSession();

        $this->actingAs($this->admin())
            ->putJson("/api/v1/training-sessions/{$session->id}", ['status' => 'invalid'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    // ── Eliminar ──────────────────────────────────────────────────────────

    public function test_admin_can_delete_session(): void
    {
        $session = $this->makeSession();

        $this->actingAs($this->admin())
            ->deleteJson("/api/v1/training-sessions/{$session->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('training_sessions', ['id' => $session->id]);
    }

    public function test_employee_cannot_delete_session(): void
    {
        $session = $this->makeSession();

        $this->actingAs($this->employee())
            ->deleteJson("/api/v1/training-sessions/{$session->id}")
            ->assertStatus(403);
    }

    // ── Sumário anual ─────────────────────────────────────────────────────

    public function test_annual_summary_returns_12_months(): void
    {
        $this->makeSession(['planned_date' => '2026-03-10']);
        $this->makeSession(['planned_date' => '2026-07-20']);

        $res = $this->actingAs($this->admin())
            ->getJson('/api/v1/training-sessions/annual-summary?year=2026')
            ->assertOk()
            ->json();

        $this->assertEquals(2026, $res['year']);
        $this->assertCount(12, $res['by_month']);
        $this->assertEquals(2, $res['total']);
    }

    public function test_annual_summary_counts_by_status(): void
    {
        $this->makeSession(['planned_date' => '2026-05-01', 'status' => 'planned']);
        $this->makeSession(['planned_date' => '2026-05-15', 'status' => 'completed']);
        $this->makeSession(['planned_date' => '2026-05-20', 'status' => 'cancelled']);

        $res = $this->actingAs($this->admin())
            ->getJson('/api/v1/training-sessions/annual-summary?year=2026')
            ->assertOk()
            ->json('by_status');

        $this->assertEquals(1, $res['planned']);
        $this->assertEquals(1, $res['completed']);
        $this->assertEquals(1, $res['cancelled']);
    }

    // ── Estrutura da resposta ─────────────────────────────────────────────

    public function test_response_has_expected_structure(): void
    {
        $this->makeSession();

        $data = $this->actingAs($this->admin())
            ->getJson('/api/v1/training-sessions')
            ->assertOk()
            ->json('data.0');

        foreach (['id', 'training_id', 'planned_date', 'status', 'enrolled_count', 'completed_count'] as $key) {
            $this->assertArrayHasKey($key, $data, "Chave em falta: $key");
        }
    }
}
