<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Training;
use App\Models\TrainingSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TrainingPlanSeeder extends Seeder
{
    public function run(): void
    {
        $trainings = Training::all();

        if ($trainings->isEmpty()) {
            $this->command->warn('Sem formações. Execute primeiro os seeders de formações.');
            return;
        }

        $year = Carbon::now()->year;

        // Definição das sessões a criar — distribuídas ao longo do ano
        $sessionsData = [
            [
                'month' => 1, 'day' => 15,
                'duration' => 2,
                'location' => 'Sala A — Sede',
                'max_participants' => 20,
                'estimated_participants' => 15,
                'cost_per_person' => 120.00,
                'status' => 'completed',
                'notes' => 'Sessão de arranque do ano.',
            ],
            [
                'month' => 2, 'day' => 10,
                'duration' => 1,
                'location' => 'Online (Zoom)',
                'max_participants' => 30,
                'estimated_participants' => 25,
                'cost_per_person' => 50.00,
                'status' => 'completed',
                'notes' => null,
            ],
            [
                'month' => 3, 'day' => 5,
                'duration' => 3,
                'location' => 'Centro de Formação — Braga',
                'max_participants' => 15,
                'estimated_participants' => 12,
                'cost_per_person' => 200.00,
                'status' => 'completed',
                'notes' => 'Formação externa certificada.',
            ],
            [
                'month' => 4, 'day' => 20,
                'duration' => 1,
                'location' => 'Sala B — Sede',
                'max_participants' => 25,
                'estimated_participants' => 20,
                'cost_per_person' => 80.00,
                'status' => 'completed',
                'notes' => null,
            ],
            [
                'month' => 5, 'day' => 8,
                'duration' => 2,
                'location' => 'Online (Teams)',
                'max_participants' => 20,
                'estimated_participants' => 18,
                'cost_per_person' => 95.00,
                'status' => 'completed',
                'notes' => null,
            ],
            [
                'month' => 6, 'day' => 3,
                'duration' => 5,
                'location' => 'Hotel Minho — Viana do Castelo',
                'max_participants' => 10,
                'estimated_participants' => 8,
                'cost_per_person' => 450.00,
                'status' => 'completed',
                'notes' => 'Inclui alojamento e refeições.',
            ],
            [
                'month' => 7, 'day' => 14,
                'duration' => 1,
                'location' => 'Sala A — Sede',
                'max_participants' => 30,
                'estimated_participants' => 22,
                'cost_per_person' => 60.00,
                'status' => 'completed',
                'notes' => null,
            ],
            [
                'month' => 8, 'day' => 20,
                'duration' => 2,
                'location' => 'Online (Zoom)',
                'max_participants' => 20,
                'estimated_participants' => 10,
                'cost_per_person' => 75.00,
                'status' => 'planned',
                'notes' => 'Período de férias — participação reduzida prevista.',
            ],
            [
                'month' => 9, 'day' => 10,
                'duration' => 3,
                'location' => 'Centro de Formação — Porto',
                'max_participants' => 20,
                'estimated_participants' => 18,
                'cost_per_person' => 180.00,
                'status' => 'planned',
                'notes' => null,
            ],
            [
                'month' => 10, 'day' => 6,
                'duration' => 2,
                'location' => 'Sala B — Sede',
                'max_participants' => 25,
                'estimated_participants' => 20,
                'cost_per_person' => 110.00,
                'status' => 'planned',
                'notes' => null,
            ],
            [
                'month' => 10, 'day' => 22,
                'duration' => 1,
                'location' => 'Online (Teams)',
                'max_participants' => 50,
                'estimated_participants' => 35,
                'cost_per_person' => 40.00,
                'status' => 'planned',
                'notes' => 'Sessão de reciclagem obrigatória.',
            ],
            [
                'month' => 11, 'day' => 12,
                'duration' => 4,
                'location' => 'Hotel Bom Jesus — Braga',
                'max_participants' => 12,
                'estimated_participants' => 10,
                'cost_per_person' => 380.00,
                'status' => 'planned',
                'notes' => 'Workshop intensivo.',
            ],
            [
                'month' => 12, 'day' => 5,
                'duration' => 1,
                'location' => 'Sala A — Sede',
                'max_participants' => 30,
                'estimated_participants' => 25,
                'cost_per_person' => 0.00,
                'status' => 'planned',
                'notes' => 'Sessão interna gratuita — balanço anual.',
            ],
        ];

        $employees = Employee::where('status', 'active')->get();
        $createdSessions = [];

        foreach ($sessionsData as $data) {
            $training = $trainings->random();
            $startDate = Carbon::create($year, $data['month'], $data['day']);
            $endDate   = $startDate->copy()->addDays($data['duration'] - 1);

            $session = TrainingSession::create([
                'training_id'            => $training->id,
                'planned_date'           => $startDate->toDateString(),
                'planned_end_date'       => $data['duration'] > 1 ? $endDate->toDateString() : null,
                'location'               => $data['location'],
                'max_participants'       => $data['max_participants'],
                'estimated_participants' => $data['estimated_participants'],
                'cost_per_person'        => $data['cost_per_person'],
                'status'                 => $data['status'],
                'notes'                  => $data['notes'],
            ]);

            $createdSessions[] = ['session' => $session, 'training' => $training, 'data' => $data];
            $this->command->line("  ✓ Sessão criada: {$training->title} — {$startDate->format('d/m/Y')}");
        }

        // Para as sessões concluídas, criar inscrições associadas
        foreach ($createdSessions as $item) {
            $session  = $item['session'];
            $training = $item['training'];
            $data     = $item['data'];

            if ($data['status'] !== 'completed') {
                continue;
            }

            // Número de inscrições = entre 70% e 100% dos participantes estimados
            $numEnroll = (int) round($data['estimated_participants'] * rand(70, 100) / 100);
            $pool = $employees->shuffle()->take($numEnroll);

            foreach ($pool as $employee) {
                // Evitar duplicado na mesma formação
                $exists = EmployeeTraining::where('employee_id', $employee->id)
                    ->where('training_id', $training->id)
                    ->exists();
                if ($exists) continue;

                $passedQuiz = rand(0, 100) > 20; // 80% aprovação

                EmployeeTraining::create([
                    'employee_id'          => $employee->id,
                    'training_id'          => $training->id,
                    'training_session_id'  => $session->id,
                    'status'               => $passedQuiz ? 'completed' : 'enrolled',
                    'start_date'           => $session->planned_date->toDateString(),
                    'end_date'             => ($session->planned_end_date ?? $session->planned_date)->toDateString(),
                    'score'                => $passedQuiz ? rand(65, 100) : rand(20, 64),
                    'validity_months'      => rand(0, 1) ? 12 : null,
                ]);
            }

            $this->command->line("    → {$numEnroll} inscrições criadas");
        }

        $this->command->info('TrainingPlanSeeder concluído.');
    }
}
