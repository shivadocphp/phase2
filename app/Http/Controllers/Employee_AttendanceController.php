<?php

namespace App\Http\Controllers;

use App\Models\Employee_attendance;
use App\Models\Employee_personal_detail;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use App\Exports\SampleExport; // Adjust this to your export class
// use Excel;
// use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\LateClockinAttendence;
use App\Mail\BeforeClockoutAttendence;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

use App\Exports\MonthlyAttendancesExport;
use App\Models\Week_off;
use App\Models\Holiday;
use App\Models\Employee_leave;
use App\Models\EmployeePayroll;

use App\Models\Employee_official_detail;
use App\Models\Setting;
use App\Models\Employee_team;
use App\Models\Team;

class Employee_AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendance = Employee_attendance::orderBy('attendance_date', 'DESC')->get();
        // $user_emp = User::all();
        $user_emp = Employee_personal_detail::join('users', 'users.emp_code', 'employee_personal_details.emp_code')
            ->select('employee_personal_details.*', 'users.name')
            ->where('employee_personal_details.is_active', 'Y')
            ->get();
        // print_r($user_emp);exit();
        return view('attendance.index', compact('attendance', 'user_emp'));
    }

    public function getAtendances(Request $request)
    {
        // print_r($request->all());exit();

        $employee_id = null;
        $from_date = null;
        $to_date = null;

        if ($request->ajax()) {
            $employee_id = $request->employee_id;
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $attendance = null;

            if (Auth::user()->id == 1 || Auth::user()->can('View All Attendance')) {
                $attendance = Employee_attendance::join('employee_personal_details', 'employee_personal_details.emp_code', 'employee_attendances.emp_code')
                    ->select('employee_attendances.*', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname');
            } else {
                $attendance = Employee_attendance::where('employee_attendances.emp_code', Auth::user()->emp_code)
                    ->join('employee_personal_details', 'employee_personal_details.emp_code', 'employee_attendances.emp_code')
                    ->select('employee_attendances.*', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname');
            }

            if ($employee_id != null) {
                if ($employee_id != "all") {
                    $attendance = $attendance->where('employee_personal_details.id', $employee_id);
                }

                $attendance = $attendance->where('employee_attendances.attendance_date', '>=', $from_date)
                    ->where('employee_attendances.attendance_date', '<=', $to_date);
            }

            // ->whereBetween('employee_attendances.date', [$fromDate, $toDate])
            // exit();
            $attendance = $attendance->orderby('employee_attendances.id', 'DESC')->get();

            $each_attendance = new Collection();
            $working_hours = "";
            foreach ($attendance as $key => $value) {
                $mb_start_datetime = new \DateTime($value->morning_break_in);
                $mb_end_datetime = new \DateTime($value->morning_break_out);

                $mb_difference = $mb_end_datetime->diff($mb_start_datetime);
                $mb_break_hours = $mb_difference->format('%H:%I:%S');
                $mdiffInMinutes = $mb_difference->i;
                if ($mdiffInMinutes > 15) {
                    $mb_break_hours = "<b style='color: red'>$mb_break_hours</b>";
                } else {
                    $mb_break_hours = "<b style='color: green'>$mb_break_hours</b>";
                }

                $lb_start_datetime = new \DateTime($value->lunch_break_in);
                $lb_end_datetime = new \DateTime($value->lunch_break_out);

                $lb_difference = $lb_end_datetime->diff($lb_start_datetime);
                $lb_break_hours = $lb_difference->format('%H:%I:%S');
                $ldiffInMinutes = $lb_difference->i;
                if ($ldiffInMinutes > 30) {
                    $lb_break_hours = "<b style='color: red'>$lb_break_hours</b>";
                } else {
                    $lb_break_hours = "<b style='color: green'>$lb_break_hours</b>";
                }
                $eb_start_datetime = new \DateTime($value->evening_break_in);
                $eb_end_datetime = new \DateTime($value->evening_break_out);

                $eb_difference = $eb_end_datetime->diff($eb_start_datetime);
                $eb_break_hours = $eb_difference->format('%H:%I:%S');
                $ediffInMinutes = $eb_difference->i;
                if ($ediffInMinutes > 15) {
                    $eb_break_hours = "<b style='color: red'>$eb_break_hours</b>";
                } else {
                    $eb_break_hours = "<b style='color: green'>$eb_break_hours</b>";
                }

                // $w_start_datetime = new \DateTime($value->total_break_hours);
                // $w_end_datetime = new \DateTime($value->total_working_hours);
                // // print_r($w_end_datetime);exit();

                // $difference = $w_end_datetime->diff($w_start_datetime);
                // $total_work_hours = $difference->format('%H:%I:%S');
                // $t_diffInHours = $difference->h;
                // if ($t_diffInHours < 8) {
                //     $total_work_hours = "<b style='color: red'>$total_work_hours</b>";
                // } else {
                //     $total_work_hours = "<b style='color: red'>$total_work_hours</b>";
                // }

                $date = null;
                $user = User::find(Auth::user()->id);
                if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Attendance')) {
                    $date = '<a  href=""
                    data-login="' . $value->login_time . '" data-logout="' . $value->logout_time . '"
                    data-owt="' . $value->total_working_hours . '" data-obt="' . $value->total_break_hours . '"
                    data-mymbi="' . $value->morning_break_in . '" data-mymbo="' . $value->morning_break_out . '" 
                    data-mylbi="' . $value->lunch_break_in . '" data-mylbo="' . $value->lunch_break_out . '" 
                    data-myebi="' . $value->evening_break_in . '" data-myid="' . $value->id . '" 
                    data-myebo="' . $value->evening_break_out . '" data-toggle="modal"
                    data-target="#edit_break" placeholder="View Break Timings" >' . date('d-m-Y', strtotime($value->attendance_date)) . '</a>';
                } else {
                    $date = '<a  href=""  data-mymbi="' . $value->morning_break_in . '"
                    data-mymbo="' . $value->morning_break_out . '" data-mylbi="' . $value->lunch_break_in . '"
                    data-mylbo="' . $value->lunch_break_out . '" data-myebi="' . $value->evening_break_in . '"
                    data-myid="' . $value->id . '" data-myebo="' . $value->evening_break_out . '" 
                    data-toggle="modal" data-target="#view_break" placeholder="View Break Timings" >' . date('d-m-Y', strtotime($value->attendance_date)) . '</a>';
                }
                $each_attendance->push([
                    'emp_code' => $value->emp_code . "-" . $value->firstname . " " . $value->middlename . " " . $value->lastname,
                    'attendance_date' => $date,
                    'shift_id' => $value->shift_id,
                    'login_time' => $value->login_time,
                    'morning_break_in' => $mb_break_hours,
                    'lunch_break_in' => $lb_break_hours,
                    'evening_break_in' => $eb_break_hours,
                    'logout_time' => $value->logout_time,
                    // 'working_hours' => $total_work_hours,
                    'total_working_hours' => $value->total_working_hours,
                    'total_break_hours' => $value->total_break_hours,
                ]);
            }
            return DataTables::of($each_attendance)->addIndexColumn()->rawColumns(['emp_code', 'total_break_hours', 'working_hours', 'attendance_date', 'morning_break_in', 'lunch_break_in', 'evening_break_in'])->make(true);
        }
    }

    public function attendance_details($id)
    {
        echo "<html><body> <table><tr style='background-color: #ff751a;' class='modal-header' ><th> Attendance Details On $id</th></tr></table>";
        $date = date('Y-m-d', strtotime($id));
        $attendance_details = Employee_attendance::where('emp_code', Auth::user()->id)
            ->where('attendance_date', $date)->first();
        echo "<table class='table table-striped'><tr align='center'><th>Break</th><th>From</th><th>To</th></tr>";
        echo "<tr align='center'><td>First </td><td>$attendance_details->morning_break_in </td><td> $attendance_details->morning_break_out</td></tr>";
        echo "<tr align='center'><td>Lunch </td><td>$attendance_details->lunch_break_in </td><td> $attendance_details->lunch_break_out</td></tr>";
        echo "<tr align='center'><td>Second</td><td>$attendance_details->evening_break_in </td><td> $attendance_details->evening_break_out</td></tr></table>";

        echo "<table><tr><td align='center'><button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button></td></tr></table>
            </body></html>";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getMonthlyReport(Request $request)
    {
        $search = array();
        if ($request->has('month')) {
            $search['month'] = trim($request->month);
        } else {
            $search['month'] = Carbon::now()->format('m');;
        }
        if ($request->has('year')) {
            $search['year'] = trim($request->year);
        } else {
            $search['year'] = Carbon::now()->format('Y');;
        }
        //dd($search);
        //dd($request);
        // print_r($search);exit();
        $attendance = Employee_attendance::orderBy('attendance_date', 'DESC')->get();
        return view('attendance.monthlyReport', compact('attendance', 'search'));
    }

    public function getMonthlyAttendances(Request $request)
    {

        if ($request->ajax()) {
            $attendance = null;
            $search = array();

            if ($request->has('month')) {
                $month = trim($request->month);
                $search['month'] = $month;
            } else {
                $month = Carbon::now()->format('m');
                $search['month'] = $month;
            }
            if ($request->has('year')) {
                $year = trim($request->year);
                $search['year'] = $year;
            } else {
                $year = Carbon::now()->format('Y');
                $search['year'] = $year;
            }

            $month_start = Carbon::createFromDate($year, $month)->startOfMonth()->format('Y-m-d');
            $month_end = Carbon::createFromDate($year, $month)->endOfMonth();
            $period = CarbonPeriod::create($month_start, $month_end);

            $att_array = array();
            $arr_cols = array();
            $arr_cols_header = array();
            $arr_cols[] = 'emp_code';
            $arr_cols_header[] = 'Employee';


            $arr_cols_header2[] = 'Total';
            $arr_cols_total[] = 'total';
            foreach ($period as $dt) {
                $dt_key     = $dt->format("Y-m-d");
                $dt_f     = $dt->format("d");
                $arr_cols[] = $dt_key;
                $arr_cols_header[] = $dt_f;
            }
            $arr_cols_header = array_merge($arr_cols_header, $arr_cols_header2);
            $arr_cols = array_merge($arr_cols, $arr_cols_total);

            if (Auth::user()->id == 1) {
                $all_employees = Employee_personal_detail::where('emp_code', '<>', NULL)->get();
                $attendance = Employee_attendance::whereBetween('attendance_date', [$month_start, $month_end])
                    ->where('employee_attendances.emp_code', '<>', Auth::user()->emp_code)
                    ->orderby('employee_attendances.id', 'ASC')->get()->groupBy('emp_code');
                $weekoff = Week_off::whereBetween('date', [$month_start, $month_end])
                    ->orderby('id', 'ASC')->get()->groupBy('emp_code');
                // print_r($weekoff);exit();

                foreach ($all_employees as $key => $value) {
                    $att_array[$value->emp_code]['emp_code'] = $value->emp_code . "-" . $value->firstname . " " . $value->middlename . " " . $value->lastname;
                    foreach ($period as $dt) {
                        $dt_key     = $dt->format("Y-m-d");
                        $att_array[$value->emp_code][$dt_key] = 'L';
                        $att_array[$value->emp_code]['total'] = '0';
                    }
                }
            } else {
                $attendance = Employee_attendance::whereBetween('attendance_date', [$month_start, $month_end])->where('employee_attendances.emp_code', Auth::user()->emp_code)
                    ->orderby('employee_attendances.id', 'ASC')->get()->groupBy('emp_code');
                $weekoff = Week_off::whereBetween('date', [$month_start, $month_end])->where('emp_code', Auth::user()->emp_code)
                    ->orderby('id', 'ASC')->get()->groupBy('emp_code');
                $att_array[Auth::user()->emp_code]['emp_code'] = Auth::user()->emp_code . "-" . Auth::user()->name;
                foreach ($period as $dt) {
                    $dt_key     = $dt->format("Y-m-d");
                    $att_array[Auth::user()->emp_code][$dt_key] = 'L';  //'-'
                    $att_array[Auth::user()->emp_code]['total'] = '0';
                }
            }

            $public_holiday = Holiday::whereBetween('holiday_date', [$month_start, $month_end])
                ->orderby('id', 'ASC')->get();


            $emp_leave = Employee_leave::with('leavetype')->where('leave_status', 'A')->get()->groupBy('emp_code');
            // print_r($emp_leave[0]->leavetype->leavetype);exit();   //leavetype
            foreach ($emp_leave as $elkey => $elvalue) {
                $el = 0;
                // foreach ($att_array as $emp_code) {
                // print_r($elvalue);
                foreach ($elvalue as $elkey1 => $elvalue1) {
                    // print_r($elvalue1->leavetype->leavetype);

                    if ($elvalue1->leavetype->leavetype === "Casual") {
                        $att_array[$elkey][$elvalue1->approved_from] = "CL";
                    } else if ($elvalue1->leavetype->leavetype === "Sick") {
                        $att_array[$elkey][$elvalue1->approved_from] = "SL";
                    } else if ($elvalue1->leavetype->leavetype === "Permission") {
                        $att_array[$elkey][$elvalue1->approved_from] = "per";
                    } else if ($elvalue1->leavetype->leavetype === "Others") {
                        $att_array[$elkey][$elvalue1->approved_from] = "oth";
                    } else if ($elvalue1->leavetype->leavetype === "Compensation Off") {
                        $att_array[$elkey][$elvalue1->approved_from] = "CF";
                    } else if ($elvalue1->leavetype->leavetype === "Half Day Casual") {
                        $att_array[$elkey][$elvalue1->approved_from] = "HD(CL)";
                    } else if ($elvalue1->leavetype->leavetype === "Half Day Sick Leave") {
                        $att_array[$elkey][$elvalue1->approved_from] = "HD(SL)";
                    } else if ($elvalue1->leavetype->leavetype === "Half Day LOP") {  //newly added
                        $att_array[$elkey][$elvalue1->approved_from] = "HD(LOP)";
                    } else {
                        $att_array[$elkey][$elvalue1->approved_from] = "L";
                    }

                    // $att_array[$elkey][$elvalue1->date] = "L";

                    $el = $el + 1;
                }
                // $att_array[$wokey]['total'] = $wo;
            }
            // exit();




            // for weekoff
            foreach ($weekoff as $wokey => $wovalue) {
                $wo = 0;
                foreach ($wovalue as $wokey1 => $wovalue1) {
                    $att_array[$wokey][$wovalue1->date] = "WO";
                    $wo = $wo + 1;
                }
                // $att_array[$wokey]['total'] = $wo;
            }

            // for public holiday
            foreach ($public_holiday as $phvalue) {
                $ph = 0;
                foreach ($att_array as $emp_code => $emp_data) {
                    $att_array[$emp_code][$phvalue['holiday_date']] = "PH";
                    // print_r($att_array[$phkey][$emp_code][$phvalue1->holiday_date]);
                    // print_r($emp_code);
                    $ph = $ph + 1;
                }
                // $att_array[$phkey]['total'] = $ph;
            }

            // for leave
            // foreach ($attendance as $key => $value) {
            //     $x = 0;
            //     //$att_array[$key]['emp_code'] = $key;
            //     foreach ($value as $key1 => $value1) {
            //         // added
            //         $att_array[$key][$value1->attendance_date] = "P";
            //         $x = $x + 1;
            //     }
            //     $att_array[$key]['total'] = $x;
            // }

            // for atendance
            foreach ($attendance as $key => $value) {
                $x = 0;
                //$att_array[$key]['emp_code'] = $key;
                foreach ($value as $key1 => $value1) {
                    // added
                    $att_array[$key][$value1->attendance_date] = "P";
                    $x = $x + 1;
                }
                $att_array[$key]['total'] = $x;
            }
            // exit();
            //dd($att_array);
            $data_array = array();
            // $data_array2[] = $x ;
            foreach ($att_array as $key => $value) {
                $data_array[] = $value;
            }
            return response()->json(['columns_header' => $arr_cols_header, 'columns' => $arr_cols, 'data' => $data_array, 'search' => $search]);
            //return DataTables::of($att_array)->addIndexColumn()->make(true);
        }
    }
    public function getMonthlyAttendancesCols(Request $request)
    {
    }

    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_emp_login(Request $request)
    {
        // print_r(Auth::user());exit();
        date_default_timezone_set("Asia/Kolkata");
        $login_time = Carbon::now()->format('H:i:s');
        $attendance_date = Carbon::now()->format('Y-m-d');
        $emp_code = Auth::user()->emp_code;
        $emp_shift = Auth::user()->shift_id;
        $emp_email = Auth::user()->email;
        $emp_name = Auth::user()->name;
        if ($request->clock == "clock_in") {
            $i = Employee_attendance::where('attendance_date', $attendance_date)->where('emp_code', $emp_code)->get();
            // print_r($i);exit();

            if (count($i) > 0) {
                if (Auth::user()->id == 1) {
                    return Redirect()->back();
                } else {
                    // return Redirect()->back();
                    return redirect()->route('dashboard')->with('error', "You have already clocked in for today!");
                    //return view('home');
                }
            }
            $data_id = Employee_attendance::insertGetId([
                'login_time' => $login_time,
                'emp_code' => $emp_code,
                'attendance_date' => $attendance_date,
                'added_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                // added
                'shift_id' => $emp_shift,
            ]);
            $data2 = [
                'emp_name' => $emp_name,
                'emp_exp_clockin' => '09:00:00'
            ];
            $data = Employee_attendance::find($data_id);
            // print_r($data);exit();


            // for mail trigger when late clockin starts
            if ($login_time != '') {
                if ($emp_shift != "1") {
                    $login_time_sec = strtotime($login_time) - strtotime('18:00:00');
                } else {
                    $login_time_sec = strtotime($login_time) - strtotime('09:00:00');
                    // print_r($emp_shift);
                }
                $get_late_mark_time = Setting::first();
                $late_mark_at_seconds = $get_late_mark_time->late_mark_at * 60;
                // print_r($login_time_sec . '-' .$late_mark_at_seconds);exit();
                if ($login_time_sec > $late_mark_at_seconds) {

                    $emailAddresses  = explode(', ', $get_late_mark_time->email_send_to);
                    $emailAddressesString = $emailAddresses[0];
                    $emailAddresses = json_decode($emailAddressesString, true);
                    // print_r($emailAddressesString);exit();
                    
                    // check for the not tl and member
                    $chk_mem = Employee_team::where('emp_code', $emp_code)
                        ->where('member_type', 'M')
                        ->where('is_active', 'Y')
                        ->first();
                    $get_tl_email_id = Null;
                    if ($chk_mem) {
                        $get_team_id = $chk_mem->team_id;
                        $chk_team = Team::where('id', $get_team_id)
                            ->first();
                        $get_tl_email_id = Employee_Personal_Detail::where('id', $chk_team->team_leader)
                            ->select('personal_emailID')
                            ->where('is_active', 'Y')
                            ->first();
                        if ($get_tl_email_id) {
                            $tl_id = $get_tl_email_id->personal_emailID;
                            $to = [$tl_id];
                        } 
                        $emailAddresses = array_merge($emailAddresses, $to);
                    }

                    Mail::to($emailAddresses)->send(new LateClockinAttendence($data, $data2));
                    // Mail::to("phpdeveloper2.docllp@gmail.com")->send(new LateClockinAttendence($data, $data2));
                }
            }


            // for mail trigger when late clockin ends

        } else if ($request->clock == "clock_out") {
            // print_r($request->all());exit();
            $in_date = $request->attendance_date;
            $in_time = $request->login;
            //  $break_time = "17:47:25";

            // $start_datetime = new \DateTime($in_time);
            $end_datetime = new \DateTime($login_time);

            // added
            $start_datetime = Carbon::parse($in_time . ' ' . $in_date);

            $difference = $end_datetime->diff($start_datetime);
            // $total_working_hours = $difference->format('%H:%I:%S');

            // Calculate the total hours
            $total_hours = $difference->h + $difference->days * 24;
            $total_working_time = sprintf('%02d:%02d:%02d', $total_hours, $difference->i, $difference->s);
            // print_r($total_working_time);exit();

            $updateDetails = [
                'logout_time' => $login_time,
                'total_working_hours' => $total_working_time,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
            $data_id = Employee_attendance::where('id', $request->at_id)->update($updateDetails);
            $data = Employee_attendance::find($data_id);
            // print_r($request->at_id);exit();

            $data2 = [
                'emp_name' => $emp_name,
                'emp_exp_clockout' => '18:00:00'
            ];

            // exit();
            if ($login_time != '') {

                if ($emp_shift != "1") {
                    // $login_time_sec = strtotime($login_time) - strtotime('18:00:00');
                    $login_time_sec = strtotime('02:00:00') - strtotime($login_time);
                } else {
                    $login_time_sec = strtotime('18:00:00') - strtotime($login_time);
                    // print_r($emp_shift);
                }
                if ($login_time_sec > 0) {

                    // to get the emails from the setting table
                    $get_email = Setting::first();
                    $emailAddresses  = explode(', ', $get_email->email_send_to);
                    $emailAddressesString = $emailAddresses[0];
                    $emailAddresses = json_decode($emailAddressesString, true);
                    
                    // check for the not tl and member 
                    $chk_mem = Employee_team::where('emp_code', $emp_code)
                        ->where('member_type', 'M')
                        ->where('is_active', 'Y')
                        ->first();
                    $get_tl_email_id = Null;
                    if ($chk_mem) {
                        $get_team_id = $chk_mem->team_id;
                        $chk_team = Team::where('id', $get_team_id)
                            ->first();
                        $get_tl_email_id = Employee_Personal_Detail::where('id', $chk_team->team_leader)
                            ->select('personal_emailID')
                            ->where('is_active', 'Y')
                            ->first();
                        if ($get_tl_email_id) {
                            $tl_id = $get_tl_email_id->personal_emailID;
                            $to = [$tl_id];
                        } 
                        $emailAddresses = array_merge($emailAddresses, $to);
                    }
                    // print_r($emailAddresses);exit();
                    Mail::to($emailAddresses)->send(new BeforeClockoutAttendence($data, $data2));
                }
            }
        }

        if (Auth::user()->id == 1) {
            return Redirect()->back();
        } else {
            // return Redirect()->back();
            return redirect()->route('dashboard')->with('success', "Attendance Added/Updated Successfully!");
            //return view('home');
        }
    }

    public function addAttendances(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");
        $get_emp_details = Employee_personal_detail::where('id', $request->employee_id)->first();
        // $login_time = Carbon::now()->format('H:i:s');
        // $logout_time = Carbon::now()->format('H:i:s');
        $login_time = $request->login_time;
        $logout_time = $request->logout_time;
        $attendance_date = $request->attendance_date;
        $emp_code = $get_emp_details->emp_code;
        $emp_shift = $get_emp_details->shift_id;

        // print_r($request->all());exit();
        if ($request->add_attendance == "add") {
            $i = Employee_attendance::where('attendance_date', $attendance_date)->where('emp_code', $emp_code)->get();
            // print_r($i);exit();

            if (count($i) > 0) {
                // echo '<script>alert("Failed");</script>';
                // exit();
                return redirect()->route('all.attendance')->with('error', "Attendance Already Exists!");
            } else {
                Employee_attendance::insert([
                    'login_time' => $login_time,
                    'logout_time' => $logout_time,
                    'emp_code' => $emp_code,
                    'attendance_date' => $attendance_date,
                    'shift_id' => $emp_shift,
                    'added_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                // echo '<script>alert("Success");</script>';exit();
                return redirect()->route('all.attendance')->with('success', "Attendance Added Successfully!");
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update_emp_login(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");
        $break_time = Carbon::now()->format('H:i:s');
        $morning_break_in = null;
        $total_break_hours = null;
        $updateDetails = null;
        $emp_code = Auth::user()->emp_code;
        $attendance = Employee_attendance::where('emp_code', $emp_code)
            ->where('attendance_date', Carbon::now()->format('Y-m-d'))->get();
        foreach ($attendance as $at) {
            $start_time = $at->morning_break_in;
            //  $break_time = "17:47:25";

            $start_datetime = new \DateTime($start_time);
            $end_datetime = new \DateTime($break_time);

            $difference = $end_datetime->diff($start_datetime);
            $total_break_hours = $difference->format('%H:%I:%S');
        }
        if ($request->break == "morning_break_in") {
            $updateDetails = [
                'morning_break_in' => $break_time,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
        } else if ($request->break == "morning_break_out") {

            $updateDetails = [
                'morning_break_out' => $break_time,
                'total_break_hours' => $total_break_hours,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
        } else if ($request->break == "lunch_break_in") {
            $updateDetails = [
                'lunch_break_in' => $break_time,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
        } else if ($request->break == "lunch_break_out") {

            $updateDetails = [
                'lunch_break_out' => $break_time,
                'total_break_hours' => $total_break_hours,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
        } else if ($request->break == "evening_break_in") {
            $updateDetails = [
                'evening_break_in' => $break_time,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
        } else if ($request->break == "evening_break_out") {
            $updateDetails = [
                'evening_break_out' => $break_time,
                'total_break_hours' => $total_break_hours,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
        }
        Employee_attendance::where('id', $request->at_id)->update($updateDetails);
        return Redirect()->back()->with('success', "Attendance updated successfully");
    }

    public function update(Request $request)
    {
        $login_time = $request->elogin;
        $logout_time = $request->elogout;
        $morning_break_in = $request->emymbi;
        $morning_break_out = $request->emymbo;
        $lunch_break_in = $request->emylbi;
        $lunch_break_out = $request->emylbo;
        $evening_break_in = $request->emyebi;
        $evening_break_out = $request->emyebo;
        $total_working_hours = $request->eowt;
        $total_break_hours = $request->eobt;

        $updateDetails = [
            'login_time' => $login_time,
            'logout_time' => $logout_time,
            'morning_break_in' => $morning_break_in,
            'morning_break_out' => $morning_break_out,
            'lunch_break_in' => $lunch_break_in,
            'lunch_break_out' => $lunch_break_out,
            'evening_break_in' => $evening_break_in,
            'evening_break_out' => $evening_break_out,
            'total_working_hours' => $total_working_hours,
            'total_break_hours' => $total_break_hours,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ];

        Employee_attendance::where('id', $request->emyid)->update($updateDetails);
        return Redirect()->back()->with('success', "Attendance updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }



    public function exportSampleExcel()
    {
        // Create a new PhpSpreadsheet object
    }

    // with normal excel
    // public function generateReport(Request $request)
    // {
    //     // print_r($request->all());exit();
    //     date_default_timezone_set('Asia/Kolkata');

    //     $startdate = $_POST["start"];
    //     $newstartdate = strtotime($startdate);
    //     $startmonth = date('M', $newstartdate);
    //     $startyear = date('Y', $newstartdate);

    //     $endmonth = date("M", strtotime("+1 month", $newstartdate));
    //     $endyear = date("Y", strtotime("+1 month", $newstartdate));

    //     if ($startyear != $endyear) {
    //         $endyear = $endyear;
    //     } else {
    //         $endyear = $startyear;
    //     }

    //     $getstartmonth = date('m', $newstartdate);
    //     $getendmonth = date("m", strtotime("+1 month", $newstartdate));
    //     $startdate = date("$startyear-" . $getstartmonth . "-26");
    //     $enddate = date("$endyear-" . $getendmonth . "-25");

    //     // functon to calculate the working days
    //     function dateDiffInDays($date1, $date2)
    //     {
    //         $diff = strtotime($date2) - strtotime($date1);
    //         return abs(round($diff / 86400));
    //     }
    //     $dateDiff = dateDiffInDays($startdate, $enddate) + 1;


    //     // Filter the excel data 
    //     function filterData(&$str)
    //     {
    //         $str = preg_replace("/\t/", "\\t", $str);
    //         $str = preg_replace("/\r?\n/", "\\n", $str);
    //         if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    //     }

    //     // Excel file name for download 
    //     $fileName = "attendance_" . date('Y-m-d') . ".xls";

    //     // Column names
    //     $heading = array("STAFFS  ATTENDANCE SHEET FOR THE PERIOD FROM 26-$startmonth-$startyear To 25-$endmonth-$endyear");
    //     $fields = array('SNo', 'Name');
    //     $fields2 = array('Total');

    //     // get dates list
    //     function displayDates($date1, $date2, $format = 'd-m-Y')
    //     {     //d-m-Y
    //         $dates = array();
    //         $current = strtotime($date1);
    //         $date2 = strtotime($date2);
    //         $stepVal = '+1 day';
    //         while ($current <= $date2) {
    //             $dates[] = date($format, $current);
    //             $current = strtotime($stepVal, $current);
    //         }
    //         return $dates;
    //         //   return $dates[0];
    //     }
    //     $date = displayDates($startdate, $enddate);

    //     //   $size = sizeof($date);
    //     $fields = array_merge($fields, $date);
    //     $fields = array_merge($fields, $fields2);


    //     $excelData = implode("\t", array_values($heading)) . "\n";
    //     $excelData .= implode("\t", array_values($fields)) . "\n";

    //     // Headers for download 
    //     header("Content-Type: application/vnd.ms-excel");
    //     header("Content-Disposition: attachment; filename=\"$fileName\"");

    //     // Render excel data 
    //     echo $excelData;
    // }


    // with  maatwebsite excel

    // public function generateReport(Request $request)
    // {
    //     date_default_timezone_set('Asia/Kolkata');

    //     $startdate = $request->input('start');
    //     $newstartdate = strtotime($startdate);
    //     $startmonth = date('M', $newstartdate);
    //     $startyear = date('Y', $newstartdate);

    //     $endmonth = date("M", strtotime("+1 month", $newstartdate));
    //     $endyear = date("Y", strtotime("+1 month", $newstartdate));

    //     if ($startyear != $endyear) {
    //         $endyear = $endyear;
    //     } else {
    //         $endyear = $startyear;
    //     }

    //     $getstartmonth = date('m', $newstartdate);
    //     $getendmonth = date("m", strtotime("+1 month", $newstartdate));
    //     $startdate = date("$startyear-" . $getstartmonth . "-26");
    //     $enddate = date("$endyear-" . $getendmonth . "-25");

    //     // Function to calculate the working days
    //     function dateDiffInDays($date1, $date2)
    //     {
    //         $diff = strtotime($date2) - strtotime($date1);
    //         return abs(round($diff / 86400));
    //     }
    //     $dateDiff = dateDiffInDays($startdate, $enddate) + 1;

    //     // Get dates list
    //     function displayDates($date1, $date2, $format = 'd-m-Y')
    //     {
    //         $dates = array();
    //         $current = strtotime($date1);
    //         $date2 = strtotime($date2);
    //         $stepVal = '+1 day';
    //         while ($current <= $date2) {
    //             $dates[] = date($format, $current);
    //             $current = strtotime($stepVal, $current);
    //         }
    //         return $dates;
    //     }
    //     $dates = displayDates($startdate, $enddate);

    //     // Create data array for Excel
    //     $data = [];
    //     $data[] = ["STAFFS  ATTENDANCE SHEET FOR THE PERIOD FROM 26-$startmonth-$startyear To 25-$endmonth-$endyear"];
    //     $data[] = ['SNo', 'Name', ...$dates, 'Total'];

    //     $fileName = "attendance_" . date('Y-m-d') . ".xlsx";

    //     // Set headers for download 
    //     return Excel::download(new AttendanceExport($data), $fileName);
    // }


    // for testing
    // public function getMonthlyAttendances2()
    // {
    //     $month = 02;
    //     $year = 2024;

    //         $attendance = null;
    //         $search = array();

    //         if ($month) {
    //             $month = trim($month);
    //             $search['month'] = $month;
    //         } else {
    //             $month = Carbon::now()->format('m');
    //             $search['month'] = $month;
    //         }
    //         if ($year) {
    //             $year = trim($year);
    //             $search['year'] = $year;
    //         } else {
    //             $year = Carbon::now()->format('Y');
    //             $search['year'] = $year;
    //         }

    //         $month_start = Carbon::createFromDate($year, $month)->startOfMonth()->format('Y-m-d');
    //         $month_end = Carbon::createFromDate($year, $month)->endOfMonth();
    //         $period = CarbonPeriod::create($month_start, $month_end);

    //         $att_array = array();
    //         $arr_cols = array();
    //         $arr_cols_header = array();
    //         $arr_cols[] = 'emp_code';
    //         $arr_cols_header[] = 'Employee';

    //         $arr_cols_header2[] = 'Total';
    //         $arr_cols_total[] = 'total';
    //         foreach ($period as $dt) {
    //             $dt_key     = $dt->format("Y-m-d");
    //             $dt_f     = $dt->format("d");
    //             $arr_cols[] = $dt_key;
    //             $arr_cols_header[] = $dt_f;
    //         }
    //         $arr_cols_header = array_merge($arr_cols_header, $arr_cols_header2);
    //         $arr_cols = array_merge($arr_cols, $arr_cols_total);
    //         if (Auth::user()->id == 1) {
    //             $all_employees = Employee_personal_detail::where('emp_code', '<>', NULL)->get();
    //             $attendance = Employee_attendance::whereBetween('attendance_date', [$month_start, $month_end])
    //                 ->where('employee_attendances.emp_code', '<>', Auth::user()->emp_code)
    //                 ->orderby('employee_attendances.id', 'ASC')->get()->groupBy('emp_code');

    //             foreach ($all_employees as $key => $value) {
    //                 $att_array[$value->emp_code]['emp_code'] = $value->emp_code . "-" . $value->firstname . " " . $value->middlename . " " . $value->lastname;
    //                 foreach ($period as $dt) {
    //                     //echo $dt->format("Y-m-d") . "<br>\n";
    //                     $dt_key     = $dt->format("Y-m-d");
    //                     $att_array[$value->emp_code][$dt_key] = '-';
    //                     $att_array[$value->emp_code]['total'] = '0';
    //                 }        
    //             }
    //         } else {
    //             $attendance = Employee_attendance::whereBetween('attendance_date', [$month_start, $month_end])->where('employee_attendances.emp_code', Auth::user()->emp_code)
    //                 ->orderby('employee_attendances.id', 'ASC')->get()->groupBy('emp_code'); 
    //             $att_array[Auth::user()->emp_code]['emp_code'] = Auth::user()->emp_code . "-" . Auth::user()->name;
    //             foreach ($period as $dt) {
    //                 $dt_key     = $dt->format("Y-m-d");
    //                 $att_array[Auth::user()->emp_code][$dt_key] = '-';
    //                 $att_array[Auth::user()->emp_code]['total'] = '0';
    //             }
    //         }
    //         $each_attendance = array();
    //         $working_hours = "";
    //         foreach ($attendance as $key => $value) {
    //             $x = 0;
    //             //$att_array[$key]['emp_code'] = $key;
    //             foreach ($value as $key1 => $value1) {
    //                 // added
    //                 $att_array[$key][$value1->attendance_date] = "P";
    //                 $x = $x + 1;
    //             }
    //             $att_array[$key]['total'] = $x;
    //         }
    //         $data_array = array();
    //         foreach ($att_array as $key => $value) {
    //             $data_array[] = $value;
    //         }
    //         return response()->json(['columns_header' => $arr_cols_header, 'columns' => $arr_cols, 'data' => $data_array, 'search' => $search]);




    // }


    // public function generateExcel()
    // {
    //     $month = 03; //month
    //     $year = 2024; //year

    //     $attendance = null;
    //     $search = array();

    //     if ($month) {
    //         $month = trim($month);
    //         $search['month'] = $month;
    //     } else {
    //         $month = Carbon::now()->format('m');
    //         $search['month'] = $month;
    //     }
    //     if ($year) {
    //         $year = trim($year);
    //         $search['year'] = $year;
    //     } else {
    //         $year = Carbon::now()->format('Y');
    //         $search['year'] = $year;
    //     }

    //     $month_start = Carbon::createFromDate($year, $month)->startOfMonth()->format('Y-m-d');
    //     $month_end = Carbon::createFromDate($year, $month)->endOfMonth();
    //     $period = CarbonPeriod::create($month_start, $month_end);

    //     $att_array = array();

    //     $arr_cols_header = array();
    //     $arr_cols_header[] = 'Employee';
    //     $arr_cols = array();
    //     $arr_cols[] = 'emp_code';

    //     $arr_cols_header2 = array();
    //     $arr_cols_header2[] = 'Total';
    //     $arr_cols_total = array();
    //     $arr_cols_total[] = 'total';

    //     $sl = 1; // Initialize serial number counter
    //     $arr_cols_header3 = ['SL']; // Add 'SL' as the first column header
    //     $arr_cols3 = ['sl'];





    //     foreach ($period as $dt) {
    //         $dt_key     = $dt->format("Y-m-d");
    //         $dt_f     = $dt->format("d");
    //         $arr_cols_header[] = $dt_f;
    //         $arr_cols[] = $dt_key;
    //     }
    //     $arr_cols_header = array_merge($arr_cols_header, $arr_cols_header2);
    //     $arr_cols = array_merge($arr_cols, $arr_cols_total);
    //     if (Auth::user()->id == 1) {  //for all user except superadmin
    //         $all_employees = Employee_personal_detail::where('emp_code', '<>', NULL)->get();
    //         $attendance = Employee_attendance::whereBetween('attendance_date', [$month_start, $month_end])
    //             ->where('employee_attendances.emp_code', '<>', Auth::user()->emp_code)
    //             ->orderby('employee_attendances.id', 'ASC')->get()->groupBy('emp_code');

    //         foreach ($all_employees as $key => $value) {
    //             $att_array[$value->emp_code]['emp_code'] = $value->emp_code . "-" . $value->firstname . " " . $value->middlename . " " . $value->lastname;
    //             foreach ($period as $dt) {
    //                 //echo $dt->format("Y-m-d") . "<br>\n";
    //                 $dt_key     = $dt->format("Y-m-d");
    //                 $att_array[$value->emp_code][$dt_key] = '-';
    //             }
    //             $att_array[$value->emp_code]['total'] = '0';
    //         }
    //     } else {  //for individual user
    //         $attendance = Employee_attendance::whereBetween('attendance_date', [$month_start, $month_end])->where('employee_attendances.emp_code', Auth::user()->emp_code)
    //             ->orderby('employee_attendances.id', 'ASC')->get()->groupBy('emp_code');
    //         $att_array[Auth::user()->emp_code]['emp_code'] = Auth::user()->emp_code . "-" . Auth::user()->name;
    //         foreach ($period as $dt) {
    //             $dt_key     = $dt->format("Y-m-d");
    //             $att_array[Auth::user()->emp_code][$dt_key] = '-';
    //         }
    //         $att_array[Auth::user()->emp_code]['total'] = '0';
    //     }
    //     foreach ($attendance as $key => $value) {

    //         // print_r($key);
    //         $x = 0;
    //         //$att_array[$key]['emp_code'] = $key;
    //         foreach ($value as $key1 => $value1) {
    //             // added
    //             $att_array[$key][$value1->attendance_date] = "P";
    //             $x = $x + 1;
    //         }
    //         $att_array[$key]['total'] = $x;
    //     }

    //     // exit();
    //     $data_array = array();
    //     foreach ($att_array as $key => $value) {
    //         $data_array[] = $value;
    //     }

    //  // for sl
    //     foreach ($data_array as &$row) {
    //         $row['sl'] = $sl++; // Assign serial number
    //     }
    //     $arr_cols_header = array_merge($arr_cols_header, $arr_cols_header3);
    //     $arr_cols = array_merge($arr_cols, $arr_cols3);


    //     // print_r($data_array);exit();
    //     // Generate Excel using Maatwebsite
    //     return Excel::download(new MonthlyAttendancesExport($arr_cols_header, $data_array), 'monthly_attendances.xlsx');
    // }


    public function generateExcel(Request $request)
    {
        // print_r($request->all());exit();
        $month = $request->month;
        $year = $request->year;

        $attendance = null;
        $search = array();

        if ($month) {
            $month = trim($month);
            $search['month'] = $month;
        } else {
            $month = Carbon::now()->format('m');
            $search['month'] = $month;
        }
        if ($year) {
            $year = trim($year);
            $search['year'] = $year;
        } else {
            $year = Carbon::now()->format('Y');
            $search['year'] = $year;
        }

        $month_start = Carbon::createFromDate($year, $month)->startOfMonth()->format('Y-m-d');
        $month_end = Carbon::createFromDate($year, $month)->endOfMonth();
        $period = CarbonPeriod::create($month_start, $month_end);

        $att_array = [];

        $arr_cols_header = [
            ['Monthly Attendance Report'],
            [''],
            ['S.No', 'Employee']
        ];
        $arr_day_header = ['', ''];
        $arr_cols = ['s.no', 'emp_code'];

        foreach ($period as $dt) {
            // print_r($dt);
            $arr_cols_header[2][] = $dt->format("d");
            $arr_cols[] = $dt->format("Y-m-d");
            $arr_day_header[] = $dt->format('D');
        }

        $headerColumnPairs = [
            ['Total Days', 'total_days'],
            ['Total', 'total'],
            ['Leaves', 'leaves'],

            ['', ''], ['', ''], ['', ''], ['', ''],
            ['', ''], ['', ''], ['', ''], ['', ''],

            ['No of Days Worked', 'day_w'], ['CL Avilable', 'cl_avai'], ['This month CL', 'this_m_cl'],
            ['CL+L (THIS MONTH)', 'CL+L'], ['CL Avilable Now', 'cl_avai_now'], ['Basic', 'basic'],
            ['HRA', 'hra'], ['Fixed Gross', 'fixed_gross'], ['Basic2', 'basic2'],
            ['HRA2', 'hra2'], ['Gross', 'gross'], ['PF Employer', 'pf_employer'],
            ['ESI Employer', 'esi_employer'], ['CTC', 'ctc'], ['PF Employee', 'pf_employee'],
            ['ESIC employee', 'esic_employee'], ['PT', 'pt'], ['Net Pay', 'net_pay'],
            ['Comments', 'comments'],

        ];

        foreach ($headerColumnPairs as $pair) {
            list($header, $column) = $pair;
            $arr_cols_header[2][] = $header;
            $arr_cols[] = $column;
        }

        // Add 9 subheaders under 'Leaves'
        $leaveTypes = [
            '', '', 'WO', 'CL', 'CF',  'L', 'SL', 'HD(SL)', 'HD(CL)', 'HD(LOP)', 'Tot Leaves'
        ];
        foreach ($leaveTypes as $leaveType) {
            $arr_day_header[] = $leaveType;
            $arr_cols[] = str_replace(' ', '_', $leaveType);
        }
        $sl_no = 1;
        $all_employees = Employee_personal_detail::with('empaddedBy')->whereNotNull('emp_code')->get();
        $attendance = Employee_attendance::whereBetween('attendance_date', [$month_start, $month_end])
            ->where('employee_attendances.emp_code', '<>', Auth::user()->emp_code)
            ->orderby('employee_attendances.id', 'ASC')->get()->groupBy('emp_code');
        $weekoff = Week_off::whereBetween('date', [$month_start, $month_end])
            ->orderby('id', 'ASC')->get()->groupBy('emp_code');
        $public_holiday = Holiday::whereBetween('holiday_date', [$month_start, $month_end])
            ->orderby('id', 'ASC')->get();
        $emp_leave = Employee_leave::with('leavetype')->where('leave_status', 'A')
            // added
            ->where(function ($query) use ($month_start, $month_end) {
                $query->where(function ($q) use ($month_start, $month_end) {
                    $q->where('approved_from', '>=', $month_start)
                        ->where('approved_from', '<=', $month_end);
                })->orWhere(function ($q) use ($month_start, $month_end) {
                    $q->where('approved_to', '>=', $month_start)
                        ->where('approved_to', '<=', $month_end);
                })->orWhere(function ($q) use ($month_start, $month_end) {
                    $q->where('approved_from', '<=', $month_start)
                        ->where('approved_to', '>=', $month_end);
                });
            })

            ->get()->groupBy('emp_code');

        // print_r($all_employees);exit();

        // starts appending into the excel
        foreach ($all_employees as $key => $value) {

            $employees_payroll = EmployeePayroll::with('employee')
                ->where('emp_id', $value->id)
                ->where('year', '<=', $year)
                // ->whereRaw("CONVERT(month, UNSIGNED INTEGER) <= ?", [(int)$month]) // Ensure proper integer comparison
                ->where(function ($query) use ($year, $month) {
                    $query->where('year', '<', $year)
                        ->orWhere(function ($q) use ($year, $month) {
                            $q->where('year', $year)
                                ->whereRaw("CONVERT(month, UNSIGNED INTEGER) <= ?", [(int)$month]);
                        });
                })
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->first();
            if ($employees_payroll) {
                $category = $employees_payroll->category;
                $gross_sal = $employees_payroll->gross_sal;
            } else {
                $category = 'Category 1';
                $gross_sal = 0;
            }
            // print_r($employees_payroll);
            // exit();

            // Set default values for each employee
            $att_array[$value->emp_code] = [
                's.no' => $sl_no++,
                'emp_code' => $value->emp_code . "-" . $value->firstname . " " . $value->middlename . " " . $value->lastname,
            ];
            // Set default leave values for each day
            $tot_leave = $leave = 0;
            $cl = $sl = $cf =  $hdsl = $hdcl = $hdlop = $per = $oth = 0;

            $cl_available = 0;
            $this_m_cl = 1;
            $cl_taken = 0;
            $cl_available_carry_forward = 0;
            foreach ($period as $dt) {
                $att_array[$value->emp_code][$dt->format("Y-m-d")] = 'L'; // Set default leave value
                $tot_leave++;
                $leave++;
            }
            // other column headers with default values
            $att_array[$value->emp_code] += [
                'total_days' => count($period),
                'total' => '0',
                'WO' => '0',
                'CL' => '0',
                'CF' => '0',
                'L' => '0',
                'SL' => '0',
                'HD(SL)' => '0',
                'HD(CL)' => '0',
                'HD(LOP)' => '0',
                'Tot Leaves' => '0',
                'day_w' => '0',
                'cl_avai' => '0',
                'this_m_cl' => '0',
                'CL+L' => '0',
                'cl_avai_now' => '0',
                'basic' => '0',
                'hra' => '0',
                'fixed_gross' => '0',
                'basic2' => '0',
                'hra2' => '0',
                'gross' => '0',
                'pf_employer' => '0',
                'esi_employer' => '0',
                'ctc' => '0',
                'pf_employee' => '0',
                'esic_employee' => '0',
                'pt' => '0',
                'net_pay' => '0',
                'comments' => ''
            ];


            // Process employee leaves
            if (isset($emp_leave[$value->emp_code])) {

                foreach ($emp_leave[$value->emp_code] as $elkey1 => $elvalue1) {
                    $leaveType = $elvalue1->leavetype->leavetype;
                    $approvedFrom = Carbon::parse($elvalue1->approved_from);
                    $approvedTo = Carbon::parse($elvalue1->approved_to);
                    // $approvedFrom = Carbon::parse('2024-03-14');
                    // $approvedTo = Carbon::parse('2024-03-15');
                    $leaveDays = $approvedFrom->diffInDays($approvedTo) + 1;
                    // print_r($leaveDays);
                    // exit();
                    switch ($leaveType) {
                        case "Casual":
                            // $att_array[$value->emp_code][$elvalue1->approved_from] = "CL";
                            // $cl++;
                            if ($leaveDays < 2) {
                                $att_array[$value->emp_code][$elvalue1->approved_from] = "CL";
                                $cl++;
                            } else {
                                // If duration is not 2 days, mark each day as CL
                                for ($i = 0; $i < $leaveDays; $i++) {
                                    $leaveDate = $approvedFrom->copy()->addDays($i)->format('Y-m-d');
                                    $att_array[$value->emp_code][$leaveDate] = "CL";
                                    $cl++;
                                }
                            }
                            break;
                        case "Sick":
                            $att_array[$value->emp_code][$elvalue1->approved_from] = "SL";
                            $sl++;
                            break;
                        case "Permission":
                            $att_array[$value->emp_code][$elvalue1->approved_from] = "Per";
                            $per++;
                            break;
                        case "Others":
                            $att_array[$value->emp_code][$elvalue1->approved_from] = "Oth";
                            $oth++;
                            break;
                        case "Compensation Off":
                            $att_array[$value->emp_code][$elvalue1->approved_from] = "CF";
                            $cf++;
                            break;
                        case "Half Day Casual":
                            $att_array[$value->emp_code][$elvalue1->approved_from] = "HD(CL)";
                            $hdcl = $hdcl + 0.5;
                            break;
                        case "Half Day Sick Leave":
                            $att_array[$value->emp_code][$elvalue1->approved_from] = "HD(SL)";
                            $hdsl = $hdsl + 0.5;
                            break;
                        case "Leave":
                            $att_array[$value->emp_code][$elvalue1->approved_from] = "L";
                            $leave++;
                            break;
                        case "Half Day LOP":
                            $att_array[$value->emp_code][$elvalue1->approved_from] = "HD(LOP)";
                            $hdlop = $hdlop + 0.5;
                            break;
                    }
                }
            }


            // Process week offs
            if (isset($weekoff[$value->emp_code])) {
                $wo = 0;
                foreach ($weekoff[$value->emp_code] as $wokey1 => $wovalue1) {
                    $att_array[$value->emp_code][$wovalue1->date] = "WO";
                    $wo++;
                }
                $att_array[$value->emp_code]['WO'] = $wo;
                $tot_leave -= $wo;
                $leave -= $wo;
            }
            // Process public holidays
            foreach ($public_holiday as $phvalue) {
                $att_array[$value->emp_code][$phvalue->holiday_date] = "PH";
                $tot_leave--;
                $leave--;
            }
            // Process attendance
            if (isset($attendance[$value->emp_code])) {
                $x = count($attendance[$value->emp_code]);
                foreach ($attendance[$value->emp_code] as $value1) {
                    $att_array[$value->emp_code][$value1->attendance_date] = "P";
                }
                $att_array[$value->emp_code]['total'] = $x;
                $tot_leave -= $x;
                $leave -= $x;
            }

            $tot_leave = $tot_leave - $hdlop;

            $att_array[$value->emp_code]['CL'] = $cl;
            $att_array[$value->emp_code]['CF'] = $cf;
            $att_array[$value->emp_code]['L'] = $leave;
            $att_array[$value->emp_code]['SL'] = $sl;
            $att_array[$value->emp_code]['HD(SL)'] = $hdsl;
            $att_array[$value->emp_code]['HD(CL)'] = $hdcl;
            $att_array[$value->emp_code]['HD(LOP)'] = $hdlop;
            $att_array[$value->emp_code]['Tot Leaves'] = $tot_leave;
            $att_array[$value->emp_code]['day_w'] = count($period) - $tot_leave;



            // CL Calculation starts
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
            // $joining_date_query = Employee_official_detail::where('emp_code', 'JSC002')->first();
            $joining_date_query = Employee_official_detail::where('emp_code', $value->emp_code)->first();

            if ($joining_date_query) {
                $joining_date = strtotime($joining_date_query->joining_date);
                $jmonth = date("F", $joining_date);
                $jyear = date("Y", $joining_date);
                $jday = date('d', $joining_date);
                $jmonth_no = date("m", $joining_date);

                if ($jday > 15 && $jmonth_no > 3) {
                    $leave_avail = $leave_avail - 1;
                }
            }
            // for the past months of the year
            if ($month < 4) {
                $next_year = $year - 1;
                $start_date = $next_year . "-04-01"; // Adjusted end date
                $end_date = $year . "-" . $month . "-31";
            } else {
                $next_year = $year + 1;
                $start_date = $year . "-04-01";
                $end_date = $year . "-" . $month . "-31"; // Adjusted end date
            }
            // print_r($end_date);
            // exit();
            // $past_month_cl_taken = Employee_leave::where('emp_code', 'JSC002')
            $past_month_cl_taken = Employee_leave::where('emp_code', $value->emp_code)
                ->whereIn('leavetype_id', [1, 6]) // Using whereIn instead of multiple orWhere
                ->where('leave_status', 'A')
                ->whereBetween('approved_from', [$start_date, $end_date])
                ->get();
            // echo $casual_leaves_taken;

            foreach ($past_month_cl_taken as $past_month_cl) {
                $approved_days = $past_month_cl->approved_days;
                $cl_taken = $cl_taken + $approved_days;
            }
            // $this_month_cl_taken = Employee_leave::where('emp_code', 'JSC002')
            $this_month_cl_taken = Employee_leave::where('emp_code', $value->emp_code)
                ->whereIn('leavetype_id', [1, 6])
                ->where('leave_status', 'A')
                ->where('year', $year) // Filter by year
                ->where('month', intval($month)) // Filter by month
                ->get();
            // print_r($this_month_cl_taken);
            // exit();

            if ($this_month_cl_taken) {
                // foreach ($this_month_cl_taken as $this_month_cl) {
                // if ($this_month_cl->leavetype_id == 6 || $this_month_cl->leavetype_id == '6') {
                //     $this_m_cl = 0.5;  //for hdcl
                // } else {
                $this_m_cl = 0; //for cl
                // }
                // }
            }

            $cl_available = $leave_avail - $cl_taken - 1;  //upto past month
            // $this_m_cl = $this_m_cl;
            // $cl_taken = $cl_taken;
            $cl_available_carry_forward = $cl_available + $this_m_cl - $cl_taken;  //carry forward cl
            $att_array[$value->emp_code]['cl_avai'] = $cl_available;
            $att_array[$value->emp_code]['this_m_cl'] = $this_m_cl;
            $att_array[$value->emp_code]['CL+L'] = $cl_taken;
            $att_array[$value->emp_code]['cl_avai_now'] = $cl_available_carry_forward;
            // print_r($this_m_cl);
            // exit();
            // CL Calculation ends


            // payroll category wise calculation
            // $category = 'Category 1';
            // $gross_sal = 30000;
            $basic = $gross_sal * 0.8;
            $hra = $gross_sal * 0.2;
            $fixed_gross = $gross_sal;
            $basic2 = round(($basic / count($period)) * (count($period) - $tot_leave));
            $hra2 = round(($hra / count($period)) * (count($period) - $tot_leave));
            $gross = round($basic2 + $hra2);
            $pt = 209;

            if ($category == 'Category 1') {

                $ctc = round($basic2 + $hra2);
                $net_pay = round(($basic2 + $hra2) - 209);

                $att_array[$value->emp_code]['basic'] = $basic;
                $att_array[$value->emp_code]['hra'] = $hra;
                $att_array[$value->emp_code]['fixed_gross'] = $fixed_gross;
                $att_array[$value->emp_code]['basic2'] = $basic2;
                $att_array[$value->emp_code]['hra2'] = $hra2;
                $att_array[$value->emp_code]['gross'] = $gross;
                $att_array[$value->emp_code]['pf_employer'] = 0;
                $att_array[$value->emp_code]['esi_employer'] = 0;
                $att_array[$value->emp_code]['pf_employee'] = 0;
                $att_array[$value->emp_code]['esic_employee'] = 0;
                $att_array[$value->emp_code]['ctc'] = $ctc;
                $att_array[$value->emp_code]['pt'] = $pt;
                $att_array[$value->emp_code]['net_pay'] = $net_pay;
            } elseif ($category == 'Category 2') {

                $att_array[$value->emp_code]['basic'] = $basic;
                $att_array[$value->emp_code]['hra'] = $hra;
                $att_array[$value->emp_code]['fixed_gross'] = $fixed_gross;
                $att_array[$value->emp_code]['basic2'] = $basic2;
                $att_array[$value->emp_code]['hra2'] = $hra2;
                $att_array[$value->emp_code]['gross'] = $gross;
                if ($basic2 < 15000) {  //13 %
                    $PF_Employer = $basic2 * 0.13;
                    $PF_Employee = $basic2 * 0.12;
                } else {  //12 %
                    $PF_Employer = 15000 * 0.13;
                    $PF_Employee = 15000 * 0.12;
                }
                if ($fixed_gross < 21000) {  //3.25 %
                    $ESI_Employer = $gross * 0.0325;
                    $ESIC_Employee = $gross * 0.0075;
                } else {  //0.75 %
                    $ESI_Employer = 0;
                    $ESIC_Employee = 0;
                }
                $att_array[$value->emp_code]['pf_employer'] = $PF_Employer;
                $att_array[$value->emp_code]['esi_employer'] = $ESI_Employer;
                $att_array[$value->emp_code]['pf_employee'] = $PF_Employee;
                $att_array[$value->emp_code]['esic_employee'] = $ESIC_Employee;
                $res_array[$value->emp_code]['ctc']           = $gross + $PF_Employer + $ESI_Employer;
                $att_array[$value->emp_code]['pt'] = $pt;
                $res_array[$value->emp_code]['net_pay']       = $gross - ($PF_Employee + $ESIC_Employee + $pt);
            } elseif ($category == 'Category 3') {


                $att_array[$value->emp_code]['basic'] = $basic;
                $att_array[$value->emp_code]['hra'] = $hra;
                $att_array[$value->emp_code]['fixed_gross'] = $fixed_gross;
                $att_array[$value->emp_code]['basic2'] = $basic2;
                $att_array[$value->emp_code]['hra2'] = $hra2;
                $att_array[$value->emp_code]['gross'] = $gross;
                if ($basic2 < 15000) {  //13 %
                    $PF_Employer = $basic2 * 0.13;
                    $PF_Employee = $basic2 * 0.12;
                } else {  //12 %
                    $PF_Employer = 15000 * 0.13;
                    $PF_Employee = 15000 * 0.12;
                }
                $ESI_Employer = 0;
                $ESIC_Employee = 0;
                $att_array[$value->emp_code]['pf_employer'] = $PF_Employer;
                $att_array[$value->emp_code]['esi_employer'] = $ESI_Employer;
                $att_array[$value->emp_code]['pf_employee'] = $PF_Employee;
                $att_array[$value->emp_code]['esic_employee'] = $ESIC_Employee;
                $res_array[$value->emp_code]['ctc']           = $gross + $PF_Employer;
                $att_array[$value->emp_code]['pt'] = $pt;
                $res_array[$value->emp_code]['net_pay']       = $gross - ($PF_Employee + $pt);
            }
            // END of the excel sheet data feeding
        }


        $data_array = array_values($att_array);
        $arr_cols_header = array_merge($arr_cols_header, [$arr_day_header]); // Merge the day headers

        // print_r($data_array);
        // exit();

        // Generate Excel using Maatwebsite
        return Excel::download(new MonthlyAttendancesExport($arr_cols_header, $data_array), 'monthly_attendances_' . $year . '_' . $month . '.xlsx');
    }
}



































// if (Auth::user()->id == 1) {  //for all user except superadmin
// } else {  //for individual user
//     $attendance = Employee_attendance::whereBetween('attendance_date', [$month_start, $month_end])->where('employee_attendances.emp_code', Auth::user()->emp_code)
//         ->orderby('employee_attendances.id', 'ASC')->get()->groupBy('emp_code');
//     $att_array[Auth::user()->emp_code]['sl'] = $sl++;
//     $att_array[Auth::user()->emp_code]['emp_code'] = Auth::user()->emp_code . "-" . Auth::user()->name;
//     foreach ($period as $dt) {
//         $dt_key     = $dt->format("Y-m-d");
//         $att_array[Auth::user()->emp_code][$dt_key] = '-';
//     }
//     $att_array[Auth::user()->emp_code]['total'] = '0';
// }





    //for leasve old
        // foreach ($emp_leave as $elkey => $elvalue) {
        //     $leave = $cl = $sl = $permission = $oth = $cf = $hdcl = $hdsl = $hdlop = 0;
        //     // foreach ($att_array as $emp_code) {
        //     // print_r($elvalue);
        //     foreach ($elvalue as $elkey1 => $elvalue1) {
        //         // print_r($elvalue1->leavetype->leavetype);

        //         if ($elvalue1->leavetype->leavetype === "Casual") {
        //             $att_array[$elkey][$elvalue1->approved_from] = "CL";
        //             $cl = $cl + 1;
        //         } else if ($elvalue1->leavetype->leavetype === "Sick") {
        //             $att_array[$elkey][$elvalue1->approved_from] = "SL";
        //             $sl = $sl + 1;
        //         } else if ($elvalue1->leavetype->leavetype === "Permission") {
        //             $att_array[$elkey][$elvalue1->approved_from] = "per";
        //             $permission = $permission + 1;
        //         } else if ($elvalue1->leavetype->leavetype === "Others") {
        //             $att_array[$elkey][$elvalue1->approved_from] = "oth";
        //             $oth = $oth + 1;
        //         } else if ($elvalue1->leavetype->leavetype === "Compensation Off") {
        //             $att_array[$elkey][$elvalue1->approved_from] = "CF";
        //             $cf = $cf + 1;
        //         } else if ($elvalue1->leavetype->leavetype === "Half Day Casual") {
        //             $att_array[$elkey][$elvalue1->approved_from] = "HD(CL)";
        //             $hdcl = $hdcl + 1;
        //         } else if ($elvalue1->leavetype->leavetype === "Half Day Sick Leave") {
        //             $att_array[$elkey][$elvalue1->approved_from] = "HD(SL)";
        //             $hdcl = $hdsl + 1;
        //         } else if ($elvalue1->leavetype->leavetype === "Half Day LOP") {  //newly added
        //             $att_array[$elkey][$elvalue1->approved_from] = "HD(LOP)";
        //             $hdcl = $hdlop + 1;
        //         } else {
        //             $att_array[$elkey][$elvalue1->approved_from] = "L";
        //             $leave = $leave + 1;
        //         }
        //         // $att_array[$elkey][$elvalue1->date] = "L";
        //         // $leave = $leave + 1;
        //     }
        //     // $att_array[$elkey]['CL'] = $cl;

        //     // $att_array[$wokey]['total'] = $wo;
        // }
