<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApproveLeave extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $name;
    public $approved_from;
    public $approved_to;

    public $emp_code;
    public $shift_id;
    public $leave_type_name;
    public $requested_days;
    public $approved_days;
    public $leave_status;
    public $comments;


    public function __construct($data,$data2)
    {
        $this->name = $data['name'] ?? null;
        $this->approved_from = $data['approved_from'] ?? null;
        $this->approved_to = $data['approved_to'] ?? null;

        $this->emp_code = $data2['emp_code'] ?? null;
        $this->shift_id = $data2['shift_id'] ?? null;
        $this->leave_type_name = $data2['leavetype'] ?? null;
        $this->requested_days = $data2['requested_days'] ?? null;
        $this->approved_days = $data2['approved_days'] ?? null;
        $this->leave_status = $data2['leave_status'] ?? null;
        $this->comments = $data2['comments'] ?? null;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('phpdeveloper2.docllp@gmail.com', 'JSC-Admin')
                    ->subject('Leave Approved Notification')
                    ->view('mail.approveleave');         
    }
}
