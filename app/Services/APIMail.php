<?php

namespace App\Services;

use App\Helpers\BrevoHelper;
use App\Mail\VerifyEmail;
use App\Notifications\AccountNotification;
use App\Notifications\AddedToProjectNotification;
use Illuminate\Support\Facades\Log;

class APIMail
{
    public function sendMail($user, $type = '')
    {
        try {
            BrevoHelper::sendMailable(
                new VerifyEmail($user),
                $user->email,
                $user->full_name
            );

            if ($type == '') $user->notify(new AccountNotification());
        } catch (\Throwable $e) {
            Log::error("Email sending failed", ['message' => $e->getMessage()]);
        }
    }

    public function sendProjectMail($project)
    {
        if (!setting('notify_project_award', true)) {
            return;
        }

        try {
            $project->contractor?->user?->notify(new AddedToProjectNotification($project, 'Contractor'));
            $project->consultant?->user?->notify(new AddedToProjectNotification($project, 'Consultant'));

            $auditEmail = setting('audit_notification_email');
            if ($auditEmail && filter_var($auditEmail, FILTER_VALIDATE_EMAIL)) {
                \Illuminate\Support\Facades\Notification::route('mail', $auditEmail)
                    ->notify(new AddedToProjectNotification($project, 'Procurement/Audit Oversight'));
            }
        } catch (\Throwable $e) {
            Log::error("Project Email sending failed", ['message' => $e->getMessage()]);
        }
    }


    public function sendProjectPersonnelMail($project, $personnel, $role)
    {
        try {
            $personnel?->user?->notify(new AddedToProjectNotification($project, ucfirst(str_replace(['_', ' '], ' ', $role->name))));
        } catch (\Throwable $e) {
            Log::error("Project Email sending failed", ['message' => $e->getMessage()]);
        }
    }
}
