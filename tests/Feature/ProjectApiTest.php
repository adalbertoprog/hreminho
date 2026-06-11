<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    // ── Auth helpers ─────────────────────────────────────────────────────

    private function admin(): User
    {
        return User::factory()->create([
            'role'                 => 'admin',
            'password'             => Hash::make('password'),
            'must_change_password' => false,
        ]);
    }

    private function hrUser(): User
    {
        return User::factory()->create([
            'role'                 => 'hr',
            'password'             => Hash::make('password'),
            'must_change_password' => false,
        ]);
    }

    private function employeeUser(): User
    {
        return User::factory()->create([
            'role'                 => 'employee',
            'password'             => Hash::make('password'),
            'must_change_password' => false,
        ]);
    }

    // ════════════════════════════════════════════════════════════════════
    // PROJECTS
    // ════════════════════════════════════════════════════════════════════

    public function test_unauthenticated_cannot_list_projects(): void
    {
        $this->getJson('/api/v1/projects')->assertStatus(401);
    }

    public function test_employee_cannot_access_projects(): void
    {
        $this->actingAs($this->employeeUser())
             ->getJson('/api/v1/projects')
             ->assertStatus(403);
    }

    public function test_admin_can_list_projects(): void
    {
        Project::factory()->count(3)->create();

        $this->actingAs($this->admin())
             ->getJson('/api/v1/projects')
             ->assertOk()
             ->assertJsonCount(3, 'data');
    }

    public function test_hr_can_list_projects(): void
    {
        Project::factory()->count(2)->create();

        $this->actingAs($this->hrUser())
             ->getJson('/api/v1/projects')
             ->assertOk()
             ->assertJsonCount(2, 'data');
    }

    public function test_list_projects_filters_by_status(): void
    {
        Project::factory()->active()->count(2)->create();
        Project::factory()->planned()->count(3)->create();

        $this->actingAs($this->admin())
             ->getJson('/api/v1/projects?status=active')
             ->assertOk()
             ->assertJsonCount(2, 'data');
    }

    public function test_list_projects_filters_by_search(): void
    {
        Project::factory()->create(['name' => 'Obra Central Lisboa']);
        Project::factory()->create(['name' => 'Moradia Porto']);
        Project::factory()->create(['client' => 'Empresa Lisboa Lda']);

        $res = $this->actingAs($this->admin())
                    ->getJson('/api/v1/projects?search=Lisboa')
                    ->assertOk();

        $this->assertCount(2, $res->json('data'));
    }

    public function test_admin_can_create_project(): void
    {
        $this->actingAs($this->admin())
             ->postJson('/api/v1/projects', [
                 'name'       => 'Nova Obra',
                 'reference'  => 'OBR-2026-001',
                 'client'     => 'Cliente Teste',
                 'location'   => 'Lisboa',
                 'start_date' => '2026-01-01',
                 'status'     => 'active',
             ])
             ->assertStatus(201)
             ->assertJsonPath('data.name', 'Nova Obra')
             ->assertJsonPath('data.status', 'active')
             ->assertJsonPath('data.reference', 'OBR-2026-001');

        $this->assertDatabaseHas('projects', ['name' => 'Nova Obra']);
    }

    public function test_create_project_requires_name(): void
    {
        $this->actingAs($this->admin())
             ->postJson('/api/v1/projects', ['status' => 'active'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['name']);
    }

    public function test_create_project_reference_must_be_unique(): void
    {
        Project::factory()->create(['reference' => 'OBR-001']);

        $this->actingAs($this->admin())
             ->postJson('/api/v1/projects', ['name' => 'Outra Obra', 'reference' => 'OBR-001'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['reference']);
    }

    public function test_create_project_end_date_must_be_after_start(): void
    {
        $this->actingAs($this->admin())
             ->postJson('/api/v1/projects', [
                 'name'       => 'Obra',
                 'start_date' => '2026-12-31',
                 'end_date'   => '2026-01-01',
             ])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['end_date']);
    }

    public function test_admin_can_update_project(): void
    {
        $project = Project::factory()->create(['status' => 'planned']);

        $this->actingAs($this->admin())
             ->putJson("/api/v1/projects/{$project->id}", ['status' => 'active', 'name' => 'Obra Editada'])
             ->assertOk()
             ->assertJsonPath('data.status', 'active')
             ->assertJsonPath('data.name', 'Obra Editada');
    }

    public function test_admin_can_delete_project(): void
    {
        $project = Project::factory()->create();

        $this->actingAs($this->admin())
             ->deleteJson("/api/v1/projects/{$project->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_delete_project_cascades_to_teams(): void
    {
        $project = Project::factory()->create();
        Team::factory()->count(2)->create(['project_id' => $project->id]);

        $this->actingAs($this->admin())
             ->deleteJson("/api/v1/projects/{$project->id}")
             ->assertStatus(204);

        $this->assertDatabaseCount('teams', 0);
    }

    public function test_project_list_includes_teams_count(): void
    {
        $project = Project::factory()->create();
        Team::factory()->count(3)->create(['project_id' => $project->id]);

        $res = $this->actingAs($this->admin())
                    ->getJson('/api/v1/projects')
                    ->assertOk();

        $this->assertEquals(3, $res->json('data.0.teams_count'));
    }

    // ════════════════════════════════════════════════════════════════════
    // VEHICLES
    // ════════════════════════════════════════════════════════════════════

    public function test_admin_can_list_vehicles(): void
    {
        Vehicle::factory()->count(4)->create();

        $this->actingAs($this->admin())
             ->getJson('/api/v1/vehicles')
             ->assertOk()
             ->assertJsonCount(4, 'data');
    }

    public function test_admin_can_create_vehicle(): void
    {
        $this->actingAs($this->admin())
             ->postJson('/api/v1/vehicles', [
                 'plate'  => 'AA-00-BB',
                 'brand'  => 'Renault',
                 'model'  => 'Master',
                 'year'   => 2022,
                 'type'   => 'van',
                 'status' => 'active',
             ])
             ->assertStatus(201)
             ->assertJsonPath('data.plate', 'AA-00-BB')
             ->assertJsonPath('data.type', 'van');

        $this->assertDatabaseHas('vehicles', ['plate' => 'AA-00-BB']);
    }

    public function test_create_vehicle_requires_plate(): void
    {
        $this->actingAs($this->admin())
             ->postJson('/api/v1/vehicles', ['brand' => 'Ford', 'type' => 'van', 'status' => 'active'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['plate']);
    }

    public function test_create_vehicle_plate_must_be_unique(): void
    {
        Vehicle::factory()->create(['plate' => 'ZZ-99-ZZ']);

        $this->actingAs($this->admin())
             ->postJson('/api/v1/vehicles', ['plate' => 'ZZ-99-ZZ', 'type' => 'van', 'status' => 'active'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['plate']);
    }

    public function test_admin_can_update_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create(['status' => 'active']);

        $this->actingAs($this->admin())
             ->putJson("/api/v1/vehicles/{$vehicle->id}", ['status' => 'maintenance'])
             ->assertOk()
             ->assertJsonPath('data.status', 'maintenance');
    }

    public function test_admin_can_delete_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create();

        $this->actingAs($this->admin())
             ->deleteJson("/api/v1/vehicles/{$vehicle->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }

    public function test_employee_cannot_manage_vehicles(): void
    {
        $this->actingAs($this->employeeUser())
             ->postJson('/api/v1/vehicles', ['plate' => 'XX-00-YY', 'type' => 'van', 'status' => 'active'])
             ->assertStatus(403);
    }

    // ════════════════════════════════════════════════════════════════════
    // TEAMS
    // ════════════════════════════════════════════════════════════════════

    public function test_admin_can_list_teams_for_project(): void
    {
        $project = Project::factory()->create();
        Team::factory()->count(3)->create(['project_id' => $project->id]);

        $this->actingAs($this->admin())
             ->getJson("/api/v1/projects/{$project->id}/teams")
             ->assertOk()
             ->assertJsonCount(3, 'data');
    }

    public function test_admin_can_create_team(): void
    {
        $project = Project::factory()->create();

        $this->actingAs($this->admin())
             ->postJson("/api/v1/projects/{$project->id}/teams", [
                 'name' => 'Equipa Alpha',
             ])
             ->assertStatus(201)
             ->assertJsonPath('data.name', 'Equipa Alpha')
             ->assertJsonPath('data.project_id', $project->id);

        $this->assertDatabaseHas('teams', ['name' => 'Equipa Alpha', 'project_id' => $project->id]);
    }

    public function test_create_team_requires_name(): void
    {
        $project = Project::factory()->create();

        $this->actingAs($this->admin())
             ->postJson("/api/v1/projects/{$project->id}/teams", [])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['name']);
    }

    public function test_create_team_with_leader(): void
    {
        $project = Project::factory()->create();
        $leader  = Employee::factory()->create();

        $res = $this->actingAs($this->admin())
                    ->postJson("/api/v1/projects/{$project->id}/teams", [
                        'name'      => 'Equipa Beta',
                        'leader_id' => $leader->id,
                    ])
                    ->assertStatus(201);

        $this->assertEquals($leader->id, $res->json('data.leader.id'));
    }

    public function test_admin_can_update_team(): void
    {
        $project = Project::factory()->create();
        $team    = Team::factory()->create(['project_id' => $project->id, 'name' => 'Equipa Velha']);

        $this->actingAs($this->admin())
             ->putJson("/api/v1/projects/{$project->id}/teams/{$team->id}", ['name' => 'Equipa Nova'])
             ->assertOk()
             ->assertJsonPath('data.name', 'Equipa Nova');
    }

    public function test_update_team_must_belong_to_project(): void
    {
        $projectA = Project::factory()->create();
        $projectB = Project::factory()->create();
        $team     = Team::factory()->create(['project_id' => $projectA->id]);

        $this->actingAs($this->admin())
             ->putJson("/api/v1/projects/{$projectB->id}/teams/{$team->id}", ['name' => 'X'])
             ->assertStatus(404);
    }

    public function test_admin_can_delete_team(): void
    {
        $project = Project::factory()->create();
        $team    = Team::factory()->create(['project_id' => $project->id]);

        $this->actingAs($this->admin())
             ->deleteJson("/api/v1/projects/{$project->id}/teams/{$team->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
    }

    // ── Team members ─────────────────────────────────────────────────────

    public function test_can_add_employee_to_team(): void
    {
        $project  = Project::factory()->create();
        $team     = Team::factory()->create(['project_id' => $project->id]);
        $employee = Employee::factory()->create();

        $this->actingAs($this->admin())
             ->postJson("/api/v1/projects/{$project->id}/teams/{$team->id}/employees", [
                 'employee_id' => $employee->id,
                 'role'        => 'Electricista',
                 'start_date'  => '2026-01-01',
             ])
             ->assertOk();

        $this->assertDatabaseHas('team_employees', [
            'team_id'     => $team->id,
            'employee_id' => $employee->id,
            'role'        => 'Electricista',
        ]);
    }

    public function test_add_employee_requires_valid_employee_id(): void
    {
        $project = Project::factory()->create();
        $team    = Team::factory()->create(['project_id' => $project->id]);

        $this->actingAs($this->admin())
             ->postJson("/api/v1/projects/{$project->id}/teams/{$team->id}/employees", [
                 'employee_id' => 99999,
             ])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['employee_id']);
    }

    public function test_can_remove_employee_from_team(): void
    {
        $project  = Project::factory()->create();
        $team     = Team::factory()->create(['project_id' => $project->id]);
        $employee = Employee::factory()->create();

        $team->employees()->attach($employee->id, ['start_date' => '2026-01-01']);

        $this->assertDatabaseHas('team_employees', ['team_id' => $team->id, 'employee_id' => $employee->id]);

        $this->actingAs($this->admin())
             ->deleteJson("/api/v1/projects/{$project->id}/teams/{$team->id}/employees", [
                 'employee_id' => $employee->id,
             ])
             ->assertStatus(204);

        $this->assertDatabaseMissing('team_employees', ['team_id' => $team->id, 'employee_id' => $employee->id]);
    }

    // ── Team vehicles ─────────────────────────────────────────────────────

    public function test_can_add_vehicle_to_team(): void
    {
        $project = Project::factory()->create();
        $team    = Team::factory()->create(['project_id' => $project->id]);
        $vehicle = Vehicle::factory()->create();

        $this->actingAs($this->admin())
             ->postJson("/api/v1/projects/{$project->id}/teams/{$team->id}/vehicles", [
                 'vehicle_id' => $vehicle->id,
                 'start_date' => '2026-02-01',
             ])
             ->assertOk();

        $this->assertDatabaseHas('team_vehicles', [
            'team_id'    => $team->id,
            'vehicle_id' => $vehicle->id,
        ]);
    }

    public function test_can_remove_vehicle_from_team(): void
    {
        $project = Project::factory()->create();
        $team    = Team::factory()->create(['project_id' => $project->id]);
        $vehicle = Vehicle::factory()->create();

        $team->vehicles()->attach($vehicle->id, ['start_date' => '2026-01-01']);

        $this->actingAs($this->admin())
             ->deleteJson("/api/v1/projects/{$project->id}/teams/{$team->id}/vehicles", [
                 'vehicle_id' => $vehicle->id,
             ])
             ->assertStatus(204);

        $this->assertDatabaseMissing('team_vehicles', ['team_id' => $team->id, 'vehicle_id' => $vehicle->id]);
    }

    public function test_add_vehicle_end_date_must_be_after_start(): void
    {
        $project = Project::factory()->create();
        $team    = Team::factory()->create(['project_id' => $project->id]);
        $vehicle = Vehicle::factory()->create();

        $this->actingAs($this->admin())
             ->postJson("/api/v1/projects/{$project->id}/teams/{$team->id}/vehicles", [
                 'vehicle_id' => $vehicle->id,
                 'start_date' => '2026-12-31',
                 'end_date'   => '2026-01-01',
             ])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['end_date']);
    }
}
