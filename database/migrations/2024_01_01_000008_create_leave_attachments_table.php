<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_id')->constrained('leaves')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->enum('file_type', ['pdf', 'jpg', 'png']);
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_attachments');
    }
};
