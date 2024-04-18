<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Employee_attendance;
use App\Models\Employee_leave;
use App\Models\Employee_official_detail;
use App\Models\Employee_salary_detail;
use App\Models\Holiday;
use App\Models\Leavetype;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Nette\Utils\DateTime;
// use Mail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplyLeave;
use App\Mail\ApproveLeave;
use App\Mail\DisapproveLeave;
use App\Mail\CancelLeave;

use App\Models\Team;
use App\Models\Employee_team;
use App\Models\Employee_Personal_Detail;

use App\Exports\LeaveExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Notifications\NewLeaveNotification;

class Employee_LeaveController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $leaves = Leavetype::all();
    //     if (Auth::user()->id != 1) {
    //         $emp = User::where('id', Auth::user()->id)
    //             ->where('is_active', 'Y')->first();
    //         $year = date("Y");
    //         $next_year = date("Y") + 1;
    //         //  echo $year."   ".$next_year;
    //         //exit();
    //         $casual_leaves = Employee_salary_detail::where('emp_code', $emp->emp_code)->first();
    //         $total_casual_leaves_available = isset($casual_leaves->casual_leave_available) ? $casual_leaves->casual_leave_available : 0;
    //         $joining_date_query = Employee_official_detail::where('emp_code', $emp->emp_code)->first();
    //         $month = date('m');
    //         $leave_avail = 0;
    //         if ($month == "04") {
    //             $leave_avail = 1;
    //         }
    //         if ($month == "05") {
    //             $leave_avail = 2;
    //         }
    //         if ($month == "06") {
    //             $leave_avail = 3;
    //         }
    //         if ($month == "07") {
    //             $leave_avail = 4;
    //         }
    //         if ($month == "08") {
    //             $leave_avail = 5;
    //         }
    //         if ($month == "09") {
    //             $leave_avail = 6;
    //         }
    //         if ($month == "10") {
    //             $leave_avail = 7;
    //         }
    //         if ($month == "11") {
    //             $leave_avail = 8;
    //         }
    //         if ($month == "12") {
    //             $leave_avail = 9;
    //         }
    //         if ($month == "01") {
    //             $leave_avail = 10;
    //         }
    //         if ($month == "02") {
    //             $leave_avail = 11;
    //         }
    //         if ($month == "03") {
    //             $leave_avail = 12;
    //         }

    //         $joining_date = strtotime($joining_date_query->joining_date);
    //         $jmonth = date("F", $joining_date);

    //         $jyear = date("Y", $joining_date);
    //         $jday = date('d', $joining_date);


    //         if ($jmonth == "January" && $jyear == $year + 1) {
    //             if ($jday < 15) {
    //                 $total_casual_leaves_available = 3;
    //             } else {
    //                 $total_casual_leaves_available = 2.5;
    //             }
    //         } else if ($jmonth == "February" && $jyear == $year + 1) {
    //             if ($jday < 15) {
    //                 $total_casual_leaves_available = 2;
    //             } else {
    //                 $total_casual_leaves_available = 1.5;
    //             }
    //         } else if ($jmonth == "March" && $jyear == $year + 1) {
    //             if ($jday < 15) {
    //                 $total_casual_leaves_available = 1;
    //             } else {
    //                 $total_casual_leaves_available = 0.5;
    //             }
    //         } else if ($jmonth == "April" && $jyear == $year) {
    //             if ($jday < 15) {
    //                 $total_casual_leaves_available = 12;
    //             } else {
    //                 $total_casual_leaves_available = 11.5;
    //             }
    //         } else if ($jmonth == "May" && $jyear == $year) {
    //             if ($jday < 15) {
    //                 $total_casual_leaves_available = 11;
    //             } else {
    //                 $total_casual_leaves_available = 10.5;
    //             }
    //         } else if ($jmonth == "June" && $jyear == $year) {
    //             if ($jday < 15) {
    //                 $total_casual_leaves_available = 10;
    //             } else {
    //                 $total_casual_leaves_available = 9.5;
    //             }
    //         } else if ($jmonth == "July" && $jyear == $year) {
    //             if ($jday < 15) {
    //                 $total_casual_leaves_available = 9;
    //             } else {
    //                 $total_casual_leaves_available = 8.5;
    //             }
    //         } else if ($jmonth == "August" && $jyear == $year) {
    //             if ($jday < 15) {
    //                 $total_casual_leaves_available = 8;
    //             } else {
    //                 $total_casual_leaves_available = 7.5;
    //             }
    //         } else if ($jmonth == "September" && $jyear == $year) {
    //             if ($jday < 15) {
    //                 $total_casual_leaves_available = 7;
    //             } else {
    //                 $total_casual_leaves_available = 6.5;
    //             }
    //         } else if ($jmonth == "October" && $jyear == $year) {
    //             if ($jday < 15) {
    //                 $total_casual_leaves_available = 6;
    //             } else {
    //                 $total_casual_leaves_available = 5.5;
    //             }
    //         } else if ($jmonth == "November" && $jyear == $year) {
    //             if ($jday < 15) {
    //                 $total_casual_leaves_available = 5;
    //             } else {
    //                 $total_casual_leaves_available = 4.5;
    //             }
    //         } else if ($jmonth == "December" && $jyear == $year) {
    //             if ($jday < 15) {
    //                 $total_casual_leaves_available = 4;
    //             } else {
    //                 $total_casual_leaves_available = 3.5;
    //             }
    //         }

    //         $start_date = $year . "-04-01";
    //         $end_date = $next_year . "-03-31";
    //         $casual_leaves_taken = DB::table('employee_leaves')
    //             ->where('leavetype_id', 1)
    //             ->orWhere('leavetype_id', 6)
    //             ->where('emp_code', $emp->emp_code)
    //             ->whereBetween('approved_from', [$start_date, $end_date])->get();
    //         // echo $casual_leaves_taken;
    //         $cl = 0;
    //         foreach ($casual_leaves_taken as $key => $value) {
    //             $approved_days = $value->approved_days;
    //             $cl = $cl + $approved_days;
    //         }
    //         $casual_leaves_available = 0;
    //         if ($jyear != $year) {
    //             $casual_leaves_available = $leave_avail - $cl;
    //         } else {
    //             $casual_leaves_available = $total_casual_leaves_available - $cl;
    //         }
    //         //$casual_leaves_available = $leave_avail - $cl;
    //         $current_month = date('m');
    //         $month_start_date = $year . "-" . $current_month . "-01";
    //         $month_end_date = $year . "-" . $current_month . "-31";

    //         $sick_leave_query = DB::table('employee_leaves')
    //             ->where('leavetype_id', 2)
    //             ->orWhere('leavetype_id', 7)
    //             ->where('emp_code', $emp->emp_code)
    //             ->whereBetween('approved_from', [$month_start_date, $month_end_date])->get();
    //         $sick_leave_pending = 1;
    //         foreach ($sick_leave_query as $sick) {
    //             $approved_days = $sick->approved_days;
    //             $sick_leave_pending = 1 - $approved_days;
    //         }
    //         $emp_leaves = DB::table('employee_leaves')
    //             ->join('leavetypes', 'leavetypes.id', 'leavetype_id')
    //             // ->join('users','users.emp_code',)
    //             ->select('employee_leaves.*', 'leavetypes.leavetype')
    //             ->get();
    //         return view('employeeprofile.create_emp_leave', compact('leaves', 'emp_leaves', 'casual_leaves_available', 'sick_leave_pending'));
    //     } else {

    //         return view('employeeprofile.create_emp_leave', compact('leaves'));
    //     }
    // }
    //new
    public function index()
    {
        $leaves = Leavetype::all();
        // permission employee can mark leave
        $user_emp = Employee_Personal_Detail::join('users', 'users.emp_code', 'employee_personal_details.emp_code')
            ->select('employee_personal_details.*', 'users.name')
            ->where('employee_personal_details.is_active', 'Y')
            ->get();
        if (Auth::user()->id == 1 || Auth::user()->can('Mark Leave')) {

            return view('employeeprofile.create_emp_leave', compact('leaves', 'user_emp'));
        }
        // employee add leave
        elseif (Auth::user()->id != 1 && Auth::user()->can('Add Leave')) {
            // print_r('hello');exit();
            $emp = User::where('id', Auth::user()->id)
                ->where('is_active', 'Y')->first();
            $year = date("Y");
            $next_year = date("Y") + 1;
            //  echo $year."   ".$next_year;
            //exit();
            $casual_leaves = Employee_salary_detail::where('emp_code', $emp->emp_code)->first();
            $total_casual_leaves_available = isset($casual_leaves->casual_leave_available) ? $casual_leaves->casual_leave_available : 0;
            $joining_date_query = Employee_official_detail::where('emp_code', $emp->emp_code)->first();
            $month = date('m');
            $leave_avail = 0;
            if ($month == "04") {
                $leave_avail = 1;
            }
            if ($month == "05") {
                $leave_avail = 2;
            }
            if ($month == "06") {
                $leave_avail = 3;
            }
            if ($month == "07") {
                $leave_avail = 4;
            }
            if ($month == "08") {
                $leave_avail = 5;
            }
            if ($month == "09") {
                $leave_avail = 6;
            }
            if ($month == "10") {
                $leave_avail = 7;
            }
            if ($month == "11") {
                $leave_avail = 8;
            }
            if ($month == "12") {
                $leave_avail = 9;
            }
            if ($month == "01") {
                $leave_avail = 10;
            }
            if ($month == "02") {
                $leave_avail = 11;
            }
            if ($month == "03") {
                $leave_avail = 12;
            }

            $joining_date = strtotime($joining_date_query->joining_date);
            $jmonth = date("F", $joining_date);

            $jyear = date("Y", $joining_date);
            $jday = date('d', $joining_date);


            if ($jmonth == "January" && $jyear == $year + 1) {
                if ($jday < 15) {
                    $total_casual_leaves_available = 3;
                } else {
                    $total_casual_leaves_available = 2.5;
                }
            } else if ($jmonth == "February" && $jyear == $year + 1) {
                if ($jday < 15) {
                    $total_casual_leaves_available = 2;
                } else {
                    $total_casual_leaves_available = 1.5;
                }
            } else if ($jmonth == "March" && $jyear == $year + 1) {
                if ($jday < 15) {
                    $total_casual_leaves_available = 1;
                } else {
                    $total_casual_leaves_available = 0.5;
                }
            } else if ($jmonth == "April" && $jyear == $year) {
                if ($jday < 15) {
                    $total_casual_leaves_available = 12;
                } else {
                    $total_casual_leaves_available = 11.5;
                }
            } else if ($jmonth == "May" && $jyear == $year) {
                if ($jday < 15) {
                    $total_casual_leaves_available = 11;
                } else {
                    $total_casual_leaves_available = 10.5;
                }
            } else if ($jmonth == "June" && $jyear == $year) {
                if ($jday < 15) {
                    $total_casual_leaves_available = 10;
                } else {
                    $total_casual_leaves_available = 9.5;
                }
            } else if ($jmonth == "July" && $jyear == $year) {
                if ($jday < 15) {
                    $total_casual_leaves_available = 9;
                } else {
                    $total_casual_leaves_available = 8.5;
                }
            } else if ($jmonth == "August" && $jyear == $year) {
                if ($jday < 15) {
                    $total_casual_leaves_available = 8;
                } else {
                    $total_casual_leaves_available = 7.5;
                }
            } else if ($jmonth == "September" && $jyear == $year) {
                if ($jday < 15) {
                    $total_casual_leaves_available = 7;
                } else {
                    $total_casual_leaves_available = 6.5;
                }
            } else if ($jmonth == "October" && $jyear == $year) {
                if ($jday < 15) {
                    $total_casual_leaves_available = 6;
                } else {
                    $total_casual_leaves_available = 5.5;
                }
            } else if ($jmonth == "November" && $jyear == $year) {
                if ($jday < 15) {
                    $total_casual_leaves_available = 5;
                } else {
                    $total_casual_leaves_available = 4.5;
                }
            } else if ($jmonth == "December" && $jyear == $year) {
                if ($jday < 15) {
                    $total_casual_leaves_available = 4;
                } else {
                    $total_casual_leaves_available = 3.5;
                }
            }

            $start_date = $year . "-04-01";
            $end_date = $next_year . "-03-31";
            $casual_leaves_taken = Employee_leave::where('leavetype_id', 1)
                ->orWhere('leavetype_id', 6)
                ->where('emp_code', $emp->emp_code)
                ->whereBetween('approved_from', [$start_date, $end_date])->get();
            // echo $casual_leaves_taken;
            $cl = 0;
            foreach ($casual_leaves_taken as $key => $value) {
                $approved_days = $value->approved_days;
                $cl = $cl + $approved_days;
            }
            $casual_leaves_available = 0;
            if ($jyear != $year) {
                $casual_leaves_available = $leave_avail - $cl;
            } else {
                $casual_leaves_available = $total_casual_leaves_available - $cl;
            }
            //$casual_leaves_available = $leave_avail - $cl;
            $current_month = date('m');
            $month_start_date = $year . "-" . $current_month . "-01";
            $month_end_date = $year . "-" . $current_month . "-31";

            $sick_leave_query = Employee_leave::where('leavetype_id', 2)
                ->orWhere('leavetype_id', 7)
                ->where('emp_code', $emp->emp_code)
                ->whereBetween('approved_from', [$month_start_date, $month_end_date])->get();
            $sick_leave_pending = 1;
            foreach ($sick_leave_query as $sick) {
                $approved_days = $sick->approved_days;
                $sick_leave_pending = 1 - $approved_days;
            }
            $emp_leaves = Employee_leave::join('leavetypes', 'leavetypes.id', 'leavetype_id')
                // ->join('users','users.emp_code',)
                ->select('employee_leaves.*', 'leavetypes.leavetype')
                ->get();
            return view('employeeprofile.create_emp_leave', compact('leaves', 'emp_leaves', 'casual_leaves_available', 'sick_leave_pending', 'user_emp'));
        } else {
            return view('employeeprofile.create_emp_leave', compact('leaves', 'user_emp'));
        }
    }

    public function getLeaves(Request $request)
    {

        if ($request->input('export')) {
            // Export the data
            return $this->exportleaves($request);
        }

        $employee_id = null;
        $from_date = null;
        $to_date = null;

        if ($request->ajax()) {
            $employee_id = $request->employee_id;
            $from_date = $request->from_date;
            $to_date = $request->to_date;


            $leave = Employee_leave::join('leavetypes', 'leavetypes.id', 'leavetype_id')
                ->join('employee_personal_details', 'employee_personal_details.emp_code', 'employee_leaves.emp_code')
                // ->join('employee_salary_details', 'employee_salary_details.emp_code', 'employee_leaves.emp_code') //to comment
                ->select(
                    'employee_leaves.*',
                    'leavetypes.leavetype',
                    'employee_personal_details.subtitle',
                    'employee_personal_details.firstname',
                    'employee_personal_details.middlename',
                    'employee_personal_details.lastname'
                    // 'employee_salary_details.casual_leave_available'
                );

            $user = User::find(Auth::user()->id);
            // print_r(Auth::user()->emp_code);exit();
            if (Auth::user()->id != 1  && $user->hasPermissionTo('View Own Leave')) {
                $leave = $leave->where('employee_leaves.emp_code', Auth::user()->emp_code);
            }

            if ($employee_id != null) {
                if ($employee_id != "all") {
                    $leave = $leave->where('employee_leaves.emp_code', $employee_id);
                }

                $leave = $leave->where('employee_leaves.required_from', '>=', $from_date)
                    ->where('employee_leaves.required_from', '<=', $to_date);
            }

            $leave = $leave->orderBy('employee_leaves.id', 'desc')
                ->get();
            // print_r($leave);exit();

            $each_leave = new \Illuminate\Support\Collection();
            $leave_duration = null;
            foreach ($leave as $key => $value) {
                // print_r($value);exit();
                if ($value->requested_days == 1) {
                    $leave_duration = "1";
                    $required = date('d-m-Y', strtotime($value->required_from));
                } else if ($value->requested_days > 1) {
                    $datetime1 = new DateTime($value->required_from);
                    $datetime2 = new DateTime($value->required_to);
                    $interval = $datetime1->diff($datetime2);
                    $leave_duration = $interval->format('%a');
                    $required = date('d-m-Y', strtotime($value->required_from)) . " To" . date('d-m-Y', strtotime($value->required_to));
                } else if ($value->requested_hours != NULL) {
                    $leave_duration = $value->requested_hours . "Hours";
                    $required = date('d-m-Y', strtotime($value->required_from));
                }
                // old
                // else if ($value->total_hours_leave != NULL) {
                //     $leave_duration = $value->total_hours_leave . "Hours";
                //     $required = date('d-m-Y', strtotime($value->required_from));
                // }
                if ($value->leave_status == 'A') {
                    $days = $value->requested_days;
                    if ($value->requested_days == 1) {
                        $required = $required;
                    } else if ($value->requested_days > 1) {
                        $required = $required;
                    }
                }
                if ($value->leave_status == 'P') {
                    $days = $value->requested_days;
                }
                if ($value->leave_status == 'R') {
                    $days = $value->requested_days;
                }
                if ($value->leave_status == 'C') {
                    $days = $value->requested_days;
                }

                if ($value->worked_on != null) {

                    $attendance = Employee_attendance::where('attendance_date', $value->worked_on)
                        ->where('emp_code', $value->emp_code)->first();
                    $reason = "Worked on: <a href='' class='attendance' data-worked_on='$value->worked_on'
                        data-login='$attendance->login_time' data-logout='$attendance->logout_time'
                        data-owt='$attendance->total_working_hours'  data-obt='$attendance->total_break_hours'
                        data-mymbi='$attendance->morning_break_in'  data-mymbo='$attendance->morning_break_out'
                        data-mylbi='$attendance->lunch_break_in'  data-mylbo='$attendance->lunch_break_out'
                        data-myebi='$attendance->evening_break_in'  data-myebo='$attendance->evening_break_out' data-toggle='modal'
                        data-target='#attendance' placeholder='View Attendance'>" . date('d-m-Y', strtotime($value->worked_on)) . "</a>";
                    $reason = $reason . "<br>Reason: " . $value->reason;
                } else {
                    $reason = $value->reason;
                }
                $action = '<a href="" class="details btn-sm btn-warning"  data-myid="' . $value->id . '"
                        data-leavetype="' . $value->leavetype . '" data-myfrom="' . $value->required_from . '"  data-myto="' . $value->required_to . '"
                        data-myreason="' . $value->reason . '" data-myrequestdays="' . $value->requested_days . '" data-myrequested_hours-="' . $value->requested_hours . '"
                        data-myapprovefrom="' . $value->approved_from . '" data-myapproveto="' . $value->approved_to . '"
                        data-myapprovedays="' . $value->approved_days . '"
                        data-myworked_on="' . $value->worked_on . '"
                        data-mytimefrom="' . $value->time_from . '" data-mytimeto="' . $value->time_to . '"
                        data-myapproved_hours="' . $value->approved_hours . '" data-mycomments="' . $value->comments . '"
                        data-myleave_status="' . $value->leave_status . '"
                        data-toggle="modal" data-target="#details" placeholder="View Details">Details</a>';
                $status = null;
                $user = User::find(Auth::user()->id);
                if (Auth::user()->id == 1  || $user->hasPermissionTo('Update Leave')) {

                    if ($value->leave_status == "P") {
                        $status = '<a  style="color: darkblue">Pending</a>';
                        $action = $action . '<a href="" class="approve btn-sm btn-success" data-myid="' . $value->id . '"
                                           data-leavetype="' . $value->leavetype . '" data-myfrom="' . $value->required_from . '"
                                           data-myto="' . $value->required_to . '" data-myreason="' . $value->reason . '" data-toggle="modal"
                                           data-target="#approve" placeholder="Approve Leave">Approve</a>
                                          <a href="" class="disapprove btn-sm btn-danger" data-myid="' . $value->id . '"
                                           data-leavetype="' . $value->leavetype . '" data-myfrom="' . $value->required_from . '"
                                           data-myto="' . $value->required_to . '" data-myreason="' . $value->reason . '" data-toggle="modal"
                                           data-target="#disapprove" placeholder="Disapprove">Disapprove</a>
                                           <a href="" class="cancell btn-sm btn-info" data-myid="' . $value->id . '"
                                           data-leavetype="' . $value->leavetype . '" data-myfrom="' . $value->required_from . '"
                                           data-myto="' . $value->required_to . '" data-myreason="' . $value->reason . '" data-toggle="modal"
                                           data-target="#cancell" placeholder="Cancell">Cancell</a>';
                    } else if ($value->leave_status == "A") {
                        $status = '<a style="color: darkgreen">Approved</>';
                        $action = $action . ' <a href="" class="disapprove btn-sm btn-danger" data-myid="' . $value->id . '"
                                           data-leavetype="' . $value->leavetype . '" data-myfrom="' . $value->required_from . '"
                                           data-myto="' . $value->required_to . '" data-myreason="' . $value->reason . '" data-toggle="modal"
                                           data-target="#disapprove" placeholder="Disapprove">Disapprove</a>';
                    } else if ($value->leave_status == "R") {
                        $status = '<a style="color: red">Disapproved</a>';
                        $action = $action . '<a href="" class="approve btn-sm btn-success" data-myid="' . $value->id . '"
                                           data-leavetype="' . $value->leavetype . '" data-myfrom="' . $value->required_from . '"
                                           data-myto="' . $value->required_to . '" data-myreason="' . $value->reason . '" data-toggle="modal"
                                           data-target="#approve" placeholder="Approve Leave">Approve</a>
                                          ';
                    } else if ($value->leave_status == "C") {
                        $status = '<a style="color: orange">Cancelled</a>';
                        $action = '';
                    }
                }
                $each_leave->push([
                    'emp_code' => $value->emp_code . " " . $value->subtitle . " " . $value->firstname . " " . $value->middlename . " " . $value->lastname,
                    'leave_type' => $value->leavetype,
                    'days' => $days,
                    'required' => $required,
                    'reason' => $reason,
                    // 'comments' => $value->comments,
                    'status' => $status,
                    'action' => $action,
                ]);
            }
            return \Yajra\DataTables\DataTables::of($each_leave)->addIndexColumn()->rawColumns(['reason', 'action', 'status', 'days', 'required'])->make(true);
        }
        //   return \Yajra\DataTables\DataTables::of($each_leave)->addIndexColumn()->rawColumns(['action'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    // for compensation leave type check
    function comp_work(Request $request)
    {
        // print_r($weekDay);exit();
        $worked_on = $request->id;
        $weekDay = date('w', strtotime($worked_on));
        $emp_code = Auth::user()->emp_code;
        $attendance_check = null;
        if ($weekDay == 0) {
            $attendance_check = Employee_attendance::where('attendance_date', $worked_on)
                ->where('emp_code', $emp_code)->get();
            if (count($attendance_check) > 0) {
                return 1;
            }
            return null;
        } else if ($weekDay != 0) {
            $holiday_check = Holiday::where('holiday_date', $worked_on)->get();
            if (count($holiday_check) > 0) {
                $attendance_check = Employee_attendance::where('attendance_date', $worked_on)
                    ->where('emp_code', $emp_code)->get();
                // print_r(count($attendance_check));exit();
                if (count($attendance_check) > 0) {
                    return 1;
                }
                return null;
            }
            return null;
        } else {
            return null;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_emp_leave(Request $request)
    {

        // print_r($request->all());exit(); 
        if ($request->employee_id) {  //to mark leave
            $get_emp = Employee_Personal_Detail::where('id', $request->employee_id)->first();
            $emp = Employee_Personal_Detail::where('emp_code', $get_emp->emp_code)->first();
        } else {  //to apply leave
            $emp = User::where('id', Auth::user()->id)->first();
        }
        $emp_code = $emp->emp_code;
        $emp_shift = $emp->shift_id;
        $emp_name = $emp->name;
        // print_r($emp);exit();


        $required_from = null;
        $required_to = null;
        $requested_days = 0;
        $requested_hours = 0;
        $leave_type = $request->leavetype_id;
        $cla = $request->casual_leave;
        $sla = $request->sick_leave;
        $time_from = null;
        $time_to = null;
        $request_hours = 0;
        $worked_on = null;
        $from = null;
        $leave_type_name = Leavetype::where('id', $request->leavetype_id)->first();
        if ($leave_type == 1) {
            $leave_duration = $request->leave_durationc;
            if ($leave_duration == "single") {
                $requested_days = 1;
                $required_from = $request->required_on_c;
                $required_to = $request->required_on_c;
                $from = $request->required_on_c;
            } else if ($leave_duration == "multiple") {
                $required_from = new \DateTime($request->required_from_c);
                $required_to = new \DateTime($request->required_to_c);
                $from = $request->required_from_c;
                $days = $required_to->diff($required_from);
                $requested_days = $days->format('%a');
            }
        } else if ($leave_type == 2) {

            if ($sla != 0) {
                $requested_days = 1;
                $required_from = $request->sick_required_on;
                $required_to = $request->sick_required_on;
                $from = $request->sick_required_on;
            } else {
                return Redirect()->back()->with('error', "No sick leave Available");
            }
        } else if ($leave_type == 3) {

            $requested_days = 0;
            $required_from = $request->required_day_p;
            $required_to = $request->required_day_p;
            $time_from = $request->total_hours_leave_from_p;
            $time_to = $request->total_hours_leave_to_p;
            $request_hours = $time_to - $time_from;
            $from = $required_from = $request->required_day_p;
        } else if ($leave_type == 4) {
            // print_r($request->all());exit();
            if ($request->leave_duration == 'single') {
                $requested_days = 1;
                $required_from = $request->required_on;
                $required_to = $request->required_on;
                $from = $request->required_on;
            } else if ($request->leave_duration == 'multiple') {

                $required_from = new \DateTime($request->required_from);
                $required_to = new \DateTime($request->required_to);
                $from = $request->required_from;
                $days = $required_to->diff($required_from);
                $requested_days = $days->format('%a');
            } else if ($request->leave_duration == 'hours') {
                $requested_days = 1;
                $required_from = $request->required_day;
                $required_to = $request->required_day;
                $from = $request->required_day;
                $time_from = $request->total_hours_leave_from;
                $time_to = $request->total_hours_leave_to;
            }
        } else if ($leave_type == 5) {

            $requested_days = 1;
            $worked_on = $request->worked_on;
            $required_from = $request->required_comp;
            $required_to = $request->required_comp;
            $from = $request->required_comp;
        } else if ($leave_type == 6) {

            $request_hours = 4;
            if ($request->total_hours_leave_from_cas == "morning") {
                $time_from = 9;
                $time_to = 13;
            } else if ($request->total_hours_leave_from_cas == "afternoon") {
                $time_from = 14;
                $time_to = 18;
            }
            $required_from = $request->required_day_cas;
            $required_to = $request->required_day_cas;
            $from = $request->required_day_cas;
        } else if ($leave_type == 7) {
            // print_r($request->all());exit();
            $request_hours = 4;
            if ($request->total_hours_leave_from_sick == "morning") {
                $time_from = 9;
                $time_to = 13;
            } else if ($request->total_hours_leave_from_sick == "afternoon") {
                $time_from = 14;
                $time_to = 18;
            }
            $required_from = $request->required_day_sick;
            $required_to = $request->required_day_sick;
            $from = $request->required_day_sick;
        } else {
            if ($request->leave_duration == 'single') {
                $requested_days = 1;
                $required_from = $request->required_on;
                $required_to = $request->required_on;
                $from = $request->required_on;
            } else if ($request->leave_duration == 'multiple') {

                $required_from = new \DateTime($request->required_from);
                $required_to = new \DateTime($request->required_to);
                $from = $request->required_from;
                $days = $required_to->diff($required_from);
                $requested_days = $days->format('%a');
            } else if ($request->leave_duration == 'hours') {
                $requested_days = 1;
                $required_from = $request->required_day;
                $from = $request->required_day;
                $required_to = $request->required_day;
                $time_from = $request->total_hours_leave_from;
                $time_to = $request->total_hours_leave_to;
            }
        }


        $data_id = Employee_leave::insertGetId([
            'emp_code' => $emp_code,
            'leavetype_id' => $request->leavetype_id,
            'year' => DateTime::createFromFormat('Y-m-d', $from)->format('Y'),
            'month' => DateTime::createFromFormat('Y-m-d', $from)->format('m'),
            'requested_days' => $requested_days,
            'requested_hours' => $request_hours,
            'worked_on' => $worked_on,
            'required_from' => $required_from,
            'required_to' => $required_to,
            'reason' => $request->reason,
            'time_from' => $time_from,
            'time_to' => $time_to,
            'leave_status' => "P",
            'added_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'shift_id' => $emp_shift,
        ]);
        // print_r($data);exit();
        $data2 = [
            'emp_name' => $emp_name,
            'leave_type_name' => $leave_type_name->leavetype,
        ];
        $data = Employee_leave::find($data_id);
        // exit();
        // mail to hr team and tl
        // check for the not tl and member 
        // $chk_tl = Team::where('team_leader',Auth::user()->id)->get();
        $chk_mem = Employee_team::where('emp_code', $emp_code)
            ->where('member_type', 'M')
            ->where('is_active', 'Y')
            ->first();
        // print_r($chk_mem);exit();
        $get_tl_email_id = Null;
        if ($chk_mem) {
            $get_team_id = $chk_mem->team_id;
            $chk_team = Team::where('id', $get_team_id)
                ->first();

            $get_tl_email_id = Employee_Personal_Detail::where('id', $chk_team->team_leader)
                ->select('personal_emailID')
                ->where('is_active', 'Y')
                ->first();
        }
        // print_r($get_tl_email_id->personal_emailID);exit();
        if ($get_tl_email_id) {
            //also to get the hr mails
            $to = ['phpdeveloper2.docllp@gmail.com', $get_tl_email_id->personal_emailID];
        } else {
            $to = "phpdeveloper2.docllp@gmail.com";
        }
        Mail::to($to)->send(new ApplyLeave($data, $data2));

        // leave notification
        $users = $this->getUsersWithPermission('Leave Notification');
        if ($users) {
            $get_leave = Employee_leave::find($data_id);
            // print_r($get_leave);exit();
            // return $users;
            foreach ($users as $user) {
                $notification = new NewLeaveNotification($get_leave);
                $user->notify($notification);
            }
        }

        // print_r($emp);
        // exit();
        return Redirect()->back()->with('success', "Leave Applied successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update_emp_leave(Request $request)
    {
        $leave_status = null;
        if ($request->approve != null) {
            $leave_status = "A";
        } else if ($request->reject != null) {
            $leave_status = "R";
        } else if ($request->cancel != null) {
            $leave_status = "C";
        } else if ($request->a_reject != null) {
            $leave_status = "R";
        } else if ($request->r_approve != null) {
            $leave_status = "A";
        }
        $updateDetails = [
            'leave_status' => $leave_status,
            'comments' => $request->comments,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ];
        $data = Employee_leave::where('id', $request->leave_id)
            ->update($updateDetails);

        return Redirect()->back()->with('success', "Leave updated successfully");
    }

    function approve_emp_leave(Request $request)
    {
        try {
            // print_r($request->all());exit();
            DB::beginTransaction();
            $leave_id = $request->id;
            $approve_from = $request->approve_from;
            $approve_to = $request->approve_to;
            //$comments = $request->comments;
            $leave_status = 'A';
            $required_from = new \DateTime($approve_from);
            $required_to = new \DateTime($approve_to);
            $approved_days = 0;
            if ($approve_from == $approve_to) {
                $approved_days = 1;
            } else {
                $days = $required_to->diff($required_from);
                $approved_days = $days->format('%a');
            }

            $updateDetails = [
                'leave_status' => $leave_status,
                'approved_from' => $approve_from,
                'approved_to' => $approve_to,
                'approved_days' => $approved_days,
                'comments' => $request->comments,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
            $i = Employee_leave::where('id', $leave_id)->update($updateDetails);
            if ($i) {
                $email_id_query = Employee_leave::where('id', $leave_id)->first();
                $emp_code = $email_id_query->emp_code;
                $get_email = User::where('emp_code', $emp_code)->first();
                $email_to = $get_email->email;
                $data = ['name' => $get_email->name, 'approved_from' => $approve_from, 'approved_to' => $approve_to];
                $to = ['phpdeveloper2.docllp@gmail.com', $email_to];
                $data2 = Employee_leave::with('leavetype')->find($leave_id);
                DB::commit();
                Mail::to($to)->send(new ApproveLeave($data, $data2));
                // Mail::to($to)->send(new ApplyLeave($data, $data2));
                return Redirect()->back()->with('success', "Leave Approved successfully");
            } else {
                DB::rollBack();
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    function disapprove_emp_leave(Request $request)
    {
        $leave_id = $request->id;
        $leave_status = 'R';
        $updateDetails = [
            'approved_days' => 0,
            'leave_status' => $leave_status,
            'comments' => $request->comments,
            'approved_from' => null,
            'approved_to' => null,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ];
        $i = Employee_leave::where('id', $leave_id)->update($updateDetails);
        if ($i) {
            $email_id_query = Employee_leave::where('id', $leave_id)->first();
            $emp_code = $email_id_query->emp_code;
            $get_email = User::where('emp_code', $emp_code)->first();
            $email_to = $get_email->email;
            $data = ['name' => $get_email->name];
            $to = ['phpdeveloper2.docllp@gmail.com', $email_to];
            $data2 = Employee_leave::with('leavetype')->find($leave_id);
            DB::commit();
            Mail::to($to)->send(new DisapproveLeave($data,$data2));
            return Redirect()->back()->with('success', "Leave Disapproved successfully");
        } else {
            DB::rollBack();
        }
    }

    function cancel_emp_leave(Request $request)
    {
        $leave_id = $request->id;
        $leave_status = 'C';
        $updateDetails = [
            'approved_days' => 0,
            'leave_status' => $leave_status,
            'comments' => $request->comments,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ];
        $i = Employee_leave::where('id', $leave_id)->update($updateDetails);
        if ($i) {
            $email_id_query = Employee_leave::where('id', $leave_id)->first();
            $emp_code = $email_id_query->emp_code;
            $get_email = User::where('emp_code', $emp_code)->first();
            $email_to = $get_email->email;
            $data = ['name' => $get_email->name];
            $to = ['phpdeveloper2.docllp@gmail.com', $email_to];
            $data2 = Employee_leave::with('leavetype')->find($leave_id);
            DB::commit();
            Mail::to($to)->send(new CancelLeave($data, $data2));
            return Redirect()->back()->with('success', "Leave Cancelled successfully");
        } else {
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // for export
    public function exportleaves(Request $request)
    {
        // print_r($request->all());exit();
        if ($request->has('export')) {

            $leaves = Employee_leave::join('leavetypes', 'leavetypes.id', 'leavetype_id')
                // ->join('employee_personal_details', 'employee_personal_details.emp_code', 'employee_leaves.emp_code')
                ->join('users', 'users.emp_code', 'employee_leaves.emp_code')
                // ->join('employee_salary_details', 'employee_salary_details.emp_code', 'employee_leaves.emp_code') //to comment
                ->select(
                    'employee_leaves.*',
                    'leavetypes.leavetype',
                    'users.name',
                    // 'employee_personal_details.subtitle',
                    // 'employee_personal_details.firstname',
                    // 'employee_personal_details.middlename',
                    // 'employee_personal_details.lastname'
                    // 'employee_salary_details.casual_leave_available'
                );
            if ($request->has('employee_id') && $request->employee_id != '') {


                if ($request->input('employee_id') != "all") {
                    $leaves = $leaves->where('employee_leaves.emp_code', $request->input('employee_id'));
                }

                $leaves = $leaves->where('employee_leaves.required_from', '>=', $request->input('from_date'))
                    ->where('employee_leaves.required_from', '<=', $request->input('to_date'));
            }

            // $leaves = $leaves->orderBy('employee_leaves.id', 'desc')
            //     ->get();

            // print_r($leaves);exit();

            $filename = 'leaves.xlsx';
            $response = Excel::download(new LeaveExport($leaves->orderBy('created_at', 'DESC')->get()),  $filename);

            ob_end_clean();
            return $response;
        }
    }


    public function getUsersWithPermission($permissionName)
    {
        // Retrieve users with a specific permission
        $usersWithPermission = User::permission($permissionName)->get();

        return $usersWithPermission;
    }
}
