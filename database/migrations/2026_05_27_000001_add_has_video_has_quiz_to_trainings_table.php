<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->boolean('has_video')->default(false)->after('provider');
            $table->boolean('has_quiz')->default(false)->after('has_video');
        });
    }

    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn(['has_video', 'has_quiz']);
        });
    }
};
