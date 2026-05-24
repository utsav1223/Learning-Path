<?php

namespace Tests\Feature;

use App\Models\AssessmentQuestion;
use App\Models\Profile;
use App\Models\User;
use App\Support\LearningPlanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_onboarding_creates_a_profile_and_generates_a_one_time_assessment(): void
    {
        $user = User::factory()->create();
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
            'bio' => 'I can build static pages and want to get stronger with JavaScript.',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'learning_goal' => 'Frontend Developer',
            'target_role' => 'Frontend engineer',
        ]);

        $attempt = $user->fresh()->assessmentAttempt;
        $this->assertNotNull($attempt);
        $this->assertCount(25, $attempt->question_ids);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertSee('First complete assessment, then you can generate roadmap.');
    }

    public function test_assessment_questions_follow_the_onboarding_goal_before_global_fallback(): void
    {
        $user = User::factory()->create([
            'goal' => 'Programming Fundamentals',
            'learning_format' => 'Projects first',
            'learning_pace' => 'Steady',
            'onboarded_at' => now(),
        ]);

        Profile::create([
            'user_id' => $user->id,
            'bio' => 'New learner who needs basics and short exercises.',
            'education_level' => 'College',
            'career_stage' => 'Student',
            'experience_years' => 0,
            'skill_level' => 'Beginner',
            'interests' => ['DSA', 'Projects'],
            'learning_goal' => 'Programming Fundamentals',
            'target_role' => 'Junior developer',
            'preferred_language' => 'English',
            'daily_learning_time' => 45,
            'weekly_days' => 5,
            'preferred_study_window' => 'Evening',
            'motivation' => 'Build strong basics',
            'project_preference' => 'Mini logic apps',
            'support_style' => 'Guided checkpoints',
            'strengths' => ['Consistency'],
        ]);

        $this->seedTechnologyQuestions(['DSA', 'Projects', 'Frontend', 'Backend'], 10);
        $this->seedTechnologyQuestions(['AI/ML', 'Data Science', 'Mobile', 'DevOps'], 10);

        $attempt = LearningPlanner::ensureAttempt($user->fresh('profile'));
        $technologies = AssessmentQuestion::query()
            ->whereIn('id', $attempt->question_ids)
            ->pluck('technology')
            ->unique()
            ->values()
            ->all();

        $this->assertCount(25, $attempt->question_ids);
        $this->assertSame(['DSA', 'Projects'], LearningPlanner::recommendedTechnologiesForUser($user->fresh('profile')));
        $this->assertEmpty(array_diff($technologies, ['DSA', 'Projects', 'Frontend', 'Backend']));
    }

    public function test_assessment_submission_stores_answers_and_locks_the_result(): void
    {
        $user = User::factory()->create([
            'goal' => 'Frontend Developer',
            'learning_format' => 'Projects first',
            'learning_pace' => 'Steady',
            'onboarded_at' => now(),
        ]);

        Profile::create([
            'user_id' => $user->id,
            'bio' => 'Needs practice in JavaScript and React.',
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

        $questions = $this->seedFrontendQuestions();
        $attempt = LearningPlanner::ensureAttempt($user->fresh('profile'));

        $answers = [];
        foreach ($attempt->question_ids as $index => $questionId) {
            $question = $questions->firstWhere('id', $questionId);
            $answers[$questionId] = $index < 12 ? $question->correct_answer : $question->options[1];
        }

        $response = $this->actingAs($user)->post('/assessment', [
            'answers' => $answers,
        ]);

        $response->assertRedirect(route('dashboard'));

        $attempt = $attempt->fresh();
        $this->assertNotNull($attempt->completed_at);
        $this->assertEquals(25, $attempt->answers()->count());
        $this->assertNotNull($attempt->percentage);
        $this->assertNotEmpty($attempt->insights['topic_breakdown']);
        $this->assertEmpty($attempt->ai_roadmap);
        $this->assertEmpty($attempt->roadmap_provider);
        $this->assertEquals((int) round($attempt->percentage), (int) $user->fresh()->proficiency);

        $roadmapResponse = $this->actingAs($user)->post('/roadmap/generate');
        $roadmapResponse->assertRedirect(route('roadmap.show'));

        $attempt = $attempt->fresh();
        $this->assertNotEmpty($attempt->ai_roadmap);
        $this->assertNotEmpty($attempt->roadmap_provider);
        $this->assertNotEmpty($attempt->roadmap_generated_at);
        $this->assertNotEmpty($attempt->ai_roadmap['weekly_focus']);
        $this->assertNotEmpty($attempt->ai_roadmap['todo_sections']);
        $this->assertNotEmpty($attempt->ai_roadmap['resource_stack']);
        $this->assertNotEmpty($attempt->ai_roadmap['project_milestones']);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertSee('Generate your detailed learning path');

        $this->actingAs($user)->get('/assessment/review')
            ->assertOk()
            ->assertSee('Wrong answers to repair')
            ->assertSee('Your answer')
            ->assertSee('Correct answer')
            ->assertSee('Needs review');

        $this->actingAs($user)->get('/roadmap')
            ->assertOk()
            ->assertSee('Detailed study timeline');
    }

    public function test_expired_assessment_can_auto_submit_with_unanswered_questions(): void
    {
        $user = User::factory()->create([
            'goal' => 'Frontend Developer',
            'learning_format' => 'Projects first',
            'learning_pace' => 'Steady',
            'onboarded_at' => now(),
        ]);

        Profile::create([
            'user_id' => $user->id,
            'bio' => 'Needs practice in JavaScript and React.',
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

        $questions = $this->seedFrontendQuestions();
        $attempt = LearningPlanner::ensureAttempt($user->fresh('profile'));
        $firstQuestionId = $attempt->question_ids[0];

        $response = $this->actingAs($user)->post('/assessment', [
            'auto_submitted' => true,
            'answers' => [
                $firstQuestionId => $questions->firstWhere('id', $firstQuestionId)->correct_answer,
            ],
        ]);

        $response->assertRedirect(route('dashboard'));

        $attempt = $attempt->fresh();
        $this->assertNotNull($attempt->completed_at);
        $this->assertEquals(25, $attempt->answers()->count());
        $this->assertEquals(1, $attempt->score);
        $this->assertEquals(4.0, $attempt->percentage);
    }

    private function seedFrontendQuestions()
    {
        return collect(range(1, 30))->map(function ($number) {
            return AssessmentQuestion::create([
                'technology' => 'Frontend',
                'topic' => $number % 2 === 0 ? 'CSS' : 'JavaScript',
                'difficulty' => 'Beginner',
                'question' => "Frontend question {$number}?",
                'options' => ['Correct', "Option {$number}A", "Option {$number}B", "Option {$number}C"],
                'correct_answer' => 'Correct',
                'explanation' => 'Because this is the seeded correct answer.',
                'is_active' => true,
            ]);
        });
    }

    private function seedTechnologyQuestions(array $technologies, int $countPerTechnology)
    {
        return collect($technologies)->flatMap(function (string $technology) use ($countPerTechnology) {
            return collect(range(1, $countPerTechnology))->map(function ($number) use ($technology) {
                return AssessmentQuestion::create([
                    'technology' => $technology,
                    'topic' => "{$technology} Topic {$number}",
                    'difficulty' => 'Beginner',
                    'question' => "{$technology} question {$number}?",
                    'options' => ['Correct', "Option {$number}A", "Option {$number}B", "Option {$number}C"],
                    'correct_answer' => 'Correct',
                    'explanation' => 'Because this is the seeded correct answer.',
                    'is_active' => true,
                ]);
            });
        });
    }
}
