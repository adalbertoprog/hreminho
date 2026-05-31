<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->unsignedSmallInteger('estimated_participants')->nullable()->after('max_participants');
            $table->decimal('cost_per_person', 10, 2)->nullable()->after('estimated_participants');
        });
    }

    public function down(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropColumn(['estimated_participants', 'cost_per_person']);
        });
    }
};
