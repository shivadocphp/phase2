<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LeavetypeLog;
use App\Models\Leavetype;
use Carbon\Carbon;
// use Auth;
use Illuminate\Support\Facades\Auth;

class LeavetypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $l_types = Leavetype::paginate(5);
        //echo $subtitles;
        //exit();
        return view('admin.leavetype.index', compact('l_types'));
    }

    public function history($id)
    {
        $l_type_logs = LeavetypeLog::where('leavetype_id', $id)->get();
        return view('admin.leavetype.history', compact('l_type_logs'));
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
            'leavetype' => 'required|unique:leavetypes|max:50',
        ]);

        $data = array();
        $data['leavetype'] = $request->leavetype;
        $leavetype_id = Leavetype::insertGetId($data);

        $log_data                       = new LeavetypeLog();
        $log_data->leavetype            = trim($request->leavetype);
        $log_data->leavetype_id         = $leavetype_id;
        $log_data->user_id              = Auth::user()->id;
        $log_data->type                 = 'created';
        $log_data->save();

        return Redirect()->back()->with('success', "Type of Leave inserted successfully");
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
        $leavetype_id = $request->id;

        $updateDetails = [
            'leavetype' => $request->get('leavetype'),

        ];
        Leavetype::where('id', $leavetype_id)->update($updateDetails);

        $log_data                       = new LeavetypeLog();
        $log_data->leavetype            = trim($request->leavetype);
        $log_data->leavetype_id         = $leavetype_id;
        $log_data->user_id              = Auth::user()->id;
        $log_data->type                 = 'updated';
        $log_data->save();

        return Redirect()->back()->with('success', "Leave type updated successfully");
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
