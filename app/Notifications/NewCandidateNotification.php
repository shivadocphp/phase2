<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCandidateNotification extends Notification
{
    use Queueable;
    public $get_candidate;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($get_candidate)
    {
        $this->get_candidate = $get_candidate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }
    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->get_candidate->added_by,
            'name' => $this->get_candidate->empaddedBy->name,
            'msg' => "New Candidate Added",
        ];
    }
}
