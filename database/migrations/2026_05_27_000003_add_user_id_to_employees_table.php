<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')
                  ->constrained('users')->nullOnDelete();
        });

        // Auto-populate: match existing employees to users by email
        // (sintaxe sem alias — compatível com MySQL e SQLite)
        DB::statement('
            UPDATE employees
            SET user_id = (
                SELECT id FROM users
                WHERE users.email = employees.email
                LIMIT 1
            )
            WHERE email IS NOT NULL
        ');
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
