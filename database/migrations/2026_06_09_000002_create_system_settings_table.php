<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, time
            $table->string('group')->default('general');
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Valores padrão de presenças
        DB::table('system_settings')->insert([
            ['key' => 'attendance_default_check_in',       'value' => '09:00', 'type' => 'time',    'group' => 'attendance', 'label' => 'Hora de entrada prevista',        'description' => 'Hora padrão de entrada dos funcionários.', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'attendance_late_tolerance_minutes', 'value' => '15',    'type' => 'integer', 'group' => 'attendance', 'label' => 'Tolerância de atraso (minutos)',   'description' => 'Minutos de tolerância após a hora de entrada antes de marcar atraso.', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'attendance_default_check_out',      'value' => '18:00', 'type' => 'time',    'group' => 'attendance', 'label' => 'Hora de saída prevista',           'description' => 'Hora padrão de saída dos funcionários.', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'attendance_lunch_duration_minutes', 'value' => '60',    'type' => 'integer', 'group' => 'attendance', 'label' => 'Duração do almoço (minutos)',      'description' => 'Duração padrão do intervalo de almoço.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
