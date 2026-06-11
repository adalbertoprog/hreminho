<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('docsem_empresa_id');   // ID da empresa no DocsEM
            $table->string('empresa_nome');                    // Cache do nome (evitar chamada à API)
            $table->string('empresa_nif')->nullable();         // Cache do NIF
            $table->date('data_entrada')->nullable();
            $table->date('data_saida')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'docsem_empresa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_companies');
    }
};
