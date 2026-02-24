<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailChange extends Notification
{
    use Queueable;

    public function __construct(private readonly string $url)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirm your new email address')
            ->line('We received a request to change the email address on your account.')
            ->action('Confirm Email Change', $this->url)
            ->line('If you did not request this change, you can ignore this email.');
    }
}
