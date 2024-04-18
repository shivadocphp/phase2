<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee_personal_detail;
use App\Models\Client_basic_details;
use App\Models\Client_requirement;
use App\Models\CandidateBasicDetail;
use App\Models\CandidateDetail;
use App\Models\Skill;
use Carbon\Carbon;
use App\Models\Employee_attendance;
// use DB;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TestNotification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // exit();
        // return auth()->user();
        if (Auth::user()->id == 1) {
            return view('home');
        } else {
            $attendance = Employee_attendance::where('emp_code', Auth::user()->emp_code)
                // ->where('attendance_date', Carbon::now()->format('Y-m-d'))
                ->get();
            if (count($attendance) == 0) {
                return view('userHome');
            } else {
                // return "check";
                // return 2;
                return view('home');
            }
        }
    }

    public function getDashboardData(Request $request)
    {
        $user_id            = Auth::user()->id;
        $current_day_start  = Carbon::today();
        $active_employees   = Employee_personal_detail::where('is_active', 'Y')->count();
        $active_clients     = Client_basic_details::where('client_status', 'Active')->count();
        $active_requirements     = Client_requirement::where('requirement_status', 'Active')->count();
        $requirement_value       = Client_requirement::where('requirement_status', 'Active')->sum('total_position');
        $processed_candidates    = CandidateBasicDetail::count();

        $agent_data = array();
        $agent_data['active_employees']     = $active_employees;
        $agent_data['active_clients']       = $active_clients;
        $agent_data['active_requirements']  = $active_requirements;
        $agent_data['requirement_value']    = $requirement_value;
        $agent_data['processed_candidates']  = $processed_candidates;

        return response()->json([$agent_data]);
    }

    public function getDashboardInterviewData(Request $request)
    {
        $cdate              = Carbon::now()->format('Y-m-d H:i:s');
        $skills = Skill::all();

        $candidates_data = CandidateDetail::with('candidate_basic_detail')->where(DB::raw("CONCAT(`call_back_date`,' ',`call_back_time`)"), '<=', $cdate)->where('call_status', '<>', 'Closed')->orderBy(DB::raw("CONCAT(`call_back_date`,' ',`call_back_time`)"), 'DESC')->paginate(15);

        return view('interviewList', compact('candidates_data', 'skills'));
    }
    public function Logout()
    {
        Auth::logout();

        //return view('login')->with('success','User Logout');
        return redirect()->route('login');
    }


// for notification
    public function testnotify()
    {
        if(auth()->user()){

            $user = User::first();
            // print_r($user);exit();
            // $extraData = ['key1' => 'value1', 'key2' => 'value2'];
            // auth()->user()->notify(new TestNotification($user, $extraData));

            auth()->user()->notify(new TestNotification($user));
        }
    }

    public function testnotification()
    {
        return view('test_notification');
    }

    public function testmarkasread($id)
    {
        // dd($id);
        if($id){
           auth()->user()->unreadNotifications->where('id',$id)->markAsRead();
        }
        return back();
    }
}
