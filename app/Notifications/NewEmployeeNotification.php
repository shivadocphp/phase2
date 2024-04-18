<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEmployeeNotification extends Notification
{
    use Queueable;
    public $get_employee;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($get_employee)
    {
        $this->get_employee = $get_employee;
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
            'user_id' =>$this->get_employee->added_by,
            'name' =>$this->get_employee->empaddedBy->name,
            'msg' =>"New Employee Added",
        ];
        
    }
}
