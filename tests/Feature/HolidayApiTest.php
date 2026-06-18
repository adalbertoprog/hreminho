<?php

namespace Tests\Feature;

use App\Models\Holiday;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HolidayApiTest extends TestCase
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

    private function makeHoliday(array $attrs = []): Holiday
    {
        return Holiday::create(array_merge([
            'name'           => 'Feriado de Teste',
            'date'           => '2026-12-25',
            'type'           => 'national',
            'repeats_yearly' => true,
        ], $attrs));
    }

    // ── Autorização ───────────────────────────────────────────────────────

    public function test_guest_cannot_access_holidays(): void
    {
        $this->getJson('/api/v1/holidays')->assertStatus(401);
    }

    public function test_employee_cannot_access_holidays(): void
    {
        $this->actingAs($this->employee())
            ->getJson('/api/v1/holidays')
            ->assertStatus(403);
    }

    public function test_admin_can_list_holidays(): void
    {
        $this->makeHoliday();

        $this->actingAs($this->admin())
            ->getJson('/api/v1/holidays')
            ->assertOk()
            ->assertJsonStructure(['data' => [['id', 'name', 'date', 'type', 'repeats_yearly']]]);
    }

    public function test_hr_can_list_holidays(): void
    {
        $this->actingAs($this->hrUser())
            ->getJson('/api/v1/holidays')
            ->assertOk();
    }

    // ── Filtros ───────────────────────────────────────────────────────────

    public function test_holidays_can_be_filtered_by_year(): void
    {
        $this->makeHoliday(['date' => '2025-01-01']);
        $this->makeHoliday(['date' => '2026-01-01']);

        $res = $this->actingAs($this->admin())
            ->getJson('/api/v1/holidays?year=2025')
            ->assertOk()
            ->json('data');

        $this->assertCount(1, $res);
        $this->assertStringStartsWith('2025', $res[0]['date']);
    }

    public function test_holidays_can_be_filtered_by_type(): void
    {
        $this->makeHoliday(['type' => 'national', 'date' => '2026-01-01']);
        $this->makeHoliday(['type' => 'local',    'date' => '2026-02-01']);

        $res = $this->actingAs($this->admin())
            ->getJson('/api/v1/holidays?type=local')
            ->assertOk()
            ->json('data');

        $this->assertCount(1, $res);
        $this->assertEquals('local', $res[0]['type']);
    }

    // ── Criar ─────────────────────────────────────────────────────────────

    public function test_admin_can_create_holiday(): void
    {
        $res = $this->actingAs($this->admin())
            ->postJson('/api/v1/holidays', [
                'name'           => 'Natal',
                'date'           => '2026-12-25',
                'type'           => 'national',
                'repeats_yearly' => true,
            ])
            ->assertStatus(201)
            ->json('data');

        $this->assertEquals('Natal', $res['name']);
        $this->assertEquals('2026-12-25', $res['date']);
        $this->assertTrue($res['repeats_yearly']);
        $this->assertDatabaseHas('holidays', ['name' => 'Natal']);
    }

    public function test_create_holiday_requires_name(): void
    {
        $this->actingAs($this->admin())
            ->postJson('/api/v1/holidays', [
                'date' => '2026-12-25', 'type' => 'national',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_create_holiday_requires_valid_type(): void
    {
        $this->actingAs($this->admin())
            ->postJson('/api/v1/holidays', [
                'name' => 'Teste', 'date' => '2026-12-25', 'type' => 'invalid_type',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    }

    public function test_create_holiday_requires_date(): void
    {
        $this->actingAs($this->admin())
            ->postJson('/api/v1/holidays', [
                'name' => 'Teste', 'type' => 'national',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['date']);
    }

    // ── Actualizar ────────────────────────────────────────────────────────

    public function test_admin_can_update_holiday(): void
    {
        $holiday = $this->makeHoliday();

        $res = $this->actingAs($this->admin())
            ->putJson("/api/v1/holidays/{$holiday->id}", [
                'name' => 'Natal Actualizado',
                'type' => 'local',
            ])
            ->assertOk()
            ->json('data');

        $this->assertEquals('Natal Actualizado', $res['name']);
        $this->assertEquals('local', $res['type']);
    }

    public function test_update_nonexistent_holiday_returns_404(): void
    {
        $this->actingAs($this->admin())
            ->putJson('/api/v1/holidays/99999', ['name' => 'X'])
            ->assertStatus(404);
    }

    // ── Eliminar ──────────────────────────────────────────────────────────

    public function test_admin_can_delete_holiday(): void
    {
        $holiday = $this->makeHoliday();

        $this->actingAs($this->admin())
            ->deleteJson("/api/v1/holidays/{$holiday->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('holidays', ['id' => $holiday->id]);
    }

    public function test_employee_cannot_delete_holiday(): void
    {
        $holiday = $this->makeHoliday();

        $this->actingAs($this->employee())
            ->deleteJson("/api/v1/holidays/{$holiday->id}")
            ->assertStatus(403);
    }

    // ── Estrutura da resposta ─────────────────────────────────────────────

    public function test_response_has_expected_structure(): void
    {
        $this->makeHoliday();

        $data = $this->actingAs($this->admin())
            ->getJson('/api/v1/holidays')
            ->assertOk()
            ->json('data.0');

        foreach (['id', 'name', 'date', 'date_formatted', 'type', 'repeats_yearly'] as $key) {
            $this->assertArrayHasKey($key, $data, "Chave em falta: $key");
        }
    }
}
