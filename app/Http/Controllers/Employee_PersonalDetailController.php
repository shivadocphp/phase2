<?php

namespace App\Http\Controllers;

use App\Models\BloodGroups;
use App\Models\City;
use App\Models\Client_basic_details;
use App\Models\Countries;
use App\Models\Country;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Districts;
use App\Models\Emp_code;
use App\Models\Employee_attendance;
use App\Models\Employee_bank_detail;
use App\Models\Employee_official_detail;
use App\Models\Employee_personal_detail;
use App\Models\Employee_pip_detail;
use App\Models\Employee_salary_detail;
use App\Models\Employee_team;
use App\Models\Employementmode;
use App\Models\Qualification;
use App\Models\Qualificationlevel;
use App\Models\QualificationLevels;
use App\Models\State;
use App\Models\States;
use App\Models\subtitle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables;

use Illuminate\Support\Facades\Log;


use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Mail;

use App\Notifications\NewEmployeeNotification;

class Employee_PersonalDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employee = Employee_personal_detail::join('employee_official_details', 'employee_official_details.emp_id', 'employee_personal_details.id')
            ->join('departments', 'departments.id', 'employee_official_details.department_id')
            ->join('designations', 'designations.id', 'employee_official_details.designation_id')
            ->join('users', 'users.emp_code', 'employee_personal_details.emp_code')
            ->select('employee_personal_details.*', 'departments.department', 'designations.designation')
            ->where('employee_personal_details.is_active', 'Y')
            ->get();
        // print_r($employee);exit();
        //echo $employee;
        //exit();
        return view('employeeprofile.index');
        //
    }

    public function inactive()
    {
        $employee = Employee_personal_detail::leftJoin('employee_official_details', 'employee_official_details.emp_id', 'employee_personal_details.id')
            ->leftJoin('departments', 'departments.id', 'employee_official_details.department_id')
            ->leftJoin('designations', 'designations.id', 'employee_official_details.designation_id')
            // ->join('users','users.emp_code','employee_personal_details.emp_code')
            ->select('employee_personal_details.*', 'departments.department', 'designations.designation', 'employee_official_details.joining_date')
            ->where('employee_personal_details.is_active', 'N')
            ->orderBy('emp_code')
            ->get();

        return view('employeeprofile.index_inactive', compact('employee'));
    }

    public function ipj()
    {
        $employee = Employee_personal_detail::where('emp_code', NULL)
            ->latest()->get();
        return view('employeeprofile.index_ipj', compact('employee'));
    }

    public function getActiveEmployees(Request $request)
    {
        if ($request->ajax()) {
            $employee = Employee_personal_detail::leftJoin('employee_official_details', 'employee_official_details.emp_id', 'employee_personal_details.id')
                ->leftJoin('departments', 'departments.id', 'employee_official_details.department_id')
                ->leftJoin('designations', 'designations.id', 'employee_official_details.designation_id')
                ->join('users', 'users.emp_code', 'employee_personal_details.emp_code')
                ->select('employee_personal_details.*', 'departments.department', 'designations.designation', 'employee_official_details.joining_date')
                ->where('employee_personal_details.is_active', 'Y')
                ->orderBy('employee_personal_details.emp_code')
                ->get();


            $each_employee = new Collection();
            foreach ($employee as $key => $value) {
                $tenure = '-';
                if ($value->joining_date != null) {
                    $date1 = strtotime(date('Y-m-d'));
                    $date2 = strtotime($value->joining_date);
                    $diff = abs($date2 - $date1);
                    $years = floor($diff / (365 * 60 * 60 * 24));
                    $months = floor(($diff - $years * 365 * 60 * 60 * 24)
                        / (30 * 60 * 60 * 24));
                    $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
                        $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                    $tenure =  $years . "Years " . $months . "Months " . $days . "Days";
                }
                $user = User::find(Auth::user()->id);
                $action = null;
                if (Auth::user()->id == 1  || $user->hasPermissionTo('Edit Employees')) {
                    $action =  '<a href="' . route('show.employee', [$value->id]) . '" title="View"><i class="fa fa-eye" style="color: black"></i></a>
                                        <a href="' . route('edit.employee_personal', [$value->id]) . '" title="Edit"><i class="fa fa-edit" style="color:green"></i></a>
                                         <a href="' . route('employee.manager_employee', [$value->id]) . '" title="Performance Assessment"><img src="../public/backend/assets/images/brand/939354.png" width="13%" height="13%"></a>
                                        <a href="' . route('edit.employee_status_disable', [$value->id, $value->emp_code]) . '" title="Disable" ><i class="fa fa-ban" style="color: red"></i></a>';
                } else {
                    $action =  '<a href="' . route('show.employee', [$value->id]) . '" class="btn btn-sm btn-info">View</a>';
                }
                if ($value->is_active == 'Y') {
                    $each_employee->push([

                        'emp_code' => $value->emp_code,
                        'name' => $value->subtitle . "" . $value->firstname . " " . $value->middlename . " " . $value->lastname,
                        'department' => $value->department . " - " . $value->designation,
                        'profile' => '',
                        'tenure' => $tenure,
                        'action' => $action
                    ]);
                }
            }
            return DataTables::of($each_employee)->addIndexColumn()->rawColumns(['action'])->make(true);
        }
    }

    public function getInactiveEmployees(Request $request)
    {
        if ($request->ajax()) {
            $employee = Employee_personal_detail::leftjoin('employee_official_details', 'employee_official_details.emp_id', 'employee_personal_details.id')
                ->leftjoin('departments', 'departments.id', 'employee_official_details.department_id')
                ->leftjoin('designations', 'designations.id', 'employee_official_details.designation_id')
                ->join('users', 'users.emp_code', 'employee_personal_details.emp_code')
                ->select('employee_personal_details.*', 'departments.department', 'designations.designation', 'employee_official_details.joining_date', 'employee_official_details.relieving_date')
                ->where('users.is_active', 'N')
                ->where('employee_personal_details.is_active', 'N')
                ->get();

            $each_employee = new Collection();
            $user = User::find(Auth::user()->id);
            $action = null;

            foreach ($employee as $key => $value) {
                if (Auth::user()->id == 1  || $user->hasPermissionTo('Edit Employees')) {
                    $action = '<center><a href="' . route('edit.employee_status_enable', [$value->id, $value->emp_code]) . '" title="activate"><i class="fa fa-check-circle" style="color: green"></i></a></center>';
                }
                $tenure = '-';
                if ($value->joining_date != null) {
                    $date1 = strtotime(date('Y-m-d'));
                    $date2 = strtotime($value->joining_date);
                    $diff = abs($date2 - $date1);
                    $years = floor($diff / (365 * 60 * 60 * 24));
                    $months = floor(($diff - $years * 365 * 60 * 60 * 24)
                        / (30 * 60 * 60 * 24));
                    $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
                        $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                    $tenure =  $years . "Years " . $months . "Months " . $days . "Days";
                }
                $each_employee->push([

                    'emp_code' => $value->emp_code,
                    'name' => $value->subtitle . "" . $value->firstname . " " . $value->middlename . " " . $value->lastname,
                    'department' => $value->department . " - " . $value->designation,
                    'profile' => '',
                    'tenure' => $tenure,
                    'action' => $action,
                ]);
            }
            return DataTables::of($each_employee)->addIndexColumn()

                ->rawColumns(['action'])->make(true);
        }
    }

    public function getIPJEmployees(Request $request)
    {
        if ($request->ajax()) {
            $employee = Employee_personal_detail::
                //    ->join('users','users.emp_code','employee_personal_details.emp_code')
                // ->select('employee_personal_details.*','users.name')
                where('employee_personal_details.emp_code', NULL)
                ->latest()->get();
            $each_employee = new Collection();
            $user = User::find(Auth::user()->id);
            $action = null;

            foreach ($employee as $key => $value) {
                if (Auth::user()->id == 1  || $user->hasPermissionTo('Edit Employees')) {
                    $action = '<a href="' . route('edit.employee_code', [$value->id]) . '" class="btn btn-sm  btn-primary" style="color: orangered">Generate Employee Code</a>';
                }

                $each_employee->push([
                    'name' => $value->subtitle . "" . $value->firstname . " " . $value->middlename . " " . $value->lastname,
                    'action' => $action,

                ]);
            }
        }
        return DataTables::of($each_employee)->addIndexColumn()->rawColumns(['action'])->make(true);
    }

    public function create()
    {

        // $get_employee = Employee_personal_detail::find(10);
        // print_r($get_employee->empaddedBy->name);exit();
        //$cities = City::all();
        $qlevel = Qualificationlevel::all();
        $states = State::all();
        $countries = Country::all();
        return view('employeeprofile.create', compact('qlevel', 'states', 'countries'));
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'subtitle' => 'required',
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'dob' => 'required',
            'personal_emailID' => 'required|email|unique:employee_personal_details',
            'mobile1' => 'required',
            'diff_abled' => 'required',
            'quali_level_id' => 'required',
            'quali_id' => 'required',
            'p_address1' => 'required',
            'p_city_id' => 'required',
            'p_state_id' => 'required',
            'p_country_id' => 'required',
            'p_address_pincode' => 'required',
            'blood_group' => 'required',

            'c_address1' => 'required',
            'c_state_id' => 'required',
            'c_city_id' => 'required',
            'c_country_id' => 'required',
            'c_address_pincode' => 'required',
            'shift_id' => 'required',
        ]);


        $c_address = null;
        $c_city_id = null;
        $c_state_id = null;
        $c_country_id = null;
        $c_address_pincode = null;
        $same_address = $request->same_address;
        if ($same_address == "same") {
            $c_address = $request->p_address1;
            $c_city_id = $request->p_city_id;
            $c_state_id = $request->p_state_id;
            $c_country_id = $request->p_country_id;
            $c_address_pincode = $request->p_address_pincode;
        } else {
            $c_address = $request->c_address1;
            $c_city_id = $request->c_city_id;
            $c_state_id = $request->c_state_id;
            $c_country_id = $request->c_country_id;
            $c_address_pincode = $request->c_address_pincode;
        }

        $insert = Employee_personal_detail::insertGetId([
            'subtitle' => $request->subtitle,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'personal_emailID' => $request->personal_emailID,
            'landline' => $request->landline,
            'mobile1' => $request->mobile1,
            'mobile2' => $request->mobile2,
            'diff_abled' => $request->diff_abled,
            'aadhaar_no' => $request->aadhaar_no,
            'pan_no' => $request->pan_no,
            'dl_no' => $request->dl_no,
            'dl_expiry_date' => $request->dl_expiry_date,
            'quali_level_id' => $request->quali_level_id,
            'quali_id' => $request->quali_id,
            'p_address1' => $request->p_address1,
            'p_city_id' => $request->p_city_id,
            'p_state_id' => $request->p_state_id,
            'p_country_id' => $request->p_country_id,
            'p_address_pincode' => $request->p_address_pincode,
            'c_address1' => $c_address,
            'c_city_id' => $c_city_id,
            'c_state_id' => $c_state_id,
            'c_country_id' => $c_country_id,
            'c_address_pincode' => $c_address_pincode,
            'blood_group' => $request->blood_group,
            'guardian_type' => $request->guardian_type,
            'guardian_name' => $request->guardian_name,
            'guardian_mobile' => $request->guardian_mobile,
            'is_active' => 'N',
            'added_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'shift_id' => $request->shift_id,
        ]);
        if ($request->save_draft == "save_draft") {
            if ($insert) {
                // print_r($insert);exit();
                $get_employee = Employee_personal_detail::find($insert);
                $email = $get_employee->personal_emailID;

                $insert2 = Employee_official_detail::insert([
                    'emp_id' => $insert,
                    'official_emailid' => $email,
                    'added_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                $user = User::first();
                $employee = Employee_personal_detail::where('emp_code', NULL)->get();

                // to notify the employees with the permission notification
                $users = $this->getUsersWithPermission('Employee Notification');
                // return $users;
                if ($users) {
                    foreach ($users as $user) {
                        // auth()->user()->notify(new NewEmployeeNotification($user));

                        $notification = new NewEmployeeNotification($get_employee);
                        $user->notify($notification);
                    }
                }


                return redirect()->route('ipj.employee', compact('employee'));
                //return view('employeeprofile.index_ipj', compact('employee'))->with('success', "Personal Details inserted successfully");
            } else {
                $employee = Employee_personal_detail::where('emp_code', NULL)->get();
                return redirect()->route('ipj.employee', compact('employee'));
                //return view('employeeprofile.index_ipj', compact('employee'))->with('error', "Personal Details inserted unsuccessfully");
            }
        } else if ($request->save_draft == "save_draft_next") {
            $emp_modes = Employementmode::all();
            $emp = Employee_personal_detail::where('personal_emailID', $request->personal_emailID)->get();
            $emp_id = 0;
            $emp_name = null;
            foreach ($emp as $e) {
                $emp_id = $e->id;
                $emp_name = $e->subtitle . " " . $e->firstname . " " . $e->middlename . " " . $e->lastname;
            }

            $depts = Department::all();
            $desigs = Designation::all();
            $clients = Client_basic_details::all();
            // $client = null;
            return view('employeeprofile.create_emp_official', compact('clients', 'emp_modes', 'emp_name', 'emp_id', 'depts', 'desigs'))->with('success', "Personal Details inserted successfully");
        }
        // create_emp_official.blade

        //return Redirect()->back()->with('success', "Personal Details inserted successfully");
    }

    public function edit($id)
    {
        $emp_personal = Employee_personal_detail::where('id', $id)->first();
        $p_cities = City::where('state_id', $emp_personal->p_state_id)->get();
        $c_cities = City::where('state_id', $emp_personal->c_state_id)->get();
        $qlevel = Qualificationlevel::all();
        $states = State::all();
        $countries = Country::all();
        $qualification = Qualification::all();
        return view('employeeprofile.edit', compact('emp_personal', 'id', 'qlevel', 'p_cities', 'c_cities', 'countries', 'states', 'qualification'));
    }

    public function update(Request $request)
    {
        // print_r("hello");exit();
        $emp_id = $request->emp_id;
        $emp_code = $request->emp_code;
        $validator = $request->validate([
            'subtitle' => 'required',
            'firstname' => 'required',
            'middlename' => 'required',
            'dob' => 'required',
            'mobile1' => 'required',
            'diff_abled' => 'required',
            'quali_level_id' => 'required',
            'quali_id' => 'required',
            'p_address1' => 'required',
            'p_city_id' => 'required',
            'p_state_id' => 'required',
            'p_country_id' => 'required',
            'p_address_pincode' => 'required',
            'blood_group' => 'required',
            // 'personal_emailID' => 'required|email|unique:employee_personal_details',

            'aadhaar_no' => 'required',
            'pan_no' => 'required',
            'dl_no' => 'required',
            'dl_expiry_date' => 'required',

            'c_address1' => 'required',
            'c_state_id' => 'required',
            'c_city_id' => 'required',
            'c_country_id' => 'required',
            'c_address_pincode' => 'required',
            'shift_id' => 'required',
        ]);
        $c_address = null;
        $c_city_id = null;
        $c_state_id = null;
        $c_country_id = null;
        $c_address_pincode = null;
        $same_address = $request->same_address;
        if ($same_address == "same") {
            $c_address = $request->p_address1;
            $c_city_id = $request->p_city_id;
            $c_state_id = $request->p_state_id;
            $c_country_id = $request->p_country_id;
            $c_address_pincode = $request->p_address_pincode;
        } else {
            $c_address = $request->c_address1;
            $c_city_id = $request->c_city_id;
            $c_state_id = $request->c_state_id;
            $c_country_id = $request->c_country_id;
            $c_address_pincode = $request->c_address_pincode;
        }
        $updateDetails = [
            'subtitle' => $request->subtitle,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'personal_emailID' => $request->personal_emailID,
            'landline' => $request->landline,
            'mobile1' => $request->mobile1,
            'mobile2' => $request->mobile2,
            'diff_abled' => $request->diff_abled,
            'aadhaar_no' => $request->aadhaar_no,
            'pan_no' => $request->pan_no,
            'dl_no' => $request->dl_no,
            'dl_expiry_date' => $request->dl_expiry_date,
            'quali_level_id' => $request->quali_level_id,
            'quali_id' => $request->quali_id,
            'p_address1' => $request->p_address1,
            'p_city_id' => $request->p_city_id,
            'p_state_id' => $request->p_state_id,
            'p_country_id' => $request->p_country_id,
            'p_address_pincode' => $request->p_address_pincode,
            'c_address1' => $c_address,
            'c_city_id' => $c_city_id,
            'c_state_id' => $c_state_id,
            'c_country_id' => $c_country_id,
            'c_address_pincode' => $c_address_pincode,
            'blood_group' => $request->blood_group,
            'guardian_type' => $request->guardian_type,
            'guardian_name' => $request->guardian_name,
            'guardian_mobile' => $request->guardian_mobile,
            'is_active' => 'Y',
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
            'shift_id' => $request->shift_id,
        ];
        $i = Employee_personal_detail::where('id', $emp_id)
            ->where('emp_code', $emp_code)
            ->update($updateDetails);
        if ($i) {
            return Redirect()->back()->with('success', "Personal Details updated successfully");
        } else {
            return Redirect()->back()->withInput()->with('success', "Personal Details updation error");
        }
    }

    public function edit_code($id)
    {
        $emp_id = $id;
        try {
            DB::beginTransaction();
            $e_code = Emp_code::where('type', 'Employee Code')->get();
            $emp_code = 0;
            $prefix = null;
            $emplo_code = 0;
            foreach ($e_code as $e) {
                $emplo_code = $e->emp_code;
                $prefix = $e->prefix;
            }
            $emp_code = $emplo_code + 1;
            //echo $emp_code;
            //  exit();
            $employee_code = null;
            if ($emp_code <= 9) {
                $employee_code = $prefix . "00" . $emp_code;
            } else if ($emp_code <= 99) {
                $employee_code = $prefix . "0" . $emp_code;
            } else if ($emp_code <= 999) {
                $employee_code = $prefix . "" . $emp_code;
            }
            $emp_personal = Employee_personal_detail::where('id', $emp_id)->first();
            $emp_name = $emp_personal->subtitle . " " . $emp_personal->firstname . " " . $emp_personal->middlename . " " . $emp_personal->lastname;
            $em_official = Employee_official_detail::where('emp_id', $id)->first();
            $emp_email = $emp_personal->personal_emailID;

            // print_r($em_official);exit();
            if ($em_official != null) {
                $emp_email = $em_official->official_emailid;
            }

            $em_bank = Employee_bank_detail::where('emp_id', $id)->first();

            $updateDetails = ['emp_code' => $emp_code];
            $j = Emp_code::where('id', 1)->update($updateDetails);

            if ($j) {
                $k = User::create([
                    'name' => $emp_name,
                    'emp_code' => $employee_code,
                    'email' => $emp_email,
                    'password' => Hash::make($employee_code . "123"),
                    'original_password' => $employee_code . "123",
                    'is_active' => 'Y',
                    'shift_id' => $emp_personal->shift_id,

                ]);

                if ($k) {
                    $updateDetail_p = [
                        'emp_code' => $employee_code,
                        'is_active' => 'Y',
                        'updated_by' => Auth::user()->id,
                        'updated_at' => Carbon::now()
                    ];

                    $m = Employee_personal_detail::where('id', $id)->update($updateDetail_p);
                    if ($m) {
                        if ($em_official != null) {
                            $updateDetail_o = [
                                'emp_code' => $employee_code,
                                'updated_by' => Auth::user()->id,
                                'updated_at' => Carbon::now()
                            ];
                            $n = Employee_official_detail::where('emp_id', $id)->update($updateDetail_o);
                            if ($n) {
                                if ($em_bank != null) {
                                    $h = Employee_bank_detail::where('emp_id', $id)
                                        ->update($updateDetail_o);
                                    if ($h) {
                                        DB::commit();

                                        return Redirect()->back()->with('success', "Employee Details inserted successfully and Generated Employee Code is $employee_code");
                                        //return view('employeeprofile.index')->with('success', "Employee Details inserted successfully and Generated Employee Code is $employee_code");
                                    } else {

                                        DB::rollBack();
                                        return Redirect()->back()->with('success', "Error in bank.Contact Admin");
                                    }
                                } else {
                                    DB::commit();

                                    return Redirect()->back()->with('success', "Employee Details inserted successfully and Generated Employee Code is $employee_code");
                                    //return  view('employeeprofile.index')->with('success', "Employee Details inserted successfully and Generated Employee Code is $employee_code");

                                }
                            } else {
                                DB::rollBack();
                                return Redirect()->back()->with('success', "error");
                            }
                        } else {
                            DB::commit();
                            return Redirect()->back()->with('success', "Employee Details inserted successfully and Generated Employee Code is $employee_code");
                            //return view('employeeprofile.index')->with('success', "Employee Details inserted successfully and Generated Employee Code is $employee_code");
                        }
                    } else {
                        DB::rollBack();
                        return Redirect()->back()->with('error', "Error in personal .Generate Employee Code and Edit details");
                    }
                } else {
                    DB::rollBack();
                    return Redirect()->back()->with('error', "Error in personal.Generate Employee Code and Edit details");
                }
                //inset emp_code in personal,official tables
                //  return view('employeeprofile.index', compact('emp_id', 'employee_code'))->with('success', "Employee Details inserted successfully and Generated Employee Code is $employee_code");
            } else {
                DB::rollBack();
                return Redirect()->back()->with('error', "Error in user.Generate Employee Code and Edit details");
            }
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return Redirect()->back()->with('error', "$e. Generate Employee Code and Edit details");
        }
    }

    public function show($id)
    {
        $personal = Employee_personal_detail::join('qualificationlevels', 'qualificationlevels.id', 'employee_personal_details.quali_level_id')
            ->join('qualifications', 'qualifications.id', 'employee_personal_details.quali_id')
            ->join('cities', 'cities.id', 'employee_personal_details.p_city_id')
            ->join('states', 'states.id', 'employee_personal_details.p_state_id')
            ->join('countries', 'countries.id', 'employee_personal_details.p_country_id')
            ->select('employee_personal_details.*', 'qualificationlevels.qualificationlevel', 'qualifications.qualification', 'cities.city', 'states.state', 'countries.country')
            ->where('employee_personal_details.id', $id)
            ->first();
        $official = Employee_official_detail::join('departments', 'departments.id', 'employee_official_details.department_id')
            ->join('designations', 'designations.id', 'employee_official_details.designation_id')
            ->join('employementmodes', 'employementmodes.id', 'employee_official_details.employementmode_id')
            ->select('employee_official_details.*', 'departments.department', 'employementmodes.employementmode', 'designations.designation')
            ->where('employee_official_details.emp_id', $id)
            ->first();
        $o_exists = 1;
        if ($official == null) {
            $o_exists = 0;
        }
        $bank = Employee_bank_detail::where('emp_id', $id)->first();

        $b_exists = 1;
        if ($bank == null) {
            $b_exists = 0;
        }
        $pip = Employee_pip_detail::where('emp_id', $id)->first();
        $exists = 1;
        if ($pip == null) {
            $exists = 0;
        }
        $s_exists = 1;

        $salary = Employee_salary_detail::where('emp_id', $id)->first();
        if ($salary == null) {
            $s_exists = 0;
        }
        $user = User::where('emp_code', $personal->emp_code)->first();
        // print_r($user);exit();

        return view('employeeprofile.show', compact('personal', 'official', 'bank', 'pip', 'salary', 'exists', 's_exists', 'o_exists', 'b_exists', 'user'));
    }

    public function edit_status_disable($id, $emp_code)
    {
        try {
            DB::beginTransaction();
            $updateDetails = [
                'is_active' => 'N',
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now()
            ];
            $updateDetails_user = [
                'is_active' => 'N',
                'updated_at' => Carbon::now()
            ];
            $updateDetails_empteam = [
                'is_active' => 'N',
                'updated_at' => Carbon::now()
            ];
            $updateDetails_official = [
                'team_id' => Null,
                'updated_at' => Carbon::now()
            ];
            $i = Employee_personal_detail::where('id', $id)->update($updateDetails);

            if ($i) {

                $j = User::where('emp_code', $emp_code)->update($updateDetails_user);

                if ($j) {
                    $check_inteam = Employee_team::where('emp_code', $emp_code)->where('is_active', 'Y')->first();
                    if ($check_inteam != null) {
                        $k = Employee_team::where('emp_code', $emp_code)
                            ->where('id', $check_inteam->id)->update($updateDetails_empteam);
                        $k2 = Employee_official_detail::where('emp_code', $emp_code)
                            ->update($updateDetails_official);
                        if ($k && $k2) {
                            DB::commit();
                            return Redirect()->back()->with('success', "Status updated successfully");
                        }
                    } else {
                        DB::commit();
                        return Redirect()->back()->with('success', "Status updated successfully");
                    }
                } else {
                    DB::rollBack();
                    return Redirect()->back()->with('error', "Status updation error");
                }
            } else {
                DB::rollBack();
                return Redirect()->back()->with('error', "Status updation error");
            }
        } catch (\Exception $e) {
            return Redirect()->back()->with('success', "Status updation error");
        }
    }

    public function edit_status_enable($id, $emp_code)
    {
        try {
            DB::beginTransaction();
            $updateDetails = [
                'is_active' => 'Y',
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now()
            ];
            $updateDetails_user = [
                'is_active' => 'Y',
                'updated_at' => Carbon::now()
            ];
            $i = Employee_personal_detail::where('id', $id)->update($updateDetails);
            if ($i) {

                $j = User::where('emp_code', $emp_code)->update($updateDetails_user);
                if ($j) {
                    DB::commit();
                    return Redirect()->back()->with('success', "Status updated successfully");
                } else {
                    DB::rollBack();
                    return Redirect()->back()->with('error', "Status updation error");
                }
            } else {
                DB::rollBack();
                return Redirect()->back()->with('error', "Status updation error");
            }
        } catch (\Exception $e) {
            return Redirect()->back()->with('success', "Status updation error");
        }
    }

    public function getCity(Request $request)
    {

        $search = $request->search;

        if ($search == '') {
            $cities = City::orderby('city', 'asc')->select('id', 'city')->limit(5)->get();
        } else {
            $cities = City::orderby('city', 'asc')->select('id', 'city')->where('city', 'like', '%' . $search . '%')->limit(5)->get();
        }

        $response = array();
        foreach ($cities as $c) {
            $response[] = array(
                "id" => $c->id,
                "text" => $c->city
            );
        }

        return response()->json($response);
    }

    public function fetchQualification(Request $request)
    {
        $data['qualification'] = Qualification::where("qualificationlevel_id", $request->quali_level_id)->get(["qualification", "id"]);
        return response()->json($data);
    }

    public function fetchCity(Request $request)
    {
        $data['city'] = City::where("state_id", $request->state_id)->get(["city", "id"]);
        return response()->json($data);
    }

    // export employee starts
    public function exportemployee(Request $request)
    {

        // return Excel::download(new TestExport, 'users.xlsx');   // 1 option

        // 2 option
        // $users = User::query();
        // $users->where('is_active','Y');

        // Apply filters, if any
        if ($request->has('export')) {
            $users = Employee_personal_detail::join('users', 'users.emp_code', 'employee_personal_details.emp_code')
                ->join('employee_official_details', 'employee_official_details.emp_id', 'employee_personal_details.id')
                ->leftjoin('qualifications', 'qualifications.id', 'employee_personal_details.quali_id')
                ->leftjoin('qualificationlevels', 'qualificationlevels.id', 'employee_personal_details.quali_level_id')
                ->leftjoin('countries', 'countries.id', 'employee_personal_details.p_country_id')
                ->leftjoin('states', 'states.id', 'employee_personal_details.p_state_id')
                ->leftjoin('cities', 'cities.id', 'employee_personal_details.p_city_id')
                ->leftjoin('departments', 'departments.id', 'employee_official_details.department_id')
                ->leftjoin('designations', 'designations.id', 'employee_official_details.designation_id')
                ->leftjoin('employementmodes', 'employementmodes.id', 'employee_official_details.employementmode_id')
                ->select(
                    'users.*',
                    'employee_personal_details.*',
                    'qualificationlevels.qualificationlevel',
                    'qualifications.qualification',
                    'cities.city',
                    'states.state',
                    'countries.country',
                    'employee_official_details.*',
                    'departments.department',
                    'employementmodes.employementmode',
                    'designations.designation'
                );



            if ($request->export == 'active') {
                $users->where('employee_personal_details.is_active', 'Y');
                $filename = 'active_employees.xlsx';
                // ->orwhere('users.is_active','Y')
            } elseif ($request->export == 'inactive') {
                $users->where('employee_personal_details.is_active', 'N');
                $filename = 'inactive_employees.xlsx';
            } else {
                // Default filename if export parameter is not provided
                $filename = 'employees.xlsx';
            }

            // $users =$users->get();
            // print_r($users);exit();

            $response = Excel::download(new EmployeeExport($users->orderBy('employee_personal_details.created_at', 'DESC')->get()), $filename);

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
