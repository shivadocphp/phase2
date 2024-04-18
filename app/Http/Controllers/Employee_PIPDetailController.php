<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Employee_bank_detail;
use App\Models\Employee_personal_detail;
use App\Models\Employee_pip_detail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Employee_PIPDetailController extends Controller
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
    public function edit_emp_pip($id)
    {
        $emp_pip = Employee_pip_detail::where('emp_id', $id)->first();

        $exists = 0;
        if ($emp_pip != null) {
            $exists = 1;
        }
        $emp_personal = Employee_personal_detail::where('id', $id)->first();

        $emp_code = $emp_personal->emp_code;
        $emp_name = $emp_personal->subtitle . " " . $emp_personal->firstname . " " . $emp_personal->middlename . " " . $emp_personal->lastname;

        return view('employeeprofile.edit_emp_pip', compact('emp_pip', 'exists', 'id', 'emp_code', 'emp_name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update_emp_pip(Request $request)
    {
        $emp_id = $request->emp_id;
        $emp_code = $request->emp_code;

        $validator = $request->validate([
            'emp_id' => 'required',
            'emp_code' => 'required',
            'first_review' => 'required',
            'second_review' => 'required',
            'third_review' => 'required',
            'review_comment' => 'required',
        ]);

        if ($request->edit_pip == "create_pip") {
            $i = Employee_pip_detail::insert([
                'emp_id' => $emp_id,
                'emp_code' => $emp_code,
                'first_review' => $request->first_review,
                'second_review' => $request->second_review,
                'third_review' => $request->third_review,
                'review_comment' => $request->review_comment,
                'added_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ]);
            if ($i) {
                return Redirect()->back()->with('success', "PIP Details added successfully");
            } else {
                return Redirect()->back()->with('error', "PIP Details insertion error");
            }
        } else if ($request->edit_pip == "update_pip") {
            $updateDetails = [
                'first_review' => $request->first_review,
                'second_review' => $request->second_review,
                'third_review' => $request->third_review,
                'review_comment' => $request->review_comment,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
            $i = Employee_pip_detail::where('emp_id', $emp_id)
                ->where('emp_code', $emp_code)
                ->update($updateDetails);
            if ($i) {
                return Redirect()->back()->with('success', "PIP Details updated successfully");
            } else {
                return Redirect()->back()->with('error', "PIP Details updation error");
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
