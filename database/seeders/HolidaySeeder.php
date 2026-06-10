<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $national = [
            ['name' => 'Ano Novo',                         'date' => '2024-01-01'],
            ['name' => 'Carnaval',                         'date' => '2025-03-04'], // variável — não recorrente
            ['name' => 'Sexta-Feira Santa',                'date' => '2025-04-18'], // variável
            ['name' => 'Páscoa',                           'date' => '2025-04-20'], // variável
            ['name' => 'Dia da Liberdade',                 'date' => '2024-04-25'],
            ['name' => 'Dia do Trabalhador',               'date' => '2024-05-01'],
            ['name' => 'Dia de Portugal',                  'date' => '2024-06-10'],
            ['name' => 'Corpo de Deus',                    'date' => '2025-06-19'], // variável
            ['name' => 'Assunção de Nossa Senhora',        'date' => '2024-08-15'],
            ['name' => 'Implantação da República',         'date' => '2024-10-05'],
            ['name' => 'Dia de Todos os Santos',           'date' => '2024-11-01'],
            ['name' => 'Restauração da Independência',     'date' => '2024-12-01'],
            ['name' => 'Imaculada Conceição',              'date' => '2024-12-08'],
            ['name' => 'Natal',                            'date' => '2024-12-25'],
        ];

        // Feriados de data fixa (recorrentes anualmente por mês/dia)
        $fixedDates = ['01-01','04-25','05-01','06-10','08-15','10-05','11-01','12-01','12-08','12-25'];

        foreach ($national as $h) {
            $md = substr($h['date'], 5); // MM-DD
            Holiday::firstOrCreate(
                ['date' => $h['date'], 'type' => 'national'],
                [
                    'name'           => $h['name'],
                    'repeats_yearly' => in_array($md, $fixedDates),
                ]
            );
        }
    }
}
