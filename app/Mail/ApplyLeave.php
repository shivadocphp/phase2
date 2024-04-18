<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplyLeave extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $emp_code;
    public $requested_days;
    public $required_from;
    public $reason;
    public $leave_status;
    public $shift_id;
    public $emp_name;
    public $leave_type_name;

    public function __construct($data,$data2)
    {
        $this->emp_code = $data['emp_code'] ?? null;
        $this->requested_days = $data['requested_days'] ?? null;
        $this->required_from = $data['required_from'] ?? null;
        $this->reason = $data['reason'] ?? null;
        $this->leave_status = $data['leave_status'] ?? null;
        $this->shift_id = $data['shift_id'] ?? null;

        $this->emp_name = $data2['emp_name'] ?? null;
        $this->leave_type_name = $data2['leave_type_name'] ?? null;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('phpdeveloper2.docllp@gmail.com', 'JSC-Admin')
                    ->subject('Apply Leave Notification')
                    ->view('mail.applyleave');         
    }
}
