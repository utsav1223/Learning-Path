# Database Concepts and Application Flow

This document explains what data the application stores, how the main tables are connected, and how data moves through onboarding, assessment, dashboard, and AI roadmap generation.

## Big Picture

The app is a personalized learning platform. A user creates an account, completes onboarding, takes an assessment, and then gets a dashboard plus a roadmap generated from their profile and assessment results.

The database stores four main kinds of data:

- Account data: who the user is and how they log in.
- Profile data: what the user wants to learn and how they prefer to study.
- Assessment data: questions, selected answers, score, weak areas, and strong areas.
- Roadmap data: AI-generated or fallback learning plan based on onboarding plus assessment.

## Main Tables

### users

The `users` table stores the core account record.

Important fields:

| Field | Purpose |
| --- | --- |
| `id` | Primary key for the user. Other tables connect back to this. |
| `name` | User display name. |
| `email` | Login email. Must be unique. |
| `password` | Hashed password, never stored as plain text. |
| `email_verified_at` | Shows whether the user verified email. |
| `goal` | General learning goal stored on the user. |
| `proficiency` | Latest assessment percentage rounded into a user-level score. |
| `learning_format` | Preferred format, such as project-first or mixed learning. |
| `learning_pace` | User pace, such as steady or fast. |
| `onboarded_at` | Timestamp showing onboarding is completed. |
| `remember_token` | Used by Laravel remember-me login. |

Relationships:

- One user has one profile.
- One user has one assessment attempt.
- If a user is deleted, their profile and assessment data are deleted through cascading relationships.

Model methods:

- `profile()` connects `users.id` to `profiles.user_id`.
- `assessmentAttempt()` connects `users.id` to `assessment_attempts.user_id`.
- `hasOnboarded()` checks if `onboarded_at` is filled.

## profiles

The `profiles` table stores onboarding and personalization details.

Important fields:

| Field | Purpose |
| --- | --- |
| `user_id` | Foreign key connecting the profile to one user. |
| `bio` | Short learner background. |
| `education_level` | School, college, graduate, or professional. |
| `career_stage` | Student, working professional, career switcher, etc. |
| `experience_years` | User experience level in years. |
| `skill_level` | Beginner, intermediate, or advanced. |
| `interests` | JSON array of selected interests. |
| `learning_goal` | Main selected goal from onboarding. |
| `target_role` | Role the learner wants, such as frontend engineer. |
| `preferred_language` | Learning language preference. |
| `daily_learning_time` | Minutes available per study day. |
| `weekly_days` | How many days per week the user can study. |
| `preferred_study_window` | Preferred time of day. |
| `motivation` | Why the user wants to learn. |
| `project_preference` | Preferred project style. |
| `support_style` | How much guidance the user wants. |
| `strengths` | JSON array of self-reported strengths. |

Why this table exists:

The `users` table should stay focused on account identity. The `profiles` table stores learning personalization, so the app can update learning preferences without mixing them with login data.

## assessment_questions

The `assessment_questions` table is the question bank.

Important fields:

| Field | Purpose |
| --- | --- |
| `technology` | Broad category, such as Frontend, Backend, DSA, AI/ML, Projects. |
| `topic` | Specific topic, such as HTML, CSS, JavaScript, Arrays, Laravel. |
| `difficulty` | Beginner, intermediate, or advanced. |
| `question` | The question text shown to the user. |
| `options` | JSON array of multiple-choice options. |
| `correct_answer` | Correct option text. |
| `explanation` | Explanation shown during review. |
| `is_active` | Controls whether the question can be selected. |

How it is used:

During onboarding, the app maps the user's goal and interests to technologies. Then it selects active questions from matching technology categories. For example:

- Frontend Developer maps to Frontend, Projects, and Full Stack related questions.
- Programming Fundamentals maps to DSA and Projects, then related beginner web/backend basics if more questions are needed.
- AI and Machine Learning maps to AI/ML, Data Science, and Projects.

## assessment_attempts

The `assessment_attempts` table stores one assessment session per user.

Important fields:

| Field | Purpose |
| --- | --- |
| `user_id` | The user who owns the attempt. Unique, so one current attempt exists per user. |
| `selected_goal` | Goal used when the attempt was created. |
| `recommended_stack` | JSON array of the recommended stack from onboarding. |
| `question_ids` | JSON array of selected question IDs. This freezes the assessment question set. |
| `score` | Number of correct answers. |
| `total_questions` | Total number of questions, normally 25. |
| `percentage` | Final assessment percentage. |
| `insights` | JSON summary of weak areas, strong areas, and topic breakdown. |
| `ai_roadmap` | JSON roadmap generated by Gemini or local fallback. |
| `roadmap_provider` | `gemini` or `fallback`. |
| `roadmap_generated_at` | Timestamp of roadmap generation. |
| `completed_at` | Timestamp showing assessment is complete. |

