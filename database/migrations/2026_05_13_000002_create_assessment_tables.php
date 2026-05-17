<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('assessment_questions')) {
            Schema::create('assessment_questions', function (Blueprint $table) {
                $table->id();
                $table->string('technology');
                $table->string('topic');
                $table->string('difficulty')->default('Intermediate');
                $table->text('question');
                $table->json('options');
                $table->string('correct_answer');
                $table->text('explanation');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('assessment_attempts')) {
            Schema::create('assessment_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('selected_goal');
                $table->json('recommended_stack');
                $table->json('question_ids');
                $table->unsignedSmallInteger('score')->nullable();
                $table->unsignedSmallInteger('total_questions')->default(25);
                $table->decimal('percentage', 5, 2)->nullable();
                $table->json('insights')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->unique('user_id');
            });
        }

        if (!Schema::hasTable('assessment_answers')) {
            Schema::create('assessment_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('assessment_attempt_id')->constrained()->cascadeOnDelete();
                $table->foreignId('assessment_question_id')->constrained()->cascadeOnDelete();
                $table->string('selected_answer');
                $table->boolean('is_correct');
                $table->timestamps();

                $table->unique(['assessment_attempt_id', 'assessment_question_id'], 'assessment_attempt_question_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_answers');
        Schema::dropIfExists('assessment_attempts');
        Schema::dropIfExists('assessment_questions');
    }
};
