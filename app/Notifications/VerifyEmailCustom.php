<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailCustom extends BaseVerifyEmail
{
    /**
     * Get the verify email notification mail message for the given user.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify your email to start your adaptive journey')
            ->greeting('Welcome to SkillWeave, ' . $notifiable->name . '!')
            ->line('We are excited to have you join our community. To provide you with a truly personalized learning experience that adapts to your unique progress, we first need to verify your email address.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('For your security, this verification link will expire in ' . Config::get('auth.verification.expire', 60) . ' minutes.')
            ->line('If you did not create a SkillWeave account, you can safely ignore this email.')
            ->salutation('Happy Learning, <br>The SkillWeave Team');
    }

    /**
     * Get the verification URL for the given notifiable.
     * Note: This overrides the base method to ensure we use the correct logic
     * 
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}