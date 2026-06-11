<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\Project;
use App\Models\Team;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    // ── Project ──────────────────────────────────────────────────────────────

    public function test_project_fillable(): void
    {
        $p = Project::factory()->create([
            'name'       => 'Obra Teste',
            'reference'  => 'OBR-001',
            'client'     => 'Cliente X',
            'location'   => 'Lisboa',
            'status'     => 'active',
            'notes'      => 'Notas aqui',
        ]);

        $this->assertEquals('Obra Teste', $p->name);
        $this->assertEquals('OBR-001',   $p->reference);
        $this->assertEquals('active',    $p->status);
    }

    public function test_project_dates_are_cast(): void
    {
        $p = Project::factory()->create([
            'start_date' => '2026-01-01',
            'end_date'   => '2026-12-31',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $p->start_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $p->end_date);
    }

    public function test_project_has_many_teams(): void
    {
        $project = Project::factory()->create();
        Team::factory()->count(3)->create(['project_id' => $project->id]);

        $this->assertCount(3, $project->teams);
        $this->assertInstanceOf(Team::class, $project->teams->first());
    }

    public function test_project_teams_cascade_delete(): void
    {
        $project = Project::factory()->create();
        Team::factory()->count(2)->create(['project_id' => $project->id]);

        $this->assertDatabaseCount('teams', 2);
        $project->delete();
        $this->assertDatabaseCount('teams', 0);
    }

    public function test_project_nullable_fields(): void
    {
        $p = Project::factory()->create([
            'reference' => null,
            'client'    => null,
            'location'  => null,
            'end_date'  => null,
            'notes'     => null,
        ]);

        $this->assertNull($p->reference);
        $this->assertNull($p->client);
        $this->assertNull($p->end_date);
    }

    // ── Vehicle ──────────────────────────────────────────────────────────────

    public function test_vehicle_fillable(): void
    {
        $v = Vehicle::factory()->create([
            'plate'  => 'AA-00-BB',
            'brand'  => 'Renault',
            'model'  => 'Master',
            'year'   => 2022,
            'type'   => 'van',
            'status' => 'active',
        ]);

        $this->assertEquals('AA-00-BB', $v->plate);
        $this->assertEquals('Renault',  $v->brand);
        $this->assertEquals('van',      $v->type);
        $this->assertEquals('active',   $v->status);
    }

    public function test_vehicle_plate_is_unique(): void
    {
        Vehicle::factory()->create(['plate' => 'XX-99-YY']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Vehicle::factory()->create(['plate' => 'XX-99-YY']);
    }

    public function test_vehicle_belongs_to_many_teams(): void
    {
        $vehicle = Vehicle::factory()->create();
        $team    = Team::factory()->create();

        $team->vehicles()->attach($vehicle->id, ['start_date' => '2026-01-01']);

        $this->assertCount(1, $vehicle->teams);
        $this->assertInstanceOf(Team::class, $vehicle->teams->first());
    }

    // ── Team ─────────────────────────────────────────────────────────────────

    public function test_team_belongs_to_project(): void
    {
        $project = Project::factory()->create();
        $team    = Team::factory()->create(['project_id' => $project->id]);

        $this->assertEquals($project->id, $team->project->id);
    }

    public function test_team_leader_is_optional(): void
    {
        $team = Team::factory()->create(['leader_id' => null]);
        $this->assertNull($team->leader_id);
        $this->assertNull($team->leader);
    }

    public function test_team_leader_is_employee(): void
    {
        $leader = Employee::factory()->create();
        $team   = Team::factory()->create(['leader_id' => $leader->id]);

        $this->assertInstanceOf(Employee::class, $team->leader);
        $this->assertEquals($leader->id, $team->leader->id);
    }

    public function test_team_has_many_employees_via_pivot(): void
    {
        $team = Team::factory()->create();
        $emps = Employee::factory()->count(3)->create();

        foreach ($emps as $e) {
            $team->employees()->attach($e->id, [
                'start_date' => '2026-01-01',
                'role'       => 'Electricista',
            ]);
        }

        $this->assertCount(3, $team->employees);
        $this->assertEquals('Electricista', $team->employees->first()->pivot->role);
    }

    public function test_team_has_vehicles_via_pivot(): void
    {
        $team    = Team::factory()->create();
        $vehicle = Vehicle::factory()->create();

        $team->vehicles()->attach($vehicle->id, ['start_date' => '2026-03-01']);

        $this->assertCount(1, $team->vehicles);
        $this->assertEquals('2026-03-01', $team->vehicles->first()->pivot->start_date);
    }

    public function test_employee_belongs_to_many_teams(): void
    {
        $employee = Employee::factory()->create();
        $teams    = Team::factory()->count(2)->create();

        foreach ($teams as $t) {
            $t->employees()->attach($employee->id, ['start_date' => '2026-01-01']);
        }

        $this->assertCount(2, $employee->teams);
    }
}
