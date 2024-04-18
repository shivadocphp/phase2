<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EmploymentmodeLog;
use App\Models\Employementmode;
use Carbon\Carbon;
// use Auth;
use Illuminate\Support\Facades\Auth;

class EmployementmodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employementmodes =Employementmode::paginate(5);
        return view('admin.employementmode.index',compact('employementmodes'));
    }

    public function history($id)
    {
        $employmentmode_logs =EmploymentmodeLog::where('employmentmode_id',$id)->get();
        return view('admin.employementmode.history',compact('employmentmode_logs'));
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
            'employementmode' => 'required|unique:employementmodes|max:5',
        ]);

        $data = array();
        $data['employementmode'] = trim($request->employementmode);
        $data['created_at']      = Carbon::now();

        $employmentmode_id = Employementmode::insertGetId($data);
        
        $log_data                       = new EmploymentmodeLog();
        $log_data->employementmode      = trim($request->employementmode);
        $log_data->employmentmode_id    = $employmentmode_id;
        $log_data->user_id              = Auth::user()->id;
        $log_data->type                 = 'created';
        $log_data->save();

        return Redirect()->back()->with('success',"Employement mode inserted successfully");

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
        $id = $request->id;

        $updateDetails = [
            'employementmode' => $request->get('employementmode'),

        ];
        Employementmode::where('id', $id)->update($updateDetails);

        $log_data                       = new EmploymentmodeLog();
        $log_data->employmentmode       = trim($request->employementmode);
        $log_data->employmentmode_id    = $id;
        $log_data->user_id              = Auth::user()->id;
        $log_data->type                 = 'updated';
        $log_data->save();

        return Redirect()->back()->with('success', "employementmode updated successfully");
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
