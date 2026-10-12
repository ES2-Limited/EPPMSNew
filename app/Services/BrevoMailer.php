<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrevoMailer
{
    public function sendEmail($toEmail, $toName, $subject, $htmlContent)
    {
        $response = Http::withHeaders([
            'api-key' => env('BREVO_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => env('APP_NAME'),
                'email' => env('MAIL_FROM_ADDRESS'),
            ],
            'to' => [[
                'email' => $toEmail,
                'name' => $toName,
            ]],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ]);

        return $response->json();
    }
}
