<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLeaveNotification extends Notification
{
    use Queueable;
    public $get_leave;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($get_leave)
    {
        $this->get_leave = $get_leave;
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
            'user_id' => $this->get_leave->added_by,
            'name' => $this->get_leave->empaddedBy->name,
            'msg' => "New Leave Added",
        ];
    }
}
