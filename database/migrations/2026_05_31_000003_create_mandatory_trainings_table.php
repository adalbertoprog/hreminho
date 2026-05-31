<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mandatory_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->cascadeOnDelete();

            // Âmbito: 'all' = todos os funcionários, 'department' = por departamento, 'position' = por cargo
            $table->enum('target_type', ['all', 'department', 'position'])->default('all');
            $table->unsignedBigInteger('target_id')->nullable(); // id do department ou position

            // Prazo em dias após contratação (null = sem prazo definido)
            $table->unsignedInteger('deadline_days')->nullable();

            $table->string('notes')->nullable();
            $table->timestamps();

            // Unicidade: mesma formação não pode ser obrigatória duas vezes para o mesmo âmbito
            $table->unique(['training_id', 'target_type', 'target_id'], 'unique_mandatory');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mandatory_trainings');
    }
};
