<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LateClockinAttendence extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $login_time;
    public $emp_code;
    public $attendance_date;
    public $shift_id;
    public $emp_name;
    public $emp_exp_clockin;


    public function __construct($data, $data2)
    {
        $this->login_time = $data['login_time'] ?? null;
        $this->emp_code = $data['emp_code'] ?? null;
        $this->attendance_date = $data['attendance_date'] ?? null;
        $this->shift_id = $data['shift_id'] ?? null;

        $this->emp_name = $data2['emp_name'] ?? null;
        $this->emp_exp_clockin = $data2['emp_exp_clockin'] ?? null;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('phpdeveloper2.docllp@gmail.com', 'JSC-Admin')
            ->subject('Late Clockin Notification')
            ->view('mail.lateclockinattendance');
    }
}
