<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_trainings', function (Blueprint $table) {
            // Validade em meses — findo o qual a formação deve ser renovada
            $table->unsignedSmallInteger('validity_months')->nullable()->after('end_date')
                  ->comment('Validade da formação em meses a partir da data de fim');
        });
    }

    public function down(): void
    {
        Schema::table('employee_trainings', function (Blueprint $table) {
            $table->dropColumn('validity_months');
        });
    }
};
