<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('profiles', function (Blueprint $table) {

        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade');

        $table->text('bio')->nullable();

        $table->enum('education_level', [
            'School',
            'College',
            'Graduate',
            'Professional'
        ])->nullable();

        $table->enum('skill_level', [
            'Beginner',
            'Intermediate',
            'Advanced'
        ]);

        $table->json('interests');

        $table->string('learning_goal');

        $table->string('preferred_language')
            ->default('English');

        $table->integer('daily_learning_time')
            ->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
