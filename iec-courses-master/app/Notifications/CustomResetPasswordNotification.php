<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Services\CustomEmailService;

class CustomResetPasswordNotification extends Notification
{
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['custom'];
    }

    public function toCustom($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $emailService = new CustomEmailService();

        $subject = 'Reset Password Notification';
        $body = view('email.reset-password', [
            'resetUrl' => $resetUrl,
            'user' => $notifiable
        ])->render();

        return $emailService->sendEmail(
            $notifiable->getEmailForPasswordReset(),
            $subject,
            $body
        );
    }
} 