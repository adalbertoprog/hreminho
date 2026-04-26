<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->change();
            $table->string('gender')->nullable()->change();
            $table->string('nationality')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('contract_type')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable(false)->change();
            $table->enum('gender', ['male', 'female', 'other'])->nullable(false)->change();
            $table->string('nationality')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->enum('contract_type', ['full-time', 'part-time', 'freelance'])->nullable(false)->change();
        });
    }
};
