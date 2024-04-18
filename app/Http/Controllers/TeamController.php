<?php

namespace App\Http\Controllers;

use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Models\Employee_official_detail;
use App\Models\Employee_personal_detail;
use App\Models\Employee_team;
use App\Models\State;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Exports\TeamMemberExport;
use Maatwebsite\Excel\Facades\Excel;

class TeamController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::join('employee_personal_details', 'employee_personal_details.id', 'teams.team_leader')
            ->select('teams.*', 'employee_personal_details.subtitle', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname')
            //->where('employee_official_details.team_id', NULL)
            ->get();

        $employees = Employee_official_detail::join('employee_personal_details', 'employee_personal_details.emp_code', 'employee_official_details.emp_code')
            ->select('employee_official_details.*', 'employee_personal_details.subtitle', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname')
            ->where('employee_official_details.team_id', 'NOT', NULL)
            ->orwhere('employee_official_details.team_id', 'NOT', 0)
            ->where('employee_personal_details.is_active', 'NOT', 'N')
            ->get();

        $emp_teams = Employee_official_detail::join('users', 'users.emp_code', 'employee_official_details.emp_code')
            ->select('employee_official_details.*', 'users.name')
            ->get();
        $add_mem = Employee_official_detail::join('employee_personal_details', 'employee_personal_details.emp_code', 'employee_official_details.emp_code')
            ->select('employee_official_details.*', 'employee_personal_details.subtitle', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname')
            ->where('employee_personal_details.is_active', 'Y')
            ->where('employee_official_details.team_id', NULL)
            ->orwhere('employee_official_details.team_id', 0)
            ->get();
        $employ = Employee_official_detail::join('employee_personal_details', 'employee_personal_details.emp_code', 'employee_official_details.emp_code')
            ->select('employee_official_details.*', 'employee_personal_details.subtitle', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname')
            ->where('employee_official_details.team_id', 'NOT', NULL)
            ->orwhere('employee_official_details.team_id', 'NOT', 0)
            ->where('employee_personal_details.is_active', 'NOT', 'N')
            ->get();

        return view('admin.team.index', compact('teams', 'employees', 'emp_teams', 'add_mem', 'employ'));
    }

    public function getTeam()
    {
        $teams = Team::join('employee_personal_details', 'employee_personal_details.id', 'teams.team_leader')
            ->select('teams.*', 'employee_personal_details.subtitle', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname')
            //->where('employee_official_details.team_id', NULL)
            ->get();
        $add_mem = Employee_official_detail::join('employee_personal_details', 'employee_personal_details.emp_code', 'employee_official_details.emp_code')
            ->select('employee_official_details.*', 'employee_personal_details.subtitle', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname')
            ->where('employee_official_details.team_id', NULL)
            ->orwhere('employee_official_details.team_id', 0)
            ->get();
        $employ = Employee_official_detail::join('employee_personal_details', 'employee_personal_details.emp_code', 'employee_official_details.emp_code')
            ->select('employee_official_details.*', 'employee_personal_details.subtitle', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname')
            ->where('employee_official_details.team_id', 'NOT', NULL)
            ->orwhere('employee_official_details.team_id', 'NOT', 0)
            ->get();

        $each_employee = new Collection();
        foreach ($teams as $key => $value) {
            $each_employee->push([
                'team_name' => '<a href="" class="members" title="View Members" data-toggle="modal"
                                data-id="' . $value->id . '" data-target="#members">' . $value->team . '</a>',
                'name' => $value->subtitle . "" . $value->firstname . " " . $value->middlename . " " . $value->lastname,
                'description' => $value->description, 'action' => '<a href="" class="edit_members" 
                        data-mytl="' . $value->team_leader . '"data-myteamdetail="' . $employ . '"
                        data-mydescription="' . $value->description . '" data-myteam="' . $value->team . '"
                        data-myid="' . $value->id . '" data-mymem="' . $add_mem . '" data-toggle="modal"
                        data-target="#edit" placeholder="Edit Members">
                        <i class="fa fa-edit" title="edit ' . $value->team . '" style="color:green"></i></a>',
            ]);
        }

        return DataTables::of($each_employee)->addIndexColumn()->rawColumns(['action', 'team_name'])->make(true);
    }

    public function team_detail(Request $request)
    {

        return Employee_team::join('employee_personal_details', 'employee_personal_details.id', 'employee_teams.emp_id')
            ->select('employee_teams.*', 'employee_personal_details.subtitle', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname')
            ->where('employee_teams.is_active', 'Y')
            ->where('team_id', $request->id)
            ->where('member_type', 'M')
            ->get();
    }

    public function team_add(Request $request)
    {
        // print_r($request->all());exit();
        /* return DB::table('employee_official_details')
          ->join('users', 'users.emp_code', 'employee_official_details.emp_code')
          ->select('employee_official_details.*', 'users.name')
          ->where('users.is_active', 'Y')
          ->where('employee_official_details.team_id', NULL)
          ->orwhere('employee_official_details.team_id', 0)
          ->get(); */
        $data['emp'] = Employee_personal_detail::join('employee_official_details', 'employee_official_details.emp_code', 'employee_personal_details.emp_code')
            ->select('employee_personal_details.*')
            ->where('employee_personal_details.is_active', 'Y')
            ->orderBy('employee_personal_details.emp_code')
            ->get();

        $data['emp_team_member'] = Employee_team::join('employee_personal_details', 'employee_personal_details.id', 'employee_teams.emp_id')
            ->select('employee_teams.*', 'employee_personal_details.subtitle', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname')
            ->where('employee_teams.is_active', 'Y')
            ->where('team_id', $request->id)
            ->where('member_type', 'M')
            ->where('employee_personal_details.is_active', 'Y')
            ->orderBy('employee_personal_details.emp_code')
            ->get();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $team_id = $request->team_id;
        $input = $request->all();
        $input = $request->input('emp_id');

        foreach ($input as $in) {

            $emp_id = Employee_personal_detail::where('emp_code', $in)->first();
            $id = $emp_id->id;
            $i = Employee_team::insert([
                'team_id' => $team_id,
                'emp_id' => $id,
                'emp_code' => $in,
                'member_type' => 'M',
                'is_active' => 'Y',
                'added_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            if ($i) {
                $updateDetails_o = ['team_id' => $team_id];
                $j = Employee_official_detail::where('emp_code', $in)->update($updateDetails_o);
                if ($j) {
                    return Redirect()->back()->with('success', "Team members updated successfully");
                }
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // print_r($request->all());exit();
        // to check the the TL and member are choosen
        $tl_emp_code = Employee_personal_detail::where('id', $request->team_leader)->first();
        $team_emp_code = $request->input('emp_id');
        foreach ($team_emp_code as $team) {
            if ($team == $tl_emp_code->emp_code) {
                // print_r("tl and emp is same");
                return Redirect()->back()->with('error', "TL and the TEAM MEMBER not to choose same");
                exit();
            } // else{ print_r("not same"); }
        }
        // exit();

        $team_leader = $request->team_leader;
        try {
            DB::beginTransaction();
            $data = array();
            $data['team'] = $request->team_name;
            $data['team_leader'] = $team_leader;
            $data['description'] = $request->description;
            $data['added_by'] = Auth::user()->id;
            $data['updated_by'] = Auth::user()->id;
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();

            $i = Team::insert($data); //insertGetId

            if ($i) {
                $team_id_get = Team::where('team', $request->team_name)->first();
                $team_id = $team_id_get->id;
                $updateDetails = [
                    'team_id' => $team_id,
                    'updated_at' => Carbon::now(),
                ];

                // echo $team_leader;
                $emp_cod = Employee_personal_detail::where('id', $team_leader)->first();
                $j = Employee_official_detail::where('emp_id', $request->team_leader)->update($updateDetails);
                if ($j) {

                    $k = Employee_team::insert([
                        'team_id' => $team_id,
                        'emp_id' => $request->team_leader,
                        'emp_code' => $emp_cod->emp_code,
                        'member_type' => 'TL',
                        'is_active' => 'Y',
                        'added_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                    if ($k) {

                        // $team_id = $request->team_id;
                        $input = $request->all();
                        $input = $request->input('emp_id');

                        $m = 0;
                        foreach ($input as $in) {
                            $empid = Employee_official_detail::where('emp_code', $in)->first();
                            if ($empid->emp_id != $request->team_leader) {
                                $m = Employee_team::insert([
                                    'team_id' => $team_id,
                                    'emp_id' => $empid->emp_id,
                                    'emp_code' => $in,
                                    'member_type' => 'M',
                                    'is_active' => 'Y',
                                    'added_by' => Auth::user()->id,
                                    'updated_by' => Auth::user()->id,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now()
                                ]);
                                if ($m) {
                                    $h = Employee_official_detail::where('emp_code', $in)->update($updateDetails);
                                }
                            }
                        }
                        if ($m) {
                            DB::commit();
                            return Redirect()->back()->with('success', "New Team added successfully");
                        } else {
                            DB::rollBack();
                            return Redirect()->back()->with('error', "Member team error");
                        }
                    } else {
                        DB::rollBack();
                        return Redirect()->back()->with('error', "team employee error");
                    }
                } else {
                    DB::rollBack();
                    return Redirect()->back()->with('error', "Offcial error");
                }
            } else {
                DB::rollBack();
                return Redirect()->back()->with('error', "Error team creation");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('error', "Error team creation. $e");
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
    public function edit(Request $request)
    {

        return Team::join('employee_personal_details', 'employee_personal_details.id', 'teams.team_leader')
            ->select('teams.*', 'employee_personal_details.subtitle', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname')
            ->where('employee_personal_details.is_active', 'Y')
            ->where('teams.id', $request->id)
            ->get();
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
        try {
            // print_r($request->all());exit();

            $teamLeader = $request->team_leader;
            $teamId = $request->team_id;

            // to check the the TL and member are choosen same
            $tl_emp_code = Employee_personal_detail::where('id', $teamLeader)->first();
            $team_emp_code = $request->input('emp_id');
            if ($team_emp_code != null) {
                foreach ($team_emp_code as $team) {
                    if ($team == $tl_emp_code->emp_code) {
                        // print_r("tl and emp is same");
                        return Redirect()->back()->with('error', "TL and the TEAM MEMBER not to choose same");
                        exit();
                    } // else{ print_r("not same"); }
                }
            }
            // to check the TL already exists
            $teamQuery = Team::where('team_leader', $teamLeader) //to check TL
                ->whereNotIn('id', [$teamId]);
            $tlMQuery = Employee_team::where('emp_id', $teamLeader) //to check TL&M
                ->whereIn('member_type', ['TL', 'M'])
                ->whereNotIn('team_id', [$teamId])
                ->where('is_active', 'Y');
            $mQuery = Employee_team::where('emp_id', $teamLeader) //to check M
                ->whereIn('member_type', ['M'])
                ->where('team_id', $teamId)
                ->where('is_active', 'Y');

            $check_tl_exists = $teamQuery->get();
            $check_tl_m_exists = $tlMQuery->get();
            $check_m_exists = $mQuery->get();

            if (!$check_tl_exists->isEmpty()) {
                // echo "TL already exists";
                return Redirect()->back()->with('error', 'TL already exists in other teams');
                exit();
            }
            if (!$check_tl_m_exists->isEmpty()) {
                // echo "TL/M already exists";
                return Redirect()->back()->with('error', 'TL/Member already exists in other teams');
                exit();
            }
            if (!$check_m_exists->isEmpty()) {
                // echo "M already exists";
                return Redirect()->back()->with('error', 'This Member cannot be a TL for this team');
                exit();
            }
            // else{ echo "no TL found"; }
            // print_r($check_tl_m_exists);


            DB::beginTransaction();
            $id = $request->team_id;

            $updateDetails = [
                'team' => $request->get('team'),
                // 'team_leader' => $request->get('team_leader'),
                'description' => $request->get('description'),
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
            $t = Team::where('id', $id)->update($updateDetails);

            // print_r($request->all());exit();
            // exit();
            if ($t) {
                $input = $request->all();
                $input = $request->input('emp_id');
                $i = 0;
                $j = 0;
                if ($input != null) {
                    foreach ($input as $in) {
                        $emp = Employee_personal_detail::where('emp_code', $in)->first();
                        $emp_id = $emp->id;
                        $emp_code = $in;

                        $i = Employee_team::insert([
                            'team_id' => $id,
                            'emp_id' => $emp_id,
                            'emp_code' => $emp_code,
                            'member_type' => 'M',
                            'is_active' => 'Y',
                            'added_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                        if ($i) {
                            $updateDetails = [
                                'team_id' => $id,
                                'updated_by' => Auth::user()->id,
                                'updated_at' => Carbon::now()
                            ];
                            $j = Employee_official_detail::where('emp_id', $emp_id)->update($updateDetails);
                        }
                    }

                    if ($i) {
                        if ($j) {
                            DB::commit();
                            return Redirect()->back()->with('success', "Team name updated successfully");
                        } else {
                            DB::rollBack();
                            return Redirect()->back()->with('error', "Official details updated unsuccessfully");
                        }
                    } else {
                        DB::rollBack();
                        return Redirect()->back()->with('error', "Team members updated unsuccessfully");
                    }
                } else {
                    DB::commit();
                    return Redirect()->back()->with('success', "Team  updated successfully");
                }
            } else {
                DB::rollBack();
                return Redirect()->back()->with('error', "Team details updated unsuccessfully");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('error', "Exception $e");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $team_id = $request->team_id;
        // $input = $request->all();
        $input = $request->input('emp_id');
        if ($input) {
            // print_r($input);exit();
            foreach ($input as $in) {
                $emp_id = Employee_personal_detail::where('emp_code', $in)->first();
                $id = $emp_id->id;
                $updateDetails = [
                    'is_active' => 'N',
                    'updated_at' => Carbon::now(),
                ];
                $i = Employee_team::where('emp_code', $in)
                    ->update($updateDetails);
                if ($i) {
                    $updateDetails_o = ['team_id' => null];
                    $j = Employee_official_detail::where('emp_code', $in)->update($updateDetails_o);
                    if ($j) {
                        // $updateDetails_t = ['team_leader' => 0];
                        // $k = DB::table('teams')->where('id', $team_id)->update($updateDetails_t);
                        return Redirect()->back()->with('success', "Team members updated successfully");
                    }
                }
            }
        }
        return Redirect()->back()->with('error', "No members choosen to remove");
    }

    // export team starts
    public function exportteammember(Request $request)
    {
        if ($request->has('export')) {
            $teams = Team::join('employee_personal_details', 'employee_personal_details.id', 'teams.team_leader')
            ->select('teams.*', 'employee_personal_details.subtitle', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname')
            ->get();
            // print_r($teams);exit();
            $filename = 'team.xlsx';
            $response = Excel::download(new TeamMemberExport($teams), $filename);
            ob_end_clean();
            return $response;
        }
    }
}
