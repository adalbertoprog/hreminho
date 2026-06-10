<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('leave_id')
                  ->nullable()
                  ->after('notes')
                  ->constrained('leaves')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Leave::class);
            $table->dropColumn('leave_id');
        });
    }
};
