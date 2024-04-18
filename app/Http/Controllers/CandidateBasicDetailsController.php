<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidateStoreRequest;
use App\Http\Requests\CandidateUpdateRequest;
use App\Models\CandidateBasicDetail;
use App\Models\CandidateDetail;
use App\Models\City;
use App\Models\Client_requirement;
use App\Models\Designation;
use App\Models\Qualification;
use App\Models\Qualificationlevel;
use App\Models\Skill;
use App\Models\User;
use App\Models\CandidateComment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Validator;
use Illuminate\Support\Facades\URL;
use App\Models\Employee_personal_detail;
use App\Models\Client_basic_details;

use App\Exports\CandidateExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Notifications\NewCandidateNotification;


class CandidateBasicDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  $employee = DB::table('employee_personal_details')
        //         ->select('employee_personal_details.id', 'employee_personal_details.emp_code','employee_personal_details.subtitle', 'employee_personal_details.firstname','employee_personal_details.lastname','employee_personal_details.middlename')
        //         ->where('employee_personal_details.is_active', 'Y')
        //         ->orderBy('employee_personal_details.emp_code')
        //         ->get();

        $employee = Employee_personal_detail::select('employee_personal_details.id', 'employee_personal_details.emp_code', 'employee_personal_details.subtitle', 'employee_personal_details.firstname', 'employee_personal_details.lastname', 'employee_personal_details.middlename', 'users.*')
            ->join('users', 'employee_personal_details.emp_code', '=', 'users.emp_code')
            // ->leftJoin('users', function($join) {
            //     $join->on('employee_personal_details.emp_code', '=', 'users.emp_code')
            //         ->orWhere('users.name', '=', 'Super Admin');
            // })
            ->where('users.is_active', 'Y')
            ->orderBy('employee_personal_details.emp_code')
            ->get();

        $clients = Client_basic_details::select('client_basic_details.*')
            ->where('client_basic_details.client_status', 'Active')
            ->orderBy('id')
            ->get();
        // print_r($employee);exit();
        return view('candidate.index', compact('employee', 'clients'));
    }

    public function autocompleteCandidate(Request $request)
    {
        $query = $request->get('query');
        $filterResult = CandidateBasicDetail::select('candidate_name')->where('candidate_name', 'LIKE', '%' . $query . '%')->pluck('candidate_name');
        return response()->json($filterResult);
    }

    public function autocompletelocation(Request $request)
    {
        $query = $request->get('query');
        $filterResult = City::select('city')->where('city', 'LIKE', '%' . $query . '%')->pluck('city');
        return response()->json($filterResult);
    }

    public function getCandidates(Request $request)
    {

        if ($request->input('export')) {
            // Export the data
            return $this->exportcandidate($request);
        }


        $emp_id = null;
        $call_status = null;
        $req_status = null;
        $from_date = null;
        $to_date = null;
        // print_r($request->all());exit();
        if ($request->ajax()) {
            // $candidate = DB::table('candidate_basic_details')
            //     ->join('users', 'users.id', 'candidate_basic_details.added_by','candidate_details.candidate_id')
            //     ->join('candidate_details', 'candidate_details.candidate_id','candidate_basic_details.id')
            //     ->select('candidate_basic_details.*', 'users.name','candidate_details.invoice_generation_limit','candidate_details.joining_date')
            //     ->orderBy('id')
            //     ->get();
            $candidate = CandidateBasicDetail::join('users', 'users.id', 'candidate_basic_details.added_by')
                ->leftjoin('candidate_details', 'candidate_details.candidate_id', '=', 'candidate_basic_details.id')
                ->select(
                    'candidate_basic_details.*',
                    'users.name',
                    'candidate_details.invoice_generation_limit',
                    'candidate_details.joining_date'
                );

            // added for search filter
            $emp_id = $request->emp_id;
            $call_status = $request->call_status;
            $req_status = $request->req_status;
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            // if($emp_id!=null){
            if ($request->search_filter == "search") {

                $candidate = $candidate->where('candidate_details.call_status', $call_status);
                if ($emp_id != "all") {
                    $candidate = $candidate->where('candidate_details.added_by', $emp_id);
                }
                // $candidate = $candidate->where('candidate_details.requirement_status', $req_status)
                $candidate = $candidate->where('candidate_basic_details.status', $req_status)
                    ->where('candidate_details.created_at', '>=', $from_date)
                    ->where('candidate_details.created_at', '<=', $to_date);
            } elseif ($request->search_filter == "search2") {
                $candidate = $candidate->where('candidate_details.requirement_id', $call_status);
                if ($emp_id != "all") {
                    $candidate = $candidate->where('candidate_details.client_id', $emp_id);
                }
                $candidate = $candidate->where('candidate_details.requirement_status', $req_status)
                    ->where('candidate_details.created_at', '>=', $from_date)
                    ->where('candidate_details.created_at', '<=', $to_date);
            } else {
                // default 
            }
            $candidate = $candidate->orderBy('id', 'desc')->get();

            $each_candidate = new Collection();
            foreach ($candidate as $key => $value) {
                $user = User::find(Auth::user()->id);
                $action = null;
                $processed = null;
                $skills = null;
                if ($value->skills != null) {
                    $sk = json_decode($value->skills);
                    if ($sk != null) {
                        for ($i = 0; $i < count($sk); $i++) {
                            $getskill = Skill::find($sk[$i])->skill;
                            // print_r($getskill);
                            if ($skills != null) {
                                $skills = $skills . "," . $getskill;
                            } else {
                                $skills  = $getskill;
                            }
                        }
                    }
                }
                $action = '<a href="' . route('show.candidate', [$value->id]) . '" title="show"><i class="fa fa-eye" style="color: black"></i></a>';
                //dd($value->invoice_generation_limit);
                if ($value->invoice_generation_limit != NULL && $value->joining_date != NULL && is_numeric($value->invoice_generation_limit)) {
                    //$tenth_day = Carbon::now()->subDays(10)->format('Y-m-d');
                    $tommorow = \Carbon\Carbon::now()->addDay(1)->format('Y-m-d');
                    $joining_date = $value->joining_date;
                    $to = \Carbon\Carbon::createFromFormat('Y-m-d', $joining_date);
                    $from = \Carbon\Carbon::createFromFormat('Y-m-d', $tommorow);

                    $ageOnNetwork = $to->diffInDays($from);
                    if ($ageOnNetwork >= $value->invoice_generation_limit) {
                        $processed = '<a href="' . route('edit_detail.candidate', [$value->id, $value->candidate_name]) . '" title="Click here to view the positions the candidate is processed for."><i class="si si-docs"></i></a> &nbsp;&nbsp;<a href="' . route('create.invoice') . '" title="Click here to generate invoice."><i class="fas fa-download"></i></a> ';
                    } else {
                        $processed = '<a href="' . route('edit_detail.candidate', [$value->id, $value->candidate_name]) . '" title="Click here to view the positions the candidate is processed for."><i class="si si-docs"></i></a> ';
                    }
                } else {
                    $processed = '<a href="' . route('edit_detail.candidate', [$value->id, $value->candidate_name]) . '" title="Click here to view the positions the candidate is processed for."><i class="si si-docs"></i></a> ';
                }
                //$processed = '<a href="' . route('edit_detail.candidate', [$value->id, $value->candidate_name]) . '" title="Click here to view the positions the candidate is processed for."><i class="si si-docs"></i></a> ';
                if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Candidate')) {
                    $action = $action . ' <a href="' . route('edit.candidate', [$value->id]) . '" title="edit"><i class="fa fa-edit" style="color: green"></i></a>';
                }

                $candidate_name = '<a href="' . asset('storage/' . $value->candidate_resume) . '" target="_blank" title="Click here to view resume">' . $value->candidate_name . '</a>';

                $each_candidate->push([

                    'candidate_name' => $candidate_name,
                    'mobile' => $value->contact_no,
                    'whatsapp' => $value->whatsapp_no,
                    'email' => $value->candidate_email,
                    'skills' => $skills,
                    'candidate_status' => $value->status,
                    'profile' => $processed,
                    'added_by' => $value->name,
                    // 'added_on' => $value->created_at,
                    'action' => $action
                ]);
            }
            return DataTables::of($each_candidate)->addIndexColumn()->rawColumns(['candidate_name', 'profile', 'action'])->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $publicPath = public_path();
        // print_r($publicPath);exit();

        $designation = Designation::all();
        $qlevel = Qualificationlevel::all();
        $skills = Skill::all();
        return view('candidate.create', compact('qlevel', 'designation', 'skills'));
        /*return view('candidate.2');*/
    }
    public function bulk_upload()
    {

        $clients = Client_basic_details::select('client_basic_details.*')
            ->where('client_basic_details.client_status', 'Active')
            ->orderBy('id')
            ->get();

        return view('candidate.bulk_upload', compact('clients'));
    }
    public function upload(Request $request)
    {
        // print_r($request->all());exit();    
        $file = $_FILES['bulk']['tmp_name'];
        $handle = fopen($file, "r");
        $c = 0; //
        $x = 0; //
        $y = 0; //
        $z = 0; //
        while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            // print_r($filesop);echo "<br>";
            if ($c <> 0) {    // SKIP THE FIRST ROW 
                if ($filesop[0] != '' || $filesop[2] != '' || $filesop[3] != '') {

                    $candidate = array();
                    $candidate['candidate_name'] = $filesop[0];
                    $candidate['gender'] = $filesop[1];
                    $candidate['contact_no'] = $filesop[2];
                    $candidate['whatsapp_no'] = $filesop[2];
                    $candidate['candidate_email'] = $filesop[3];
                    $candidate['current_location'] = $filesop[5];
                    $candidate['status'] = $filesop[7];
                    $candidate['created_at'] = Carbon::now();

                    $requirement = array();
                    $requirement['client_id'] = $request->client_id;
                    $requirement['requirement_id'] = $request->requirement_id;
                    $requirement['call_back_status'] = $filesop[6];
                    $requirement['created_at'] = Carbon::now();

                    // to get the added by value from the excel sheet
                    $check_emp = User::where('emp_code', $filesop[8])->where('is_active', 'Y')->exists();
                    // print_r($check_emp);exit();
                    if ($check_emp) {
                        $get_id = $check_emp;
                        // $get_id = $check_emp->id;
                        $candidate['added_by'] = $requirement['added_by'] = $get_id;
                    } else {
                        $get_id = Auth::user()->id;
                        $candidate['added_by'] = $requirement['added_by'] = $get_id;
                    }

                    $i = 0;
                    // DB::beginTransaction();
                    $candidate_exists = CandidateBasicDetail::where('candidate_email', $filesop[3])->count();

                    if ($candidate_exists == 0) {  //if candidate already not exists
                        $id = CandidateBasicDetail::insertGetId($candidate);
                        $requirement['candidate_id'] = $id;
                        $i = CandidateDetail::insertGetId($requirement);
                        //Add to requirement       
                        $x++;
                    } else {
                        $candidate_exists = CandidateBasicDetail::where('candidate_email', $filesop[3])->first();
                        $id = $candidate_exists->id;
                        // to check detail replicating
                        $candidate_detail_exists = CandidateDetail::where('candidate_id', $id)
                            ->where('requirement_id', $request->requirement_id)
                            ->exists();
                        if ($candidate_detail_exists) {
                            $z++;
                        } else {
                            //Add to requirement
                            $requirement['candidate_id'] = $id;
                            // $i =  DB::table('candidate_details')->insertGetId($requirement);
                            $i =  CandidateDetail::insertGetId($requirement);
                            $y++;
                        }
                    }
                } else {
                    $z++;
                }
            }
            $c = $c + 1;
        }
        // exit(); 
        if ($i) {
            // DB::commit();
            // candidate/all
            // return view('candidate.index')->with('success','Candidate uploaded successfully');
            return redirect()->back()->with([
                'success' => 'Candidate uploaded successfully',
                'msg' => "$x records inserted $y records updated  $z records failed to insert/update "
            ]);
        } else {
            // DB::rollback();
            // return view('candidate.index')->with('error','Some error occured.Check your input file');
            return redirect()->back()->with([
                'error' => 'Some error occured!Check your input file',
                'msg' => "$z records failed to insert/update "
            ]);
        }
    }
    public function fetchClientRequirement(Request $request)
    {
        $data['requirement'] = Client_requirement::join('client_addresses', 'client_addresses.id', 'client_requirements.location')
            ->join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->join('designations', 'designations.id', 'client_requirements.position')
            ->select(
                'client_requirements.id as id',
                'client_addresses.address as address',
                'states.state as state',
                'cities.city as city',
                'designations.designation',
                'client_requirements.total_position'
            )
            ->where('client_requirements.requirement_status', 'Active')
            ->where('client_requirements.deleted_at', NULL)
            ->where('client_requirements.client_id', $request->client_id)
            ->orderBy('client_requirements.id', 'desc')
            ->get();
        // print_r($data);exit();

        return response()->json($data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CandidateStoreRequest $request)
    {
        // print_r($request->all());exit();
        // $validator = $request->validate([
        //     'candidate_resume' => 'required|mimes:pdf,doc,docx',
        //     'candidate_name'=>'required',
        //     'gender'=>'required',
        //     'contact_no' => 'required|unique:candidate_basic_details|max:10',
        //     'whatsapp_no' => 'required|unique:candidate_basic_details|max:10',
        //     'candidate_email'=> 'required|unique:candidate_basic_details|max:255',
        //     'current_company'=>'required',
        //     'preferred_location'=>'required',
        //     'employement_mode'=>'required',
        //     'pf_status'=>'required',
        //     'passport'=>'required',
        //     'preferred_shift'=>'required',
        //     'quali_level_id'=>'required',
        //     'quali_id'=>'required',
        //     'communication'=>'required',
        //     'skills'=>'required',
        //     'status'=>'required',
        //     'profile_source'=>'required',
        // ]);

        // $name = $request->file('candidate_resume')->getClientOriginalName();
        // $name = Carbon::now()->format('Y-m-d_H-i-s') . '_' . $name; // Prefix with current date and time
        // $uploadPath = 'resumes/' . $name;

        // Store the uploaded file with the specified name in the public/resumes directory
        // $path = $request->file('candidate_resume')->storeAs('public', $uploadPath);
        // $path = $request->file('candidate_resume')->store($uploadPath);


        // $name = $request->file('candidate_resume')->getClientOriginalName();
        // $name = Carbon::now()->format('Y-m-d_H-i-s') . '_' . $name;
        // $upload_path = 'resumes/'.$name;
        // $path = $request->file('candidate_resume')->move(base_path().'/public/resumes',$name);


        $name = $request->file('candidate_resume')->getClientOriginalName();
        $name = Carbon::now()->format('Y-m-d_H-i-s') . '_' . $name;
        $folderpath = 'resumes';
        $path = $request->file('candidate_resume')->storeAs('public/' . $folderpath, $name);
        $upload_path = $folderpath . '/' . $name;



        if ($path) {
            $input = $request->except(['_token']);
            $input['skills'] = json_encode($request->skills);
            $input['added_by'] = Auth::user()->id;
            $input['created_at'] = Carbon::now();
            $id = CandidateBasicDetail::insertGetId($input);

            $comment = new CandidateComment();
            $comment->candidate_id = $id;
            $comment->comments = trim($request->comments);
            $comment->added_by = Auth::user()->id;
            $comment->save();

            $name = $request->candidate_name;
            if ($id > 0) {

                // candidate notification
                $users = $this->getUsersWithPermission('Candidate Notification');
                if ($users) {
                    $get_candidate = CandidateBasicDetail::find($id);
                    // print_r($get_client);exit();
                    // return $users;
                    foreach ($users as $user) {
                        $notification = new NewCandidateNotification($get_candidate);
                        $user->notify($notification);
                    }
                }



                $j = CandidateBasicDetail::where('id', $id)->update(['candidate_resume' => $upload_path]);
                if ($j) {
                    return redirect()->route('edit_detail.candidate', [$id, $name])->with('success', 'Added Successfully');
                } else {
                    return redirect()->back()->with('error', 'Resume uploaded unuccessfully');
                }
            } else {
                return redirect()->back()->with('error', 'Added unuccessfully');
            }
        } else {
            return redirect()->back()->with('error', 'Resume uploading failed');
        }
    }
    public function edit_detail($id)
    {
        $requirement = Client_requirement::join('users', 'users.id', 'client_requirements.added_by')
            ->join('client_basic_details', 'client_basic_details.id', 'client_requirements.client_id')
            ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
            ->join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->join('designations', 'designations.id', 'client_requirements.position')
            ->select(
                'client_requirements.id as id',
                'client_basic_details.client_code',
                'client_basic_details.company_name',
                'client_addresses.address as address',
                'states.state as state',
                'cities.city as city',
                'designations.designation',
                'client_requirements.total_position'
            )
            ->where('client_requirements.requirement_status', 'Active')
            ->where('client_requirements.deleted_at', NULL)
            ->orderBy('client_requirements.id')
            ->get();
        $candidate = CandidateBasicDetail::find($id);
        $name = $candidate->candidate_name;

        $candidateDetail = CandidateDetail::join('users', 'users.id', 'candidate_details.added_by')
            ->join('client_basic_details', 'client_basic_details.id', 'candidate_details.client_id')
            ->join('client_requirements', 'client_requirements.id', 'candidate_details.requirement_id')
            ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
            ->join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->join('designations', 'designations.id', 'client_requirements.position')
            ->select(
                'client_basic_details.client_code',
                'client_basic_details.company_name',
                'designations.designation',
                'states.state',
                'cities.city',
                'client_addresses.address',
                'candidate_details.call_status',
                'candidate_details.comments',
                'users.name',
                'candidate_details.added_by',
                'candidate_details.joining_date',
                'candidate_details.invoice_generation_limit'
            )
            ->where('candidate_id', $id)->get();

        return view('candidate.edit_detail', compact('requirement', 'id', 'name', 'candidateDetail'));
    }
    public function fetchClient(Request $request)
    {
        $data['client'] = Client_requirement::join('client_basic_details', 'client_basic_details.id', 'client_requirements.client_id')
            ->select('client_basic_details.client_code', 'client_basic_details.company_name', 'client_basic_details.id')
            ->where('client_requirements.requirement_status', 'Active')
            ->where('client_requirements.deleted_at', NULL)
            ->where('client_requirements.id', $request->requirement_id)
            ->get();
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidate = CandidateBasicDetail::find($id);
        $candidateDetail = CandidateDetail::where('candidate_id', $id)->where('deleted_by', NULL)->get();

        // $designation= Designation::find($candidate->designation)->designation;
        $designationModel = Designation::find($candidate->designation);
        $designation = $designationModel ? $designationModel->designation : null;

        $qlevelModel = Qualificationlevel::find($candidate->quali_level_id);
        $qlevel = $qlevelModel ? $qlevelModel->qualificationlevel : null;

        $qualificationModel = Qualification::find($candidate->quali_id);
        $qualification = $qualificationModel ? $qualificationModel->qualification : null;

        $all_comments = CandidateComment::where('candidate_id', $id)->get();
        $skills = Skill::all();

        // print_r($designation);exit();
        return view('candidate.show', compact('candidate', 'id', 'designation', 'qlevel', 'qualification', 'candidateDetail', 'all_comments', 'skills'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidate = CandidateBasicDetail::find($id);
        $designation = Designation::all();
        $qlevel = Qualificationlevel::all();
        $qualification = Qualification::where('qualificationlevel_id', $candidate->quali_level_id)->get();
        $skills = Skill::all();
        $all_comments = CandidateComment::where('candidate_id', $id)->paginate(10);
        return view('candidate.edit', compact('candidate', 'id', 'designation', 'qlevel', 'qualification', 'skills', 'all_comments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->save_comments == "submit2") {
            // print_r("submit2");exit();
            $comment = new CandidateComment();
            $comment->candidate_id = $id;
            $comment->comments = trim($request->comments);
            $comment->added_by = Auth::user()->id;
            $comment->save();
            return redirect()->route('edit.candidate', ['id' => $id])->with('success', 'Comments Added Successfully');
        }
        // print_r("submit1");exit();

        $input = $request->except(['_token', '_method', 'candidate_resume', 'save_draft_next', 'save_comments']);
        $input['updated_by'] = Auth::user()->id;
        $input['updated_at'] = Carbon::now();
        $i = CandidateBasicDetail::where('id', $id)->update($input);

        $name = $request->candidate_name;

        if ($i > 0) {
            // echo "hi";
            //  echo "file";
            if ($request->file('candidate_resume') != null) {
                //     $filename = $request->file('candidate_resume')->getClientOriginalName();
                //     $filename =Carbon::now()."_". $filename;
                //    // echo $filename;
                //     $upload_path = 'resumes/'.$filename;
                // $path = $request->file('candidate_resume')->move(base_path().'/public/resumes',$filename);  //already exists
                // $path = $request->file('candidate_resume')->store($upload_path);

                $filename = $request->file('candidate_resume')->getClientOriginalName();
                $filename = Carbon::now()->format('Y-m-d_H-i-s') . '_' . $filename;
                $folderpath = 'resumes';
                $path = $request->file('candidate_resume')->storeAs('public/' . $folderpath, $filename);
                $upload_path = $folderpath . '/' . $filename;

                $j = CandidateBasicDetail::where('id', $id)->update(['candidate_resume' => $upload_path]);
            }
            //echo "hello";
            //exit();
            return redirect()->route('edit_detail.candidate', [$id, $name])->with('success', 'Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Added unuccessfully');
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

    // export client starts
    public function exportcandidate(Request $request)
    {
        // print_r($request->all());exit();

        if ($request->has('export')) {
            $candidates = CandidateBasicDetail::join('users', 'users.id', 'candidate_basic_details.added_by')
                ->leftjoin('candidate_details', 'candidate_details.candidate_id', '=', 'candidate_basic_details.id')
                ->select(
                    'candidate_basic_details.*',
                    'users.name',
                    'candidate_details.invoice_generation_limit',
                    'candidate_details.joining_date',
                    'candidate_details.candidate_id',
                );

            // added for search filter
            $emp_id = $request->emp_id;
            $call_status = $request->call_status;
            $req_status = $request->req_status;
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            if ($request->has('search_filter') && $request->search_filter == "search") {

                $candidates = $candidates->where('candidate_details.call_status', $call_status);
                if ($emp_id != "all") {
                    $candidates = $candidates->where('candidate_details.added_by', $emp_id);
                }
                // $candidate = $candidate->where('candidate_details.requirement_status', $req_status)
                $candidates = $candidates->where('candidate_basic_details.status', $req_status)
                    ->where('candidate_details.created_at', '>=', $from_date)
                    ->where('candidate_details.created_at', '<=', $to_date);
                $filename = 'candidate_recruiter_wise.xlsx';
            } elseif ($request->has('search_filter') && $request->search_filter == "search2") {
                $candidates = $candidates->where('candidate_details.requirement_id', $call_status);
                if ($emp_id != "all") {
                    $candidates = $candidates->where('candidate_details.client_id', $emp_id);
                }
                $candidates = $candidates->where('candidate_details.requirement_status', $req_status)
                    ->where('candidate_details.created_at', '>=', $from_date)
                    ->where('candidate_details.created_at', '<=', $to_date);
                $filename = 'candidate_client_wise.xlsx';
            } else {
                $filename = 'candidate.xlsx';
            }
            // $candidates = $candidates->orderBy('id', 'desc')->get();

            // print_r($candidates);exit();

            $response = Excel::download(new CandidateExport($candidates->orderBy('id', 'DESC')->get()), $filename);

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
