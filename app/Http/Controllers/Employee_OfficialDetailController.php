<?php

namespace App\Http\Controllers;

use App\Models\Client_address;
use App\Models\Client_basic_details;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee_official_detail;
use App\Models\Employee_personal_detail;
use App\Models\Employementmode;
use App\Models\EmployeeComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Employee_OfficialDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_official()
    {

        return view('employeeprofile.create_emp_official');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_emp_official(Request $request)
    {
        $validator = $request->validate([
            'employementmode_id' => 'required',
            'joining_date' => 'required',
            'department_id' => 'required',
            'designation_id' => 'required',
            'official_emailid' => 'required|email|unique:employee_official_details',

        ]);
        $official = Employee_official_detail::create([
            'emp_id' => $request->emp_id,
            'employementmode_id' => $request->employementmode_id,
            //  'client_id' => $request->client_id,
            // 'deployed_location' => $request->deployed_location,
            'joining_date' => $request->joining_date,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'official_emailid' => $request->official_emailid,
            'esic_no' => $request->esic_no,
            'pf_no' => $request->pf_no,
            'uan_no' => $request->uan_no,
            'relieving_date' => $request->relieving_date,
            'bgv' => $request->bgv,
            'comments' => $request->comments,
            'added_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $comment = new EmployeeComment();
        $comment->emp_id = $request->emp_id;
        $comment->emp_off_id = $official->id;
        $comment->comment = trim($request->comments);
        $comment->added_by = Auth::user()->id;
        $comment->save();

        $emp_id = $request->emp_id;
        $emp_name = $request->emp_name;
        if ($official) {
            if ($request->save_draft == "save_draft") {
                $employee = Employee_personal_detail::where('emp_code', NULL)->get();
                return view('employeeprofile.index_ipj', compact('employee'))->with('success', "Official Details inserted successfully");
            } else if ($request->save_draft == "save_draft_next") {
                return view('employeeprofile.create_emp_bank', compact('emp_id', 'emp_name'))->with('success', "Official Details inserted successfully");
            }
        } else {
            $employee = Employee_personal_detail::where('emp_code', NULL)->get();
            return view('employeeprofile.index_ipj', compact('employee'))->with('success', "Error in adding official details");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit_emp_official($id)
    {
        $emp_offcial = Employee_official_detail::where('emp_id', $id)->first();
        // print_r($emp_offcial->location);exit();
        $o_exists = 1;
        $emp_modes = Employementmode::all();
        $depts = Department::all();
        $desigs = Designation::all();
        $emp_personal = Employee_personal_detail::where('id', $id)->first();
        $all_comments = EmployeeComment::where('emp_id', $id)->paginate(10);
        $clients = Client_basic_details::all();
        $client_location = null;
        if ($emp_offcial->client_id != Null) {
            $client_location = Client_address::join('states', 'states.id', 'client_addresses.state_id')
                ->join('cities', 'cities.id', 'client_addresses.city_id')
                ->select('client_addresses.id', 'client_addresses.address', 'states.state', 'cities.city')
                ->where('client_addresses.client_id', $emp_offcial->client_id)
                ->where('client_addresses.id', $emp_offcial->location)  //added
                ->get();
            //    print_r(gettype($client_location));
            //    exit();
        }
        $emp_code = $emp_personal->emp_code;
        $emp_name = $emp_personal->subtitle . " " . $emp_personal->firstname . " " . $emp_personal->middlename . " " . $emp_personal->lastname;
        if ($emp_offcial == null) {
            $o_exists = 0;
        }
        // print_r(gettype($emp_offcial->location));exit(); 
        return view('employeeprofile.edit_emp_official', compact('client_location', 'desigs', 'depts', 'emp_offcial', 'id', 'emp_code', 'emp_modes', 'emp_name', 'o_exists', 'clients', 'all_comments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update_emp_official(Request $request)
    {
        $emp_id = $request->emp_id;
        $emp_code = $request->emp_code;
        $validator = $request->validate([
            'employementmode_id' => 'required',
            'joining_date' => 'required',
            'department_id' => 'required',
            'designation_id' => 'required',
            'official_emailid' => 'required|email',
            'comments' => 'required',

        ]);
        if ($request->edit_official == "update") {
            try {
                DB::beginTransaction();
                $updateDetails = [
                    'employementmode_id' => $request->employementmode_id,
                    'client_id' => $request->client_id,
                    'location' => $request->location,
                    'joining_date' => $request->joining_date,
                    'department_id' => $request->department_id,
                    'designation_id' => $request->designation_id,
                    'official_emailid' => $request->official_emailid,
                    'esic_no' => $request->esic_no,
                    'pf_no' => $request->pf_no,
                    'uan_no' => $request->uan_no,
                    'relieving_date' => $request->relieving_date,
                    'bgv' => $request->bgv,
                    'comments' => $request->comments,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => Carbon::now(),
                ];

                $i = Employee_official_detail::where('emp_id', $emp_id)
                    ->where('emp_code', $emp_code)
                    ->update($updateDetails);

                $emp_offcial = Employee_official_detail::where('emp_id', $emp_id)->where('emp_code', $emp_code)->first();

                $comment = new EmployeeComment();
                $comment->emp_id = $request->emp_id;
                $comment->emp_off_id = $emp_offcial->id;
                $comment->comment = trim($request->comments);
                $comment->added_by = Auth::user()->id;
                $comment->save();

                if ($i) {
                    $updateuseremail = ['email' => $request->official_emailid,];
                    $user = User::where('emp_code', $emp_code)->update($updateuseremail);
                    if ($user) {
                        DB::commit();
                        return Redirect()->back()->with('success', "Official Details updated successfully");
                    } else {
                        DB::rollBack();
                        return Redirect()->back()->with('error', "Official email updated unsuccessfully");
                    }
                    //  return Redirect()->back()->with('success', "Official Details updated successfully");
                } else {
                    DB::rollBack();
                    return Redirect()->back()->with('error', "Official Details updation error");
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return Redirect()->back()->with('error', "Official Details updation error.$e");
            }
        } else if ($request->add_official == "insert") {
            try {
                DB::beginTransaction();
                $official = Employee_official_detail::insert([
                    'emp_id' => $emp_id,
                    'emp_code' => $emp_code,
                    'employementmode_id' => $request->employementmode_id,
                    'client_id' => $request->client_id,
                    'location' => $request->location,
                    'joining_date' => $request->joining_date,
                    'department_id' => $request->department_id,
                    'designation_id' => $request->designation_id,
                    'official_emailid' => $request->official_emailid,
                    'esic_no' => $request->esic_no,
                    'pf_no' => $request->pf_no,
                    'uan_no' => $request->uan_no,
                    'relieving_date' => $request->relieving_date,
                    'bgv' => $request->bgv,
                    'comments' => $request->comments,
                    'added_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                if ($official) {
                    $updateuseremail = ['email' => $request->official_emailid,];
                    $user = User::where('emp_code', $emp_code)->update($updateuseremail);
                    if ($user) {
                        DB::commit();
                        return Redirect()->back()->with('success', "Official Details updated successfully");
                    } else {
                        DB::rollBack();
                        return Redirect()->back()->with('error', "Official email updated unsuccessfully");
                    }
                } else {
                    DB::rollBack();
                    return Redirect()->back()->with('error', "Official Details updation error");
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return Redirect()->back()->with('error', "Official Details updation error.$e");
            }
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
}
