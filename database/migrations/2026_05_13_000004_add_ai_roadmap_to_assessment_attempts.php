<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {
            if (!Schema::hasColumn('assessment_attempts', 'ai_roadmap')) {
                $table->json('ai_roadmap')->nullable()->after('insights');
            }

            if (!Schema::hasColumn('assessment_attempts', 'roadmap_provider')) {
                $table->string('roadmap_provider')->nullable()->after('ai_roadmap');
            }

            if (!Schema::hasColumn('assessment_attempts', 'roadmap_generated_at')) {
                $table->timestamp('roadmap_generated_at')->nullable()->after('roadmap_provider');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assessment_attempts', function (Blueprint $table) {
            $drops = [];

            foreach (['ai_roadmap', 'roadmap_provider', 'roadmap_generated_at'] as $column) {
                if (Schema::hasColumn('assessment_attempts', $column)) {
                    $drops[] = $column;
                }
            }

            if ($drops !== []) {
                $table->dropColumn($drops);
            }
        });
    }
};
