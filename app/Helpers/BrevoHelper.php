<?php

namespace App\Helpers;

use Illuminate\Mail\Mailable;
use App\Services\BrevoMailer;

class BrevoHelper
{
    /**
     * Send any Laravel Mailable via Brevo API.
     *
     * @param Mailable $mailable The mailable instance
     * @param string $toEmail Recipient's email
     * @param string $toName Recipient's name
     * @return array Brevo API response
     */
    public static function sendMailable(Mailable $mailable, string $toEmail, string $toName): array
    {
        $subject = method_exists($mailable, 'envelope')
            ? $mailable->envelope()->subject
            : 'Notification from '.env('APP_NAME');

        $htmlContent = $mailable->render();

        $mailer = app(BrevoMailer::class);

        return $mailer->sendEmail($toEmail, $toName, $subject, $htmlContent);
    }
}
