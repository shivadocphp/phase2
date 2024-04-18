<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Employee_team;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;
use App\Models\Employee_official_detail;

class Team_ManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emp_teams = Employee_team::join('users', 'users.emp_code', 'employee_teams.emp_code')
            ->join('teams', 'teams.id', 'employee_teams.team_id')
            ->select('employee_teams.*', 'users.name', 'teams.team')
            ->where('employee_teams.is_active', 'Y')
            ->get();

        return view('team_management.index', compact('emp_teams'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $team_id = $request->team_id;
        $member_from = $request->member_from;
        $input = $request->all();
        $input = $request->input('emp_id');

        foreach ($input as $in) {
            $emp = explode("-", $in);
            $emp_id = $emp[0];
            $emp_code = $emp[1];
            $i = Employee_team::insert([
                'team_id' => $team_id,
                'emp_id' => $emp_id,
                'emp_code' => $emp_code,
                'member_from' => $member_from,
                'is_active' => 'Y',
                'added_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            if ($i) {
                $updateDetails = [
                    'team_id' => $team_id,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => Carbon::now()

                ];
                $j = Employee_official_detail::where('emp_id', $emp_id)->update($updateDetails);
            }
        }
        return Redirect()->back()->with('success', "Employee allocation done successfully");
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
    public function edit($id)
    {
        //  $teams = Employee_team::where('team_id',$id)->get();

        $empteams = Employee_team::where('team_id', $id)
            ->get('emp_code')->toArray();

        $team = Team::where('id', $id)->first();
        $employees = Employee_official_detail::join('users', 'users.emp_code', 'employee_official_details.emp_code')
            ->select('employee_official_details.*', 'users.name')
            ->get();
        return view('team_management.edit', compact('empteams', 'employees', 'id', 'team'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
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
