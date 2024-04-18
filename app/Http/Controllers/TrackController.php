<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use App\Models\CandidateBasicDetail;
use App\Models\CandidateDetail;
use App\Models\City;
use App\Models\Client_requirement;
use App\Models\Designation;
use App\Models\Qualification;
use App\Models\Qualificationlevel;
use App\Models\Client_basic_details;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $employee = User::select('users.id', 'users.emp_code','users.name')
                ->where('users.is_active', 'Y')
                ->whereNotIn('users.id', [1])
                ->orderBy('id')
                ->get();
       
        return view('track.daily_track',compact('employee'));
    }
    
    public function client_track(){
         $clients =Client_basic_details::select('client_basic_details.*')
                ->where('client_basic_details.client_status', 'Active')
                ->orderBy('id')
                ->get();
        return view('track.client_track',compact('clients'));
    }
    
    
    public function getDailyTrack(Request $request){
        // print_r($request->all());exit();
        $emp_id=null;
        $from_date=null;
        $to_date=null;
        if ($request->ajax()) {
        $candidate = CandidateBasicDetail::leftjoin('candidate_details', 'candidate_details.candidate_id', '=', 'candidate_basic_details.id')
        ->leftjoin('users', 'users.id', 'candidate_details.added_by')
        ->leftjoin('client_basic_details', 'client_basic_details.id', 'candidate_details.client_id')
        ->leftjoin('client_requirements', 'client_requirements.id', 'candidate_details.requirement_id')
        ->leftjoin('designations', 'designations.id', 'client_requirements.position')
        ->select('candidate_basic_details.*', 'users.name', 'candidate_details.joining_date',
        'client_basic_details.company_name','candidate_details.requirement_status',
        'designations.designation','client_requirements.total_position');

        // print_r($request->emp_id);exit();
        $emp_id = $request->emp_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if($emp_id!=null){

        if($emp_id!="all"){
            $candidate = $candidate->where('candidate_details.added_by',$emp_id);
        }
        $candidate = $candidate->where('candidate_details.created_at','>=',$from_date)
        ->where('candidate_details.created_at','<=',$to_date);
        }
        $candidate = $candidate->orderBy('candidate_details.id', 'desc')->get();
        // print_r($candidate);exit();

        $each_invoice = new Collection();
        foreach ($candidate as $key => $value) {
            $each_invoice->push([
                'recruiter' => $value->name ?? "-",
                'date' => $value->created_at->format('Y-m-d H:i:s') ?? "-",
                'source' => $value->profile_source ?? "-",
                // 'company_name' => $value->company_name ?? "-",
                // 'position' => $value->designation ? $value->designation . "/" . ($value->total_position ?? "-") : "-",
                'candidate_name' => $value->candidate_name ?? "-",
                'phone_no' => $value->contact_no ?? "-",
                'status' => $value->requirement_status ?? "-",
                'current_salary' => $value->current_salary ?? "-",
                'expected_salary' => $value->expected_salary ?? "-",
                'notice_period' => $value->notice_period ?? "-",
                'comments' => $value->comments ?? "-",
            ]);
        }
        return DataTables::of($each_invoice)->addIndexColumn()->make(true);
    }
    }
    public function getDailyTrackClient(Request $request){
        // print_r($request->all());exit();
        $client_id=null;
        $requirement_id=null;
        $from_date=null;
        $to_date=null;
        if ($request->ajax()) {
        $candidate = CandidateBasicDetail::leftjoin('candidate_details', 'candidate_details.candidate_id', '=', 'candidate_basic_details.id')
        ->leftjoin('users', 'users.id', 'candidate_details.added_by')
        ->leftjoin('client_basic_details', 'client_basic_details.id', 'candidate_details.client_id')
        ->leftjoin('client_requirements', 'client_requirements.id', 'candidate_details.requirement_id')
        ->leftjoin('designations', 'designations.id', 'client_requirements.position')
        ->select('candidate_basic_details.*', 'users.name', 'candidate_details.joining_date',
        'client_basic_details.company_name','candidate_details.requirement_status',
        'designations.designation','client_requirements.total_position');

        // print_r($request->emp_id);exit();
        $client_id = $request->client_id;
        $requirement_id = $request->requirement_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if($client_id!=null){

        $candidate = $candidate->where('candidate_details.requirement_id',$requirement_id);
        if($client_id!="all"){
            $candidate = $candidate->where('candidate_details.client_id',$client_id);
        }
        $candidate = $candidate->where('candidate_details.created_at','>=',$from_date)
        ->where('candidate_details.created_at','<=',$to_date);
        }

        $candidate = $candidate->orderBy('candidate_details.id', 'desc')->get();
        // print_r($candidate);exit();

        $each_invoice = new Collection();
        foreach ($candidate as $key => $value) {
            $each_invoice->push([
                // 'recruiter' => $value->name ?? "-",
                'date' => $value->created_at->format('Y-m-d H:i:s') ?? "-",
                'source' => $value->profile_source ?? "-",
                'company_name' => $value->company_name ?? "-",
                'position' => $value->designation ? $value->designation . "/" . ($value->total_position ?? "-") : "-",
                'candidate_name' => $value->candidate_name ?? "-",
                'phone_no' => $value->contact_no ?? "-",
                'status' => $value->requirement_status ?? "-",
                // 'notice_period' => $value->notice_period ?? "-",
                // 'comments' => $value->comments ?? "-",
            ]);
        }
        return DataTables::of($each_invoice)->addIndexColumn()->make(true);
    }
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
