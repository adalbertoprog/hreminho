<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Normalise any legacy 'HR' values to lowercase 'hr'
        DB::table('users')->where('role', 'HR')->update(['role' => 'hr']);

        // SQLite does not support ALTER COLUMN for enums, so we only do the
        // data normalisation above. On MySQL/MariaDB we also fix the enum
        // definition so future inserts are validated consistently.
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'hr', 'employee'])->default('employee')->change();
            });
        }
    }

    public function down(): void
    {
        // Nothing to undo — we do not want to reintroduce mixed-case values.
    }
};
