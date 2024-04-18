<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Week_off;
use App\Models\Employee_personal_detail;
// use Auth;
use Illuminate\Support\Facades\Auth;

class WeekoffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $holiday = Week_off::orderByDesc('id')->paginate(10);
        $employees = Employee_personal_detail::where('is_active', 'Y')->get();
        return view('admin.weekoff.index', compact('holiday', 'employees'));
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
        // print_r($request->all());
        // exit();

        $employees = $request->input('employee');
        $dates = explode(', ', $request->input('date'));
        $wo = $request->input('WO');


        // Iterate through employees and store data
        foreach ($employees as $employee) {

            foreach ($dates as $date) {
                // Check if a record already exists for the current employee and date
                $existingRecord = Week_off::where('emp_code', $employee)
                    ->where('date', $date)
                    ->first();

                // Create a record for the current employee and date
                if (!$existingRecord) {
                    Week_off::create([
                        'emp_code' => $employee,
                        'date' => $date,
                        'type' => $wo,
                        'added_by' => Auth::user()->id,
                    ]);
                }
            }
        }
        return Redirect()->back()->with('success', "Week Off inserted successfully");
    }

    public function destroy($id)
    {

        // Find the record based on emp_code and date
        $weekOff = Week_off::where('id', $id)->first();

        if (!$weekOff) {
            return Redirect()->back()->with('error', "Record not found");
        }

        $weekOff->delete();
        return Redirect()->back()->with('success', "Record deleted successfully");
    }
}
