<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewClientNotification extends Notification
{
    use Queueable;
    public $get_client;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($get_client)
    {
        $this->get_client = $get_client;
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
            'user_id' => $this->get_client->added_by,
            'name' => $this->get_client->empaddedBy->name,
            'msg' => "New Client Added",
        ];
    }
}
