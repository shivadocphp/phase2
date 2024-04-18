<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAllocationNotification extends Notification
{
    use Queueable;
    public $get_allocation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($get_allocation)
    {
        $this->get_allocation = $get_allocation;
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
            'user_id' => $this->get_allocation->added_by,
            'name' => $this->get_allocation->empaddedBy->name,
            'msg' => "New Allocation Added",
        ];
    }
}
