<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('url');                    // YouTube embed, Vimeo, direct MP4, etc.
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('order')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_videos');
    }
};
