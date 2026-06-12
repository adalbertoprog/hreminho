<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // ID da obra correspondente no DocsElectro-Minho (nullable — obras locais nao tem ligacao)
            $table->unsignedBigInteger('docsem_obra_id')->nullable()->after('id');
            // Data da ultima sincronizacao com o DocsEM
            $table->timestamp('docsem_synced_at')->nullable()->after('docsem_obra_id');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['docsem_obra_id', 'docsem_synced_at']);
        });
    }
};
