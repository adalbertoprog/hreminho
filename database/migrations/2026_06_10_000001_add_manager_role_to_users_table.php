<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL/MariaDB: alterar enum para incluir 'manager'
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','hr','employee','manager') NOT NULL DEFAULT 'employee'");
        }
        // SQLite não suporta ALTER COLUMN — o valor é guardado como string, funciona sem migração
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','hr','employee') NOT NULL DEFAULT 'employee'");
        }
    }
};
