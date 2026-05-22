<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\Training;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CalendarDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar formações no catálogo
        $trainingData = [
            ['title' => 'Segurança no Trabalho (CAP)',        'provider' => 'ACT / Formação Interna',  'description' => 'Formação obrigatória de segurança e saúde no trabalho.'],
            ['title' => 'Eletricidade — Baixa Tensão (BT)',   'provider' => 'CERTIEL',                 'description' => 'Habilitação para trabalhos em instalações de baixa tensão.'],
            ['title' => 'Trabalho em Altura',                 'provider' => 'Formação Interna',        'description' => 'Procedimentos de segurança em trabalhos em altura.'],
            ['title' => 'Primeiros Socorros',                 'provider' => 'Cruz Vermelha',           'description' => 'Técnicas básicas de primeiros socorros e SBV.'],
            ['title' => 'Gestão de Equipas',                  'provider' => 'IFE Portugal',            'description' => 'Liderança, motivação e gestão de conflitos em equipas.'],
            ['title' => 'Excel Avançado',                     'provider' => 'Rumos',                   'description' => 'Fórmulas avançadas, tabelas dinâmicas e dashboards.'],
            ['title' => 'Proteção de Dados (RGPD)',           'provider' => 'CNPD / e-Learning',       'description' => 'Regulamento Geral de Proteção de Dados aplicado à empresa.'],
            ['title' => 'Prevenção e Combate a Incêndios',   'provider' => 'Formação Interna',        'description' => 'Uso de extintores e procedimentos de evacuação.'],
            ['title' => 'Comunicação e Atendimento',         'provider' => 'IFE Portugal',            'description' => 'Técnicas de comunicação eficaz e atendimento ao cliente.'],
            ['title' => 'Manutenção Preventiva de Equipamentos', 'provider' => 'Bosch Training',      'description' => 'Planos de manutenção e diagnóstico de avarias em equipamentos industriais.'],
        ];

        $trainings = [];
        foreach ($trainingData as $td) {
            $trainings[] = Training::firstOrCreate(['title' => $td['title']], $td);
        }

        // 2. Apanhar funcionários existentes (máximo 40 para o demo)
        $employees = Employee::where('status', 'active')->inRandomOrder()->limit(40)->get();

        if ($employees->isEmpty()) {
            $this->command->warn('Nenhum funcionário ativo encontrado. Corre primeiro o DatabaseSeeder.');
            return;
        }

        $today = Carbon::today();

        // 3. Gerar inscrições com datas variadas para popular o calendário
        $scenarios = [
            // Passadas concluídas (aparecem em meses anteriores)
            ['status' => 'completed', 'start_offset' => -120, 'duration' => 3,  'validity' => 24, 'score_min' => 70, 'score_max' => 100],
            ['status' => 'completed', 'start_offset' => -90,  'duration' => 5,  'validity' => 12, 'score_min' => 55, 'score_max' => 95],
            ['status' => 'completed', 'start_offset' => -60,  'duration' => 2,  'validity' => 36, 'score_min' => 75, 'score_max' => 100],
            ['status' => 'failed',    'start_offset' => -45,  'duration' => 3,  'validity' => null,'score_min' => 10, 'score_max' => 49],
            ['status' => 'completed', 'start_offset' => -30,  'duration' => 7,  'validity' => 12, 'score_min' => 60, 'score_max' => 90],
            // Em curso agora (cruzam o mês atual — essenciais para o teste do calendário)
            ['status' => 'enrolled',  'start_offset' => -15,  'duration' => 30, 'validity' => null,'score_min' => null,'score_max' => null],
            ['status' => 'enrolled',  'start_offset' => -5,   'duration' => 20, 'validity' => null,'score_min' => null,'score_max' => null],
            ['status' => 'enrolled',  'start_offset' => -10,  'duration' => 45, 'validity' => null,'score_min' => null,'score_max' => null],
            ['status' => 'enrolled',  'start_offset' => 0,    'duration' => 14, 'validity' => null,'score_min' => null,'score_max' => null],
            // Futuras agendadas
            ['status' => 'enrolled',  'start_offset' => 7,   'duration' => 10, 'validity' => null,'score_min' => null,'score_max' => null],
            ['status' => 'enrolled',  'start_offset' => 14,  'duration' => 5,  'validity' => null,'score_min' => null,'score_max' => null],
            ['status' => 'enrolled',  'start_offset' => 21,  'duration' => 3,  'validity' => null,'score_min' => null,'score_max' => null],
            ['status' => 'enrolled',  'start_offset' => 30,  'duration' => 7,  'validity' => null,'score_min' => null,'score_max' => null],
        ];

        $created = 0;
        $trainingCount = count($trainings);

        foreach ($employees as $i => $employee) {
            // Cada funcionário recebe 2 a 4 inscrições aleatórias
            $numEnrollments = rand(2, 4);
            $usedTrainings  = [];

            for ($j = 0; $j < $numEnrollments; $j++) {
                // Escolher cenário e formação sem repetição por funcionário
                $scenario  = $scenarios[($i * $numEnrollments + $j) % count($scenarios)];
                $training  = $trainings[array_rand($trainings)];

                if (in_array($training->id, $usedTrainings)) {
                    continue;
                }
                $usedTrainings[] = $training->id;

                // Evitar duplicados na BD
                $exists = EmployeeTraining::where('employee_id', $employee->id)
                    ->where('training_id', $training->id)
                    ->exists();
                if ($exists) {
                    continue;
                }

                $startDate = $today->copy()->addDays($scenario['start_offset'] + rand(-3, 3));
                $endDate   = $startDate->copy()->addDays($scenario['duration']);

                // Para formações em curso sem end_date definida (status enrolled sem fim)
                $endDateFinal = in_array($scenario['status'], ['enrolled']) && $scenario['validity'] === null && $scenario['start_offset'] <= 0
                    ? ($scenario['duration'] > 0 ? $endDate->format('Y-m-d') : null)
                    : $endDate->format('Y-m-d');

                $score = null;
                if ($scenario['score_min'] !== null) {
                    $score = round(rand((int)($scenario['score_min'] * 10), (int)($scenario['score_max'] * 10)) / 10, 1);
                }

                EmployeeTraining::create([
                    'employee_id'      => $employee->id,
                    'training_id'      => $training->id,
                    'status'           => $scenario['status'],
                    'start_date'       => $startDate->format('Y-m-d'),
                    'end_date'         => $endDateFinal,
                    'score'            => $score,
                    'validity_months'  => $scenario['validity'],
                    'certificate_path' => $scenario['status'] === 'completed'
                        ? 'certificates/' . strtolower(str_replace(' ', '-', $training->title)) . '-' . $employee->code . '.pdf'
                        : null,
                    'notes' => null,
                ]);
                $created++;
            }
        }

        $this->command->info("✅ CalendarDemoSeeder concluído: {$created} inscrições criadas para " . $employees->count() . " funcionários.");
    }
}
