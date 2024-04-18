<?php

namespace App\Http\Controllers;

use App\Models\Employee_personal_detail;
use App\Models\Employee_pip_detail;
use App\Models\Employee_salary_detail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Employee_SalaryDetailController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit_emp_salary($id)
    {
        $emp_salary = Employee_salary_detail::where('emp_id', $id)->first();

        $exists = 0;
        if ($emp_salary != null) {
            $exists = 1;
        }
        $emp_personal = Employee_personal_detail::where('id', $id)->first();

        $emp_code = $emp_personal->emp_code;
        $emp_name = $emp_personal->subtitle . " " . $emp_personal->firstname . " " . $emp_personal->middlename . " " . $emp_personal->lastname;

        return view('employeeprofile.edit_emp_salary', compact('emp_salary', 'exists', 'id', 'emp_code', 'emp_name'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update_emp_salary(Request $request)
    {
        $emp_id = $request->emp_id;
        $emp_code = $request->emp_code;

        $validator = $request->validate([
            'emp_id' => 'required',
            'emp_code' => 'required',
            'fixed_basic' => 'required',
            'fixed_hra' => 'required',
            'fixed_conveyance' => 'required',
            'employer_pf' => 'required',
            'employer_esi' => 'required',
        ]);

        if ($request->edit_salary == "add_salary") {
            $i = Employee_salary_detail::insert([
                'emp_id' => $emp_id,
                'emp_code' => $emp_code,
                'fixed_basic' => $request->fixed_basic,
                'fixed_hra'=>$request->fixed_hra,
                'fixed_conveyance'=>$request->fixed_conveyance,
                'employer_pf'=>$request->employer_pf,
                'employer_esi'=>$request->employer_esi,
                'employee_pf'=>$request->employee_pf,
                'employee_esi'=>$request->employee_esi,
                'casual_leave_available'=>$request->casual_leave_available,
                'monthly_target'=>$request->monthly_target,
                'start_date'=>$request->start_date,
                'comments' =>$request->comments,
                'added_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ]);
            if ($i) {
                return Redirect()->back()->with('success', "Salary Details added successfully");
            } else {
                return Redirect()->back()->with('error', "Salary Details  insertion error");
            }


        } else if ($request->edit_salary == "edit_salary") {
            $updateDetails = [
                'fixed_basic' => $request->fixed_basic,
                'fixed_hra'=>$request->fixed_hra,
                'fixed_conveyance'=>$request->fixed_conveyance,
                'employer_pf'=>$request->employer_pf,
                'employer_esi'=>$request->employer_esi,
                'employee_pf'=>$request->employee_pf,
                'employee_esi'=>$request->employee_esi,
                'casual_leave_available'=>$request->casual_leave_available,
                'monthly_target'=>$request->monthly_target,
                'start_date'=>$request->start_date,
                'comments' =>$request->comments,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
            $i = Employee_salary_detail::where('emp_id', $emp_id)
                ->where('emp_code', $emp_code)
                ->update($updateDetails);
            if ($i) {
                return Redirect()->back()->with('success', "Salary Details updated successfully");
            } else {
                return Redirect()->back()->with('error', "Salary Details updation error");
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