Why `question_ids` is stored:

The app stores the selected question IDs so the assessment does not change if the question bank changes later. A user should continue the same assessment after refresh, and the review page should show exactly the questions they answered.

Why `insights` is stored:

The dashboard and roadmap need quick access to weak areas and topic scores. Instead of recalculating every page load, the app saves this structured summary after assessment submission.

Example `insights` shape:

```json
{
  "weak_areas": ["JavaScript", "CSS"],
  "strong_areas": ["HTML", "Accessibility"],
  "topic_breakdown": [
    {
      "topic": "JavaScript",
      "correct": 2,
      "wrong": 4,
      "score": 33
    }
  ]
}
```

## assessment_answers

The `assessment_answers` table stores every answer selected by the user.

Important fields:

| Field | Purpose |
| --- | --- |
| `assessment_attempt_id` | Foreign key to the attempt. |
| `assessment_question_id` | Foreign key to the question. |
| `selected_answer` | The answer selected by the user. |
| `is_correct` | Boolean result after comparing with `correct_answer`. |

Relationships:

- One assessment attempt has many assessment answers.
- One assessment answer belongs to one assessment question.

Why this table exists:

The attempt stores the final result, but answers store the detail. This is required for:

- Review wrong answers.
- Show "Your answer" and "Correct answer".
- Build weak-area insights.
- Dashboard action queue.

## Table Relationships

```text
users
  id
  |
  | one-to-one
  v
profiles
  user_id

users
  id
  |
  | one-to-one
  v
assessment_attempts
  user_id
  id
  |
  | one-to-many
  v
assessment_answers
  assessment_attempt_id
  assessment_question_id
  |
  | many-to-one
  v
assessment_questions
  id
```

Simple explanation:

- `users` is the owner.
- `profiles` stores onboarding details for that user.
- `assessment_attempts` stores the user's selected assessment and result.
- `assessment_answers` stores what the user selected for each question.
- `assessment_questions` stores the master question bank.

## Full Data Flow

### 1. Registration and Login

When a user registers:

1. A record is created in `users`.
2. The password is hashed.
3. The user verifies email.
4. Laravel stores the login session in `sessions`.

At this point, the user may not have a profile or assessment yet.

### 2. Onboarding

When the user submits onboarding:

1. The app creates or updates `profiles`.
2. The app updates `users.goal`, `users.learning_format`, `users.learning_pace`, and `users.onboarded_at`.
3. The app reads onboarding config from `public/js/onboarding/config/onboarding.json`.
4. `LearningPlanner` creates the recommended stack.
5. The app creates an `assessment_attempts` row.
6. The attempt stores selected `question_ids`.

Important behavior:

If the user edits onboarding later, the old assessment attempt is reset so the new roadmap can match the new profile.

### 3. Assessment Start

When the user opens `/assessment`:

1. The app loads the current `assessment_attempts` row.
2. The app reads the frozen `question_ids`.
3. It loads matching rows from `assessment_questions`.
4. Questions and shuffled options are shown to the user.

The assessment is connected to onboarding because the question set was created from the user's selected goal, stack, assessment coverage, interests, and target role.

### 4. Assessment Submit

When the user submits answers:

1. The app compares each selected answer with `assessment_questions.correct_answer`.
2. Each answer is saved in `assessment_answers`.
3. The app calculates:
   - total score
   - percentage
   - weak areas
   - strong areas
   - topic-by-topic breakdown
4. The app updates `assessment_attempts.score`, `percentage`, `insights`, and `completed_at`.
5. The app updates `users.proficiency`.

After this, the assessment is complete and the user can generate a roadmap.

### 5. Dashboard

When the user opens `/dashboard`, the dashboard reads:

- `users` for account and proficiency.
- `profiles` for goals and preferences.
- `assessment_attempts` for score, insights, and roadmap status.
- `assessment_answers` with `assessment_questions` for wrong answer review.

The dashboard uses this data to show:

- Assessment status.
- Weak areas.
- Topic analytics.
- Action queue.
- Roadmap generation status.
- Learning progress UI.

