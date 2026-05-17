<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('career_stage')->nullable()->after('education_level');
            $table->unsignedTinyInteger('experience_years')->nullable()->after('career_stage');
            $table->string('target_role')->nullable()->after('learning_goal');
            $table->unsignedTinyInteger('weekly_days')->nullable()->after('daily_learning_time');
            $table->string('preferred_study_window')->nullable()->after('weekly_days');
            $table->string('motivation')->nullable()->after('preferred_study_window');
            $table->string('project_preference')->nullable()->after('motivation');
            $table->string('support_style')->nullable()->after('project_preference');
            $table->json('strengths')->nullable()->after('support_style');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'career_stage',
                'experience_years',
                'target_role',
                'weekly_days',
                'preferred_study_window',
                'motivation',
                'project_preference',
                'support_style',
                'strengths',
            ]);
        });
    }
};
