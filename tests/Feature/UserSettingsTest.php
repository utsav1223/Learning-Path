<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_settings_and_update_account_name(): void
    {
        $user = $this->makeOnboardedUser();

        $this->actingAs($user)
            ->get('/settings')
            ->assertOk()
            ->assertSee('Manage your profile and security')
            ->assertSee('Change password');

        $this->actingAs($user)
            ->patch('/settings/account', [
                'name' => 'Updated Learner',
            ])
            ->assertRedirect();

        $this->assertSame('Updated Learner', $user->fresh()->name);
    }

    public function test_user_can_change_password_from_settings(): void
    {
        $user = $this->makeOnboardedUser();

        $this->actingAs($user)
            ->patch('/settings/password', [
                'current_password' => 'password',
                'password' => 'NewPass123',
                'password_confirmation' => 'NewPass123',
            ])
            ->assertRedirect();

        $this->assertTrue(Hash::check('NewPass123', $user->fresh()->password));
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
}
