<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // ✅ add this
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailCustom;

class User extends Authenticatable implements MustVerifyEmail // ✅ implement
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
        'provider',        // 🔥 for OAuth (optional but recommended)
        'provider_id',     // 🔥 for OAuth (optional but recommended)
        'email_verified_at', // allow setting this for Google users
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

    

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailCustom);
    }
}