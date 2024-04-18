<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRequirementNotification extends Notification
{
    use Queueable;
    public $get_requirement;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($get_requirement)
    {
        $this->get_requirement = $get_requirement;
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
            'user_id' => $this->get_requirement->added_by,
            'name' => $this->get_requirement->empaddedBy->name,
            'msg' => "New Client Added",
        ];
    }
}
