<?php

namespace App\Notifications;

use App\Notifications\Channels\BrevoChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AccountNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct() {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [BrevoChannel::class, 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->cc('ncaa.eppms@gmail.com')
    //                 ->line('Welcome to NCAA.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    public function toBrevo(object $notifiable): array
    {
        return [
            'email' => $notifiable->email,
            'name' => $notifiable->fullname,
            'subject' => 'Welcome to NCAA',
            'html' => view('emails.account-welcome', [
                'user' => $notifiable
            ])->render(),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => "Account Created",
            'message' => 'New account with role created successfully.',
        ];
    }
}
