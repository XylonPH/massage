<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetLink extends Notification
{
    public function __construct(
        #[\SensitiveParameter]
        private readonly string $token,
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject(__('auth.password_reset_email_subject'))
            ->line(__('auth.password_reset_email_intro'))
            ->action(__('auth.password_reset_email_action'), $url)
            ->line(__('auth.password_reset_email_expiry', [
                'minutes' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
            ]))
            ->line(__('auth.password_reset_email_ignore'));
    }
}
