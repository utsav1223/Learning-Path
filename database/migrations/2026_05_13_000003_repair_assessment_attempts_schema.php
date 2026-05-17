<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('assessment_attempts')) {
            return;
        }

        Schema::table('assessment_attempts', function (Blueprint $table) {
            if (!Schema::hasColumn('assessment_attempts', 'selected_goal')) {
                $table->string('selected_goal')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('assessment_attempts', 'recommended_stack')) {
                $table->json('recommended_stack')->nullable()->after('selected_goal');
            }

            if (!Schema::hasColumn('assessment_attempts', 'question_ids')) {
                $table->json('question_ids')->nullable()->after('recommended_stack');
            }

            if (!Schema::hasColumn('assessment_attempts', 'score')) {
                $table->unsignedSmallInteger('score')->nullable()->after('question_ids');
            }

            if (!Schema::hasColumn('assessment_attempts', 'total_questions')) {
                $table->unsignedSmallInteger('total_questions')->default(25)->after('score');
            }

            if (!Schema::hasColumn('assessment_attempts', 'percentage')) {
                $table->decimal('percentage', 5, 2)->nullable()->after('total_questions');
            }

            if (!Schema::hasColumn('assessment_attempts', 'insights')) {
                $table->json('insights')->nullable()->after('percentage');
            }

            if (!Schema::hasColumn('assessment_attempts', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('insights');
            }
        });
    }

    public function down(): void
    {
    }
};