### 6. Roadmap Generation

When the user clicks generate roadmap:

1. The app checks that assessment is completed.
2. The app reads the user profile.
3. The app reads assessment score and insights.
4. The app builds a detailed prompt using:
   - learning goal
   - target role
   - skill level
   - study time
   - weak areas
   - strong areas
   - topic breakdown
   - recommended stack
5. If Gemini is enabled and the API key exists, the app calls Gemini.
6. If Gemini fails or is disabled, the app creates a fallback roadmap locally.
7. The final roadmap JSON is saved in `assessment_attempts.ai_roadmap`.

The roadmap is based on both onboarding and assessment:

- Onboarding decides the goal, target role, schedule, preferences, and initial stack.
- Assessment decides weak areas, strong areas, score, and what to practice first.

## Why JSON Columns Are Used

The app uses JSON columns for flexible structured data:

| Column | Why JSON is useful |
| --- | --- |
| `profiles.interests` | A user can select multiple interests. |
| `profiles.strengths` | A user can select multiple strengths. |
| `assessment_questions.options` | Each question has multiple options. |
| `assessment_attempts.recommended_stack` | The stack can have different length depending on goal. |
| `assessment_attempts.question_ids` | A fixed list of selected question IDs. |
| `assessment_attempts.insights` | Stores structured analytics without needing many extra tables. |
| `assessment_attempts.ai_roadmap` | Stores generated roadmap sections, weeks, todos, resources, and projects. |

This keeps the schema simpler while still allowing rich dashboard and roadmap data.

## Important Business Rules

### One current assessment attempt per user

`assessment_attempts.user_id` is unique. That means one user has one active/current attempt.

Reason:

- Prevents multiple conflicting assessments.
- Keeps dashboard and roadmap logic simple.
- Profile changes can reset the attempt when needed.

### Roadmap cannot be generated before assessment completion

The roadmap needs assessment insights. If no completed assessment exists, the app sends the user to the dashboard with a message to complete assessment first.

### Onboarding can be edited

The profile is not locked. A user can edit onboarding/profile details. When they do, the app can create a fresh assessment and roadmap path based on the new goal.

### Assessment review comes from stored answers

The review page does not guess wrong answers from score. It reads `assessment_answers` and joins each answer to its `assessment_question`.

## Example End-to-End Record Flow

Example learner:

- Goal: Frontend Developer
- Interests: Frontend, Projects
- Skill level: Beginner
- Daily time: 45 minutes

Flow:

1. `users` stores account and general learning settings.
2. `profiles` stores Frontend Developer, interests, target role, and study schedule.
3. `LearningPlanner` maps the goal to Frontend-related technologies.
4. `assessment_attempts.question_ids` stores 25 selected question IDs.
5. The user answers questions.
6. `assessment_answers` stores each selected option and correctness.
7. `assessment_attempts.insights` stores weak areas like JavaScript or CSS.
8. Dashboard highlights those weak areas.
9. Roadmap generation uses profile plus insights.
10. `assessment_attempts.ai_roadmap` stores the final weekly plan, todos, resources, and project milestones.

## Controller Responsibility Summary

| Controller | Database role |
| --- | --- |
| `OnboardingController` | Creates/updates profile, updates user onboarding fields, resets/regenerates assessment attempt when needed. |
| `AssessmentController` | Shows assigned questions, stores answers, calculates score and insights, shows review. |
| `DashboardController` | Reads profile, attempt, answers, insights, and roadmap status for dashboard UI. |
| `RoadmapController` | Checks assessment completion, generates roadmap, saves provider and roadmap JSON. |
| `UserSettingsController` | Updates account name and password. |

## Short Explanation for Presentation

The database is designed around the learner. The `users` table stores login and account information. The `profiles` table stores onboarding data such as goal, skill level, interests, study time, and target role. Based on this profile, the system creates one `assessment_attempts` record and stores the selected question IDs. The actual questions come from the `assessment_questions` table.

When the user submits the assessment, every selected option is stored in `assessment_answers`. The system calculates the score and builds insights like weak areas, strong areas, and topic breakdown. These insights are saved in the assessment attempt.

The dashboard reads the user profile, assessment attempt, answer details, and roadmap data to show progress and weak areas. The roadmap generator uses both onboarding and assessment results, sends them to Gemini when available, and saves the generated roadmap in the assessment attempt. This is how the app connects onboarding, assessment, dashboard, and roadmap into one personalized learning flow.
