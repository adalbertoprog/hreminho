<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('role')->nullable(); // função na equipa (ex: encarregado, operário)
            $table->timestamps();

            $table->unique(['team_id', 'employee_id', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_employees');
    }
};
