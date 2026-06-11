<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate')->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->year('year')->nullable();
            $table->enum('type', ['van', 'truck', 'car', 'other'])->default('van');
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
