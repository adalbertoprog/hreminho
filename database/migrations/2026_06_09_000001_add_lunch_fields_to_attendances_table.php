<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->time('lunch_out')->nullable()->after('check_in');  // saída para almoço
            $table->time('lunch_in')->nullable()->after('lunch_out');  // regresso do almoço
            $table->string('notes')->nullable()->after('status');      // observações
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->time('expected_check_in')->nullable()->after('work_location'); // hora de entrada prevista
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['lunch_out', 'lunch_in', 'notes']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('expected_check_in');
        });
    }
};
