<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Services\BrevoMailer;


/**
 * @method array toBrevo($notifiable)
 */

class BrevoChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toBrevo')) {
            return;
        }

        $message = $notification->toBrevo($notifiable);

        $brevo = new BrevoMailer();
        $brevo->sendEmail(
            $message['email'],
            $message['name'],
            $message['subject'],
            $message['html']
        );
    }
}
