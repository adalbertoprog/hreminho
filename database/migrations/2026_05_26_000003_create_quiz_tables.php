<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One quiz per training (1-to-1, but stored separately for clarity)
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('passing_score')->default(70); // percentage
            $table->timestamps();
        });

        // Questions: multiple-choice (mc) or true/false (tf)
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->enum('type', ['mc', 'tf']); // multiple-choice | true/false
            $table->unsignedSmallInteger('order')->default(1);
            $table->timestamps();
        });

        // Answer options for each question
        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();
            $table->string('text');
            $table->boolean('is_correct')->default(false);
            $table->unsignedTinyInteger('order')->default(1);
            $table->timestamps();
        });

        // Each attempt by an employee at a given quiz
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('score')->nullable();        // 0-100 percentage
            $table->boolean('passed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Individual answers per attempt
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();
            $table->foreignId('option_id')->constrained('quiz_options')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_options');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
