<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('nationality');
            $table->text('address');
            $table->string('profile_photo')->nullable();
            $table->foreignId('position_id')->constrained('positions')->restrictOnDelete();
            $table->foreignId('department_id')->constrained('departments')->restrictOnDelete();
            $table->foreignId('sector_id')->nullable()->constrained('sectors')->nullOnDelete();
            $table->date('hire_date');
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->enum('contract_type', ['full-time', 'part-time', 'freelance']);
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
