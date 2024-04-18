<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewInvoiceNotification extends Notification
{
    use Queueable;
    public $get_invoice;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($get_invoice)
    {
        $this->get_invoice = $get_invoice;
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
            'user_id' => $this->get_invoice->added_by,
            'name' => $this->get_invoice->empaddedBy->name,
            'msg' => "New Invoice Added",
        ];
    }
}
