<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('proficiency')->nullable()->after('goal');
            $table->string('learning_format')->nullable()->after('proficiency');
            $table->string('learning_pace')->nullable()->after('learning_format');
            $table->timestamp('onboarded_at')->nullable()->after('learning_pace');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'proficiency',
                'learning_format',
                'learning_pace',
                'onboarded_at',
            ]);
        });
    }
};
