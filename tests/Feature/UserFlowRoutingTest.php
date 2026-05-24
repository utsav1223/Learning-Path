<?php

namespace Tests\Feature;

use App\Models\AssessmentAttempt;
use App\Models\Profile;
use App\Models\User;
use App\Support\LearningPlanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFlowRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_user_without_onboarding_is_sent_to_onboarding(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('onboarding'));
    }

    public function test_logged_in_user_with_pending_assessment_is_sent_to_dashboard(): void
    {
        $user = $this->makeOnboardedUser();
        AssessmentAttempt::create([
            'user_id' => $user->id,
            'selected_goal' => 'Frontend Developer',
            'recommended_stack' => ['HTML', 'CSS'],
            'question_ids' => [],
            'total_questions' => 25,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
    }

    public function test_login_uses_existing_profile_and_completed_assessment_if_onboarded_timestamp_is_missing(): void
    {
        $user = $this->makeOnboardedUser();
        $user->forceFill(['onboarded_at' => null])->save();

        AssessmentAttempt::create([
            'user_id' => $user->id,
            'selected_goal' => 'Frontend Developer',
            'recommended_stack' => ['HTML', 'CSS'],
            'question_ids' => [],
            'score' => 20,
            'total_questions' => 25,
            'percentage' => 80,
            'insights' => ['weak_areas' => ['CSS'], 'strong_areas' => ['HTML'], 'topic_breakdown' => []],
            'completed_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertNotNull($user->fresh()->onboarded_at);
    }

    public function test_existing_assessment_allows_profile_changes_and_resets_the_attempt(): void
    {
        $user = $this->makeOnboardedUser();
        $oldAttempt = AssessmentAttempt::create([
            'user_id' => $user->id,
            'selected_goal' => 'Frontend Developer',
            'recommended_stack' => ['HTML', 'CSS'],
            'question_ids' => [],
            'score' => 18,
            'total_questions' => 25,
            'percentage' => 72,
            'insights' => ['weak_areas' => ['CSS'], 'strong_areas' => ['HTML'], 'topic_breakdown' => []],
            'completed_at' => now(),
        ]);

        $this->seedFrontendQuestions();

        $response = $this->actingAs($user)->post('/onboarding', [
            'education_level' => 'College',
            'career_stage' => 'Student',
            'experience_years' => 1,
            'skill_level' => 'Beginner',
            'interests' => ['Frontend', 'Projects'],
            'learning_goal' => 'Frontend Developer',
            'target_role' => 'Frontend engineer',
            'preferred_language' => 'English',
            'daily_learning_time' => 45,
            'weekly_days' => 5,
            'preferred_study_window' => 'Evening',
            'motivation' => 'Get job-ready',
            'project_preference' => 'Real-world dashboards',
            'support_style' => 'Mentor checkpoints',
            'strengths' => ['Consistency', 'Design sense'],
            'learning_format' => 'Projects first',
            'learning_pace' => 'Steady',
            'bio' => 'Updating the learner profile.',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseMissing('assessment_attempts', ['id' => $oldAttempt->id]);

        $newAttempt = $user->fresh()->assessmentAttempt;
        $this->assertNotNull($newAttempt);
        $this->assertFalse($newAttempt->is($oldAttempt));
        $this->assertNull($newAttempt->completed_at);
        $this->assertSame('Updating the learner profile.', $user->fresh('profile')->profile->bio);
    }

    public function test_dashboard_handles_completed_assessment_without_answer_rows(): void
    {
        $user = $this->makeOnboardedUser();
        AssessmentAttempt::create([
            'user_id' => $user->id,
            'selected_goal' => 'Frontend Developer',
            'recommended_stack' => ['HTML', 'CSS'],
            'question_ids' => [],
            'score' => 0,
            'total_questions' => 25,
            'percentage' => 0,
            'insights' => ['weak_areas' => ['HTML'], 'strong_areas' => [], 'topic_breakdown' => []],
            'completed_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Action queue');
    }

    public function test_assessment_review_handles_completed_assessment_without_answer_rows(): void
    {
        $user = $this->makeOnboardedUser();
        AssessmentAttempt::create([
            'user_id' => $user->id,
            'selected_goal' => 'Frontend Developer',
            'recommended_stack' => ['HTML', 'CSS'],
            'question_ids' => [],
            'score' => 0,
            'total_questions' => 25,
            'percentage' => 0,
            'insights' => ['weak_areas' => ['HTML'], 'strong_areas' => [], 'topic_breakdown' => []],
            'completed_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/assessment/review')
            ->assertOk()
            ->assertSee('Wrong answers to repair');
    }

    private function makeOnboardedUser(): User
    {
        $user = User::factory()->create([
            'goal' => 'Frontend Developer',
            'learning_format' => 'Projects first',
            'learning_pace' => 'Steady',
            'onboarded_at' => now(),
        ]);

        Profile::create([
            'user_id' => $user->id,
            'bio' => 'Comfortable with markup and wants stronger JavaScript.',
            'education_level' => 'College',
            'career_stage' => 'Student',
            'experience_years' => 1,
            'skill_level' => 'Beginner',
            'interests' => ['Frontend', 'Projects'],
            'learning_goal' => 'Frontend Developer',
            'target_role' => 'Frontend engineer',
            'preferred_language' => 'English',
            'daily_learning_time' => 45,
            'weekly_days' => 5,
            'preferred_study_window' => 'Evening',
            'motivation' => 'Get job-ready',
            'project_preference' => 'Real-world dashboards',
            'support_style' => 'Mentor checkpoints',
            'strengths' => ['Consistency', 'Design sense'],
        ]);

        return $user;
    }

    private function seedFrontendQuestions(): void
    {
        if (!class_exists(\Database\Seeders\AssessmentQuestionSeeder::class)) {
            return;
        }

        $this->seed(\Database\Seeders\AssessmentQuestionSeeder::class);
    }
}
