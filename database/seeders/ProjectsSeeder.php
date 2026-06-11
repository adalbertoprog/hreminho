<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Project;
use App\Models\Team;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    public function run(): void
    {
        // ── Viaturas ─────────────────────────────────────────────────────────
        $viaturas = [
            ['plate' => '00-AE-12', 'brand' => 'Renault',     'model' => 'Master',   'year' => 2020, 'type' => 'van',   'status' => 'active'],
            ['plate' => '55-QR-78', 'brand' => 'Mercedes',    'model' => 'Sprinter',  'year' => 2019, 'type' => 'van',   'status' => 'active'],
            ['plate' => '34-MN-56', 'brand' => 'Ford',        'model' => 'Transit',   'year' => 2021, 'type' => 'van',   'status' => 'active'],
            ['plate' => '88-GH-21', 'brand' => 'Volkswagen',  'model' => 'Crafter',   'year' => 2018, 'type' => 'truck', 'status' => 'active'],
            ['plate' => '12-ZX-99', 'brand' => 'Iveco',       'model' => 'Daily',     'year' => 2022, 'type' => 'truck', 'status' => 'maintenance'],
            ['plate' => '67-PT-03', 'brand' => 'Fiat',        'model' => 'Ducato',    'year' => 2023, 'type' => 'van',   'status' => 'active'],
            ['plate' => '41-LK-88', 'brand' => 'Renault',     'model' => 'Trafic',    'year' => 2017, 'type' => 'van',   'status' => 'inactive'],
            ['plate' => '93-BN-45', 'brand' => 'Mercedes',    'model' => 'Vito',      'year' => 2021, 'type' => 'car',   'status' => 'active'],
        ];

        $vehs = [];
        foreach ($viaturas as $v) {
            // evitar duplicados se correr mais que uma vez
            $vehs[] = Vehicle::firstOrCreate(['plate' => $v['plate']], $v);
        }

        // ── Chefes de equipa (para líderes) ──────────────────────────────────
        $chefesIds = Employee::whereHas('position', fn($q) =>
            $q->where('position', 'like', '%CHEFE DE EQUIPA%')
        )->pluck('id')->toArray();

        // ── Todos os funcionários activos ─────────────────────────────────────
        $allEmpIds = Employee::where('status', 'active')->pluck('id')->toArray();

        // ── Obras ─────────────────────────────────────────────────────────────
        $obras = [
            [
                'name'       => 'Moradia Unifamiliar — Braga Norte',
                'reference'  => 'OBR-2026-001',
                'client'     => 'António Silva & Filhos Lda',
                'location'   => 'Braga',
                'start_date' => '2026-01-15',
                'end_date'   => '2026-09-30',
                'status'     => 'active',
                'notes'      => 'Instalação eléctrica completa. Cliente exige certificação CERTIEL antes da entrega.',
            ],
            [
                'name'       => 'Edifício Comercial Viana — Fase 1',
                'reference'  => 'OBR-2026-002',
                'client'     => 'Imobiliária Norte SA',
                'location'   => 'Viana do Castelo',
                'start_date' => '2026-02-01',
                'end_date'   => '2026-12-15',
                'status'     => 'active',
                'notes'      => 'Piso 0 e 1. Fase 2 prevista para 2027.',
            ],
            [
                'name'       => 'Subestação Industrial — Barcelos',
                'reference'  => 'OBR-2026-003',
                'client'     => 'TêxtilNorte SA',
                'location'   => 'Barcelos',
                'start_date' => '2026-03-10',
                'end_date'   => '2026-07-31',
                'status'     => 'active',
                'notes'      => 'Alta tensão. Requer coordenação com EDP.',
            ],
            [
                'name'       => 'Reabilitação Escola EB2,3 — Guimarães',
                'reference'  => 'OBR-2026-004',
                'client'     => 'Câmara Municipal de Guimarães',
                'location'   => 'Guimarães',
                'start_date' => '2026-05-01',
                'end_date'   => '2026-08-31',
                'status'     => 'planned',
                'notes'      => 'Obra condicionada ao período escolar — só verão.',
            ],
            [
                'name'       => 'Parque Fotovoltaico — Ponte de Lima',
                'reference'  => 'OBR-2025-012',
                'client'     => 'GreenEnergy Portugal',
                'location'   => 'Ponte de Lima',
                'start_date' => '2025-06-01',
                'end_date'   => '2025-12-20',
                'status'     => 'completed',
                'notes'      => 'Concluída. 320 painéis instalados.',
            ],
        ];

        foreach ($obras as $oData) {
            $project = Project::firstOrCreate(
                ['reference' => $oData['reference']],
                $oData
            );

            // Criar 2 equipas por obra activa / planned
            if (in_array($oData['status'], ['active', 'planned'])) {
                $this->seedTeams($project, $chefesIds, $allEmpIds, $vehs);
            } else {
                // obra concluída — 1 equipa sem membros activos
                $team = Team::firstOrCreate(
                    ['project_id' => $project->id, 'name' => 'Equipa A'],
                    ['leader_id' => $chefesIds[0] ?? null]
                );
            }
        }
    }

    private function seedTeams(Project $project, array $chefesIds, array $allEmpIds, array $vehs): void
    {
        shuffle($chefesIds);
        shuffle($allEmpIds);
        $vehIds = array_map(fn($v) => $v->id, $vehs);
        shuffle($vehIds);

        // ── Equipa A ─────────────────────────────────────────────────────────
        $teamA = Team::firstOrCreate(
            ['project_id' => $project->id, 'name' => 'Equipa A'],
            [
                'leader_id' => $chefesIds[0] ?? null,
                'notes'     => 'Equipa principal',
            ]
        );

        // Adicionar 4-5 funcionários
        $membrosA = array_slice($allEmpIds, 0, 5);
        foreach ($membrosA as $i => $empId) {
            if (!$teamA->employees()->where('employee_id', $empId)->exists()) {
                $teamA->employees()->attach($empId, [
                    'start_date' => $project->start_date?->format('Y-m-d'),
                    'end_date'   => null,
                    'role'       => $i === 0 ? 'Encarregado' : ($i % 2 === 0 ? 'Electricista' : 'Ajudante'),
                ]);
            }
        }

        // Adicionar 2 viaturas
        foreach (array_slice($vehIds, 0, 2) as $vId) {
            if (!$teamA->vehicles()->where('vehicle_id', $vId)->exists()) {
                $teamA->vehicles()->attach($vId, [
                    'start_date' => $project->start_date?->format('Y-m-d'),
                ]);
            }
        }

        // ── Equipa B ─────────────────────────────────────────────────────────
        $teamB = Team::firstOrCreate(
            ['project_id' => $project->id, 'name' => 'Equipa B'],
            [
                'leader_id' => $chefesIds[1] ?? $chefesIds[0] ?? null,
                'notes'     => 'Equipa de apoio',
            ]
        );

        $membrosB = array_slice($allEmpIds, 5, 4);
        foreach ($membrosB as $i => $empId) {
            if (!$teamB->employees()->where('employee_id', $empId)->exists()) {
                $teamB->employees()->attach($empId, [
                    'start_date' => $project->start_date?->format('Y-m-d'),
                    'end_date'   => null,
                    'role'       => $i === 0 ? 'Electricista Sénior' : 'Electricista',
                ]);
            }
        }

        // 1 viatura
        if (isset($vehIds[2])) {
            if (!$teamB->vehicles()->where('vehicle_id', $vehIds[2])->exists()) {
                $teamB->vehicles()->attach($vehIds[2], [
                    'start_date' => $project->start_date?->format('Y-m-d'),
                ]);
            }
        }
    }
}
