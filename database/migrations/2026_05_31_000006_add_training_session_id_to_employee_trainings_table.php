<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_trainings', function (Blueprint $table) {
            $table->foreignId('training_session_id')
                  ->nullable()
                  ->after('training_id')
                  ->constrained('training_sessions')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('employee_trainings', function (Blueprint $table) {
            $table->dropForeign(['training_session_id']);
            $table->dropColumn('training_session_id');
        });
    }
};
