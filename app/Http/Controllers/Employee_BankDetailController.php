<?php

namespace App\Http\Controllers;

use App\Models\Emp_code;
use App\Models\Employee_bank_detail;
use App\Models\Employee_official_detail;
use App\Models\Employee_personal_detail;
use App\Models\Employee_pip_detail;
use App\Models\Employee_salary_detail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Employee_BankDetailController extends Controller
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
    public function create_bank()
    {
        return view('employeeprofile.create_emp_bank');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_emp_bank(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required',
            'account_no' => 'required',
            'ifsc_code' => 'required',
            'branch_code' => 'required'
        ]);

        $employee_code = null;
        $emp_id = $request->emp_id;
        $emp_name = $request->emp_name;
        $em_email = Employee_official_detail::where('emp_id', $emp_id)->get();
        $emp_email = null;
        foreach ($em_email as $em) {
            $emp_email = $em->official_emailid;
        }
        //  echo $emp_email;
        // exit();

        if ($request->save_bank == "save_bank") {
            $bank = Employee_bank_detail::insert([
                'emp_id' => $emp_id,
                'emp_code' => null,
                'bank_name' => $request->bank_name,
                'account_no' => $request->account_no,
                'ifsc_code' => $request->ifsc_code,
                'branch_code' => $request->branch_code,
                'added_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if ($bank) {
                $employee = Employee_personal_detail::where('emp_code', NULL)->get();
                return view('employeeprofile.index_ipj', compact('employee'))->with('success', "Employee Details inserted successfully.");
            } else {
                $employee = Employee_personal_detail::where('emp_code', NULL)->get();
                return view('employeeprofile.index_ipj', compact('employee'))->with('error', "Bank details inserted unsuccessfully.");

            }
        } else if ($request->generate == "generate_emp_id") {

            //     $employee = Employee_personal_detail::all();
            try {
                DB::beginTransaction();
                $e_code = Emp_code::where('type', 'Employee Code')->first();
                //  $emp_code = 0;
                $employee_code = null;
                $emplo_code = $e_code->emp_code;
                $prefix = $e_code->prefix;

                $emp_code = $emplo_code + 1;;

                if ($emp_code <= 9) {
                    $employee_code = $prefix . "00" . $emp_code;
                } else if ($emp_code <= 99) {
                    $employee_code = $prefix . "0" . $emp_code;
                } else if ($emp_code <= 999) {
                    $employee_code = $prefix . "" . $emp_code;
                }

                $i = Employee_bank_detail::insert([
                    'emp_id' => $emp_id,
                    'emp_code' => $employee_code,
                    'bank_name' => $request->bank_name,
                    'account_no' => $request->account_no,
                    'ifsc_code' => $request->ifsc_code,
                    'branch_code' => $request->branch_code,
                    'added_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                if ($i) {
                    $updateDetails = ['emp_code' => $emp_code];
                    $j = Emp_code::where('id', 1)->update($updateDetails);

                    if ($j) {
                        $k = User::create([
                            'name' => $emp_name,
                            'emp_code' => $employee_code,
                            'email' => $emp_email,
                            'password' => Hash::make($employee_code . "123"),
                            'is_active' => 'Y',
                        ]);

                        if ($k) {
                            $updateDetail_p = [
                                'emp_code' => $employee_code,
                                'is_active' => 'Y',
                                'updated_by' => Auth::user()->id,
                                'updated_at' => Carbon::now()
                            ];
                            $updateDetail_o = [
                                'emp_code' => $employee_code,
                                'updated_by' => Auth::user()->id,
                                'updated_at' => Carbon::now()
                            ];
                            $m = Employee_personal_detail::where('id', $emp_id)->update($updateDetail_p);
                            if ($m) {
                                $n = Employee_official_detail::where('emp_id', $emp_id)->update($updateDetail_o);
                                if ($n) {

                                    DB::commit();
                                    $employee = Employee_personal_detail::join('employee_official_details', 'employee_official_details.emp_id', 'employee_personal_details.id')
                                        ->join('departments', 'departments.id', 'employee_official_details.department_id')
                                        ->join('designations', 'designations.id', 'employee_official_details.designation_id')
                                        //->join('users','users.emp_code','employee_personal_details.emp_code')
                                        ->select('employee_personal_details.*', 'departments.department', 'designations.designation')
                                        // ->where('employee_official_details.team_id', NULL)
                                        ->get();

                                    return view('employeeprofile.index', compact('employee'))->with('success', "Employee Details inserted successfully and Generated Employee Code is $employee_code");

                                } else {
                                    DB::rollBack();
                                    $employee = Employee_personal_detail::where('emp_code', NULL)->get();
                                    return view('employeeprofile.index_ipj', compact('employee'))->with('error', "Official details inserted unsuccessfully.");

                                }
                                //  return view('employeeprofile.index',compact('employee'))->with('error', "Error in Salary.Generate Employee Code and Edit details");
                            } else {
                                DB::rollBack();
                                $employee = Employee_personal_detail::where('emp_code', NULL)->get();
                                return view('employeeprofile.index_ipj', compact('employee'))->with('error', "Error in official .Generate Employee Code and Edit details");

                            }
                        } else {
                            DB::rollBack();
                            $employee = Employee_personal_detail::where('emp_code', NULL)->get();
                            return view('employeeprofile.index_ipj', compact('employee'))->with('error', "Error in user.Generate Employee Code and Edit details");

                        }
                        //inset emp_code in personal,official tables
                        //  return view('employeeprofile.index', compact('emp_id', 'employee_code'))->with('success', "Employee Details inserted successfully and Generated Employee Code is $employee_code");
                    } else {
                        DB::rollBack();
                        $employee = Employee_personal_detail::where('emp_code', NULL)->get();
                        return view('employeeprofile.index_ipj', compact('employee'))->with('error', " Emp code updation error.");

                    }
                } else {
                    DB::rollBack();
                    $employee = Employee_personal_detail::where('emp_code', NULL)->get();
                    return view('employeeprofile.index_ipj', compact('employee'))->with('error', "Bank details inserted unsuccessfully.");


                }
            } catch (\Exception $e) {
                $employee = Employee_personal_detail::where('emp_code', NULL)->get();
                return view('employeeprofile.index', compact('employee'))->with('error', "$e. Generate Employee Code and Edit details");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit_emp_bank($id)
    {

        $emp_bank = Employee_bank_detail::where('emp_id', $id)->first();
        $exists = 0;
        if ($emp_bank != null) {
            $exists = 1;
        }
        $emp_personal = Employee_personal_detail::where('id', $id)->first();

        $emp_code = $emp_personal->emp_code;
        $emp_name = $emp_personal->subtitle . " " . $emp_personal->firstname . " " . $emp_personal->middlename . " " . $emp_personal->lastname;

        return view('employeeprofile.edit_emp_bank', compact('emp_bank', 'exists', 'id', 'emp_code', 'emp_name'));

        // return view('employeeprofile.edit_emp_bank', compact('emp_bank','id'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update_emp_bank(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required',
            'account_no' => 'required',
            'ifsc_code' => 'required',
            'branch_code' => 'required'
        ]);
        $emp_id = $request->emp_id;
        $emp_code = $request->emp_code;
        if ($request->edit_bank == "edit_bank") {
            $updateDetails = [
                'bank_name' => $request->bank_name,
                'account_no' => $request->account_no,
                'ifsc_code' => $request->ifsc_code,
                'branch_code' => $request->branch_code,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
            $i = Employee_bank_detail::where('emp_id', $emp_id)
                ->where('emp_code', $emp_code)
                ->update($updateDetails);
            if ($i) {
                return Redirect()->back()->with('success', "Bank Details updated successfully");
            } else {
                return Redirect()->back()->with('error', "Bank Details updation error");
            }
        }else if($request->edit_bank=="add_bank"){
            $bank = Employee_bank_detail::insert([
                'emp_id' => $emp_id,
                'emp_code' => $emp_code,
                'bank_name' => $request->bank_name,
                'account_no' => $request->account_no,
                'ifsc_code' => $request->ifsc_code,
                'branch_code' => $request->branch_code,
                'added_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if($bank){
                return Redirect()->back()->with('success', "Bank Details added successfully");
            }else{
                return Redirect()->back()->with('error', "Bank Details added unsuccessfully.Please try again");
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
