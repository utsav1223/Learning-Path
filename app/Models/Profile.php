<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'education_level',
        'career_stage',
        'experience_years',
        'skill_level',
        'interests',
        'learning_goal',
        'target_role',
        'preferred_language',
        'daily_learning_time',
        'weekly_days',
        'preferred_study_window',
        'motivation',
        'project_preference',
        'support_style',
        'strengths',
    ];

    protected $casts = [
        'interests' => 'array',
        'strengths' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
