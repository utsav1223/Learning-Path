<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AssessmentQuestionSeeder::class,
        ]);

        $user = User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => 'password',
            'goal' => 'Frontend Developer',
            'proficiency' => 42,
            'learning_format' => 'Projects first',
            'learning_pace' => 'Steady',
        ]);

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => 'Comfortable with markup, looking for more confidence in JavaScript logic and building polished UI projects.',
                'education_level' => 'College',
                'career_stage' => 'Student',
                'experience_years' => 1,
                'skill_level' => 'Beginner',
                'interests' => ['Frontend', 'Projects', 'AI'],
                'learning_goal' => 'Frontend Developer',
                'target_role' => 'Product-focused frontend engineer',
                'preferred_language' => 'English',
                'daily_learning_time' => 45,
                'weekly_days' => 5,
                'preferred_study_window' => 'Evening',
                'motivation' => 'Switch into a paid developer role',
                'project_preference' => 'Real-world dashboards',
                'support_style' => 'Mentor checkpoints',
                'strengths' => ['Design sense', 'Consistency'],
            ]
        );
    }
}
