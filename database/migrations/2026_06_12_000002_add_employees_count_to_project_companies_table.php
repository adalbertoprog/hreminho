<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_companies', function (Blueprint $table) {
            $table->unsignedInteger('employees_count')->default(0)->after('observacoes');
        });
    }

    public function down(): void
    {
        Schema::table('project_companies', function (Blueprint $table) {
            $table->dropColumn('employees_count');
        });
    }
};
