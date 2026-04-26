<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->enum('status', ['enrolled', 'completed', 'failed'])->default('enrolled');
            $table->string('certificate_path')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_trainings');
    }
};
