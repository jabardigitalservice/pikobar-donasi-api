<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Password Reset Request')
            ->greeting('Hello, ' . $notifiable->username)
            ->line('You are receiving this email because we received a password reset request for your account. Click the button below to reset your password:')
            ->action('Reset Password', url('password/reset', $this->token) . '?email=' . urlencode($notifiable->email))
            ->line('If you did not request a password reset, no further action is required.')
            ->line('Thank you for using ' . config('app.name'));

    }
}
