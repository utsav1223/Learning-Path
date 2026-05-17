<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'selected_goal',
        'recommended_stack',
        'question_ids',
        'score',
        'total_questions',
        'percentage',
        'insights',
        'ai_roadmap',
        'roadmap_provider',
        'roadmap_generated_at',
        'completed_at',
    ];

    protected $casts = [
        'recommended_stack' => 'array',
        'question_ids' => 'array',
        'insights' => 'array',
        'ai_roadmap' => 'array',
        'roadmap_generated_at' => 'datetime',
        'completed_at' => 'datetime',
        'percentage' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }
}
