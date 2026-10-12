<?php

namespace App\Notifications;

use App\Notifications\Channels\BrevoChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddedToProjectNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $project, public $type) {}

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
    //     $creator = $this->project?->creator;
    //     $creator_name = ($creator?->name != '') ? $creator?->name : $creator?->first_name .' '. $creator?->last_name;
    //     return (new MailMessage)
    //         ->subject('You were added to a new project')
    //         ->greeting("Hello  {$this->type}, ")
    //         ->line("You've been added to the project: {$this->project->name}")
    //         ->action('View Project', url("/projects/{$this->project->ulid}"))
    //         ->line("Added by: $creator_name");
    // }

    public function toBrevo(object $notifiable): array
    {
        $creator = $this->project?->creator;
        $creator_name = $creator?->name ?: trim("{$creator?->first_name} {$creator?->last_name}");

        return [
            'email' => $notifiable->email,
            'name'  => $notifiable->name ?? "{$notifiable->first_name} {$notifiable->last_name}",
            'subject' => 'You were added to a new project',
            'html' => view('emails.added-to-project', [
                'user' => $notifiable,
                'type' => $this->type,
                'project' => $this->project,
                'creator_name' => $creator_name,
                'url' => url("/projects/{$this->project->ulid}"),
            ])->render()
        ];
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $creator = $this->project?->creator;
        $creator_name = ($creator?->name != '') ? $creator?->name : $creator?->first_name . ' ' . $creator?->last_name;
        return [
            'message' => "You've been added to the project: {$this->project->name}",
            'project_ulid' => $this->project->ulid,
            'added_by' => $creator_name,
            'url' => "/projects/show/{$this->project->ulid}",
        ];
    }
}
