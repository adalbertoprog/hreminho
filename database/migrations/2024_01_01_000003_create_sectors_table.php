<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sectors', function (Blueprint $table) {
            $table->id();
            $table->string('sector');
            $table->foreignId('department_id')->constrained('departments')->restrictOnDelete();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sectors');
    }
};
