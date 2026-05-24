<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailCustom;
use App\Models\AssessmentAttempt;
use App\Models\SupportTicket;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'goal',
        'proficiency',
        'learning_format',
        'learning_pace',
        'onboarded_at',
        'provider',
        'provider_id',
        'email_verified_at',
        'is_admin',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Type casting
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'onboarded_at' => 'datetime',
            'proficiency' => 'float',
            'is_admin' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Helper: has user completed onboarding?
     */
    public function hasOnboarded(): bool
    {
        return !is_null($this->onboarded_at);
    }

    /**
     * User profile relationship
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function assessmentAttempt()
    {
        return $this->hasOne(AssessmentAttempt::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailCustom);
    }
}
