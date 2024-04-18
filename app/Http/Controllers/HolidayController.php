<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HolidayLog;
use App\Models\Holiday;
// use Auth;
use Illuminate\Support\Facades\Auth;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $holiday = Holiday::orderByDesc('id')->paginate(10);
        return view('admin.holiday.index', compact('holiday'));
    }

    public function history($id)
    {
        $holiday_logs = HolidayLog::where('holiday_id', $id)->get();
        return view('admin.holiday.history', compact('holiday_logs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'holiday_date' => 'required|max:50',
            'holiday_reason' => 'required',
        ]);

        $data = array();
        $data['holiday_date'] = $request->holiday_date;
        $data['holiday_reason'] = $request->holiday_reason;
        $data['created_at'] = Carbon::now();
        $holiday_id = Holiday::insertGetId($data);

        $log_data                       = new HolidayLog();
        $log_data->holiday_date         = trim($request->holiday_date);
        $log_data->holiday_reason       = trim($request->holiday_reason);
        $log_data->holiday_id           = $holiday_id;
        $log_data->user_id              = Auth::user()->id;
        $log_data->type                 = 'created';
        $log_data->save();

        return Redirect()->back()->with('success', "Holidays inserted successfully");
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
        $holiday_id = $request->id;

        $updateDetails = [
            'holiday_date' => $request->get('holiday_date'),
            'holiday_reason' => $request->get('holiday_reason'),
            'updated_at' => Carbon::now(),

        ];
        Holiday::where('id', $holiday_id)->update($updateDetails);

        $log_data                       = new HolidayLog();
        $log_data->holiday_date         = trim($request->holiday_date);
        $log_data->holiday_reason       = trim($request->holiday_reason);
        $log_data->holiday_id           = $holiday_id;
        $log_data->user_id              = Auth::user()->id;
        $log_data->type                 = 'updated';
        $log_data->save();
        return Redirect()->back()->with('success', "Holiday updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $holiday = Holiday::where('id', $id)->first();

        if (!$holiday) {
            return Redirect()->back()->with('error', "Record not found");
        }
        $holiday->delete();
        return Redirect()->back()->with('success', "Record deleted successfully");
    }
}
