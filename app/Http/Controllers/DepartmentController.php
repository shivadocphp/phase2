<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DepartmentLog;
use App\Models\Department;
use Carbon\Carbon;
// use Auth;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depts = Department::paginate(2);
        return view('admin.Department.index', compact('depts'));
    }

    public function history($id)
    {
        $dept_logs = DepartmentLog::where('department_id', $id)->get();
        return view('admin.Department.history', compact('dept_logs'));
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
        $validated = $request->validate([
            'department' => 'required|unique:departments|max:255',
        ]);

        $data = array();
        $data['department'] = $request->department;

        $department_id = Department::insertGetId($data);

        $log_data                       = new DepartmentLog();
        $log_data->department          = trim($request->department);
        $log_data->department_id       = $department_id;
        $log_data->user_id              = Auth::user()->id;
        $log_data->type                 = 'created';
        $log_data->save();

        return Redirect()->back()->with('success', "Department inserted successfully");
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
    public function update(Request $request)
    {
        $department_id = $request->id;

        $updateDetails = [
            'department' => $request->get('dept'),

        ];
        Department::where('id', $department_id)->update($updateDetails);

        $log_data                       = new DepartmentLog();
        $log_data->department          = trim($request->dept);
        $log_data->department_id       = $department_id;
        $log_data->user_id              = Auth::user()->id;
        $log_data->type                 = 'updated';
        $log_data->save();

        return Redirect()->back()->with('success', "Department updated successfully");
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
