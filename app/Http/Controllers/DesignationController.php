<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DesignationLog;
use App\Models\Designation;
use Carbon\Carbon;
// use Auth;
use Illuminate\Support\Facades\Auth;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $desigs = Designation::paginate(2);
        return view('admin.designation.index', compact('desigs'));
    }

    public function history($id)
    {
        $desig_logs = DesignationLog::where('designation_id', $id)->get();
        return view('admin.designation.history', compact('desig_logs'));
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
            'designation' => 'required|unique:designations|max:255',
        ]);

        $data = array();
        $data['designation'] = $request->designation;

        $designation_id = Designation::insertGetId($data);

        $log_data                       = new DesignationLog();
        $log_data->designation          = trim($request->designation);
        $log_data->designation_id       = $designation_id;
        $log_data->user_id              = Auth::user()->id;
        $log_data->type                 = 'created';
        $log_data->save();

        return Redirect()->back()->with('success', "Designation inserted successfully");
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
            'designation' => $request->get('designation'),

        ];
        Designation::where('id', $id)->update($updateDetails);

        $log_data                       = new DesignationLog();
        $log_data->designation          = trim($request->designation);
        $log_data->designation_id       = $id;
        $log_data->user_id              = Auth::user()->id;
        $log_data->type                 = 'updated';
        $log_data->save();

        return Redirect()->back()->with('success', "Designation updated successfully");
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
