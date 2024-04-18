<?php

namespace App\Http\Controllers;

use App\Models\Client_basic_details;
use App\Models\Client_requirement;
use App\Models\ClientAllocation;
use App\Models\Designation;
use App\Models\Employee_team;
use App\Models\Qualificationlevel;
use App\Models\Task;
use App\Models\Team;
use App\Models\Team_member;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;


use App\Exports\AllocationExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Notifications\NewAllocationNotification;


class Client_allocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client_basic_details::where('client_status', 'Active')
            ->orderBy('id')
            ->get();
        return view('allocation.index', compact('clients'));
    }

    // new
    public function getAllocation(Request $request)
    {

        if ($request->input('export')) {
            // Export the data
            return $this->exportallocation($request);
        }


        $client_id = null;
        try {
            if ($request->ajax()) {

                $client_id = $request->client_id;

                // old (grpup by client not working)
                $client = ClientAllocation::join('users', 'users.id', 'client_allocations.added_by')
                    ->join('client_basic_details', 'client_basic_details.id', 'client_allocations.client_id')
                    ->select('client_allocations.id as id', 'client_allocations.client_id', 'client_allocations.team_id', 'client_allocations.added_by', 'users.name', 'client_basic_details.client_code', 'client_basic_details.company_name')
                    ->where('client_allocations.deleted_at', NULL);
                if ($client_id != null) {
                    if ($client_id != "all") {
                        $client = $client->where('client_allocations.client_id', $client_id);
                    }
                }
                $client = $client->orderBy('client_allocations.id')
                    // ->groupBy('client_allocations.client_id')   //temporary commented
                    ->get();


                //new  (not working)
                // $client = DB::table('client_allocations')
                //     ->join('users', 'users.id', 'client_allocations.added_by')
                //     ->join('client_basic_details', 'client_basic_details.id', 'client_allocations.client_id')
                //     ->select('client_allocations.client_id', DB::raw('COUNT(client_allocations.id) as allocation_count'), DB::raw('GROUP_CONCAT(users.name) as added_by_names'), 'client_basic_details.client_code', 'client_basic_details.company_name')
                //     ->whereNull('client_allocations.deleted_at');
                //     if($client_id!=null){
                //         if($client_id!="all"){
                //             $client = $client->where('client_allocations.client_id',$client_id);
                //         }
                //     }
                // $client = $client->groupBy('client_allocations.client_id', 'client_basic_details.client_code', 'client_basic_details.company_name')
                //     ->orderBy('client_allocations.client_id')->get();

                $each_client = new Collection();
                foreach ($client as $key => $value) {
                    $teams = null;
                    /* getting teams*/
                    $team_ids = $value->team_id;
                    $team_ids = explode(",", $team_ids);
                    foreach ($team_ids as $t) {
                        $clientteam = Team::where('id', $t)
                            ->get();
                        foreach ($clientteam as $ct) {
                            if ($teams != null) {
                                $teams = $teams . ", " . $ct->team;
                            } else {
                                $teams = $ct->team;
                            }
                        }
                    }
                    /*getting requirements*/
                    $requirements = null;
                    $total = 0;
                    $client_requirements = Client_requirement::join('designations', 'designations.id', 'client_requirements.position')
                        ->select('client_requirements.total_position', 'designations.designation')
                        ->where('client_requirements.client_id', $value->client_id)
                        ->get();
                    foreach ($client_requirements as $cr) {
                        $total = $total + $cr->total_position;
                        if ($requirements != null) {
                            $requirements = $requirements . ", " . $cr->designation . "(" . $cr->total_position . ")";
                        } else {
                            $requirements =  $cr->designation . "(" . $cr->total_position . ")";
                        }
                    }
                    $tenure = '-';
                    $user = User::find(Auth::user()->id);
                    $action = null;
                    if (Auth::user()->id == 1 || $user->hasPermissionTo('Add Client Allocation')) {
                        $action = '<a href="' . route('show.allocation', [$value->id]) . '" ><i class="fa fa-eye black"></i></a>
                                       <a href="' . route('allocate_task.allocation', [$value->id]) . '" title="Allocate requirements to team members"><i class="fa fa-sitemap green"></i></a>
                                        <a href="' . route('destroy.allocation', [$value->id]) . '" title="Delete Allocation"><i class="fa fa-trash red"></i></a>
                                       ';
                    } else {
                        $action = '<a href="' . route('show.requirement', [$value->id]) . '" class="btn btn-sm btn-info">View</a>';
                    }

                    $each_client->push([

                        'client_code' => $value->client_code . '-' . $value->company_name,
                        'requirements' => $requirements,
                        'no' => $total,
                        'team' => $teams,
                        'action' => $action
                    ]);
                }
                return DataTables::of($each_client)->addIndexColumn()->rawColumns(['action'])->make(true);
            }
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return Redirect()->back()->with('error', "$e. Generate Employee Code and Edit details");
        }
    }

    // old
    // public function getAllocation(Request $request)
    // {
    //     $client_id=null;

    //     if ($request->ajax()) {

    //         $client_id = $request->client_id;

    //         $client = DB::table('client_allocations')
    //             ->join('users', 'users.id', 'client_allocations.added_by')
    //             ->join('client_basic_details', 'client_basic_details.id', 'client_allocations.client_id')
    //             ->select('client_allocations.id as id', 'client_allocations.client_id','client_allocations.team_id','client_allocations.added_by', 'users.name', 'client_basic_details.client_code', 'client_basic_details.company_name')
    //             ->where('client_allocations.deleted_at', NULL);
    //             if($client_id!=null){
    //                 if($client_id!="all"){
    //                     $client = $client->where('client_allocations.client_id',$client_id);
    //                 }
    //             }
    //             $client = $client->orderBy('client_allocations.id')
    //             ->groupBy('client_allocations.client_id')->get();

    //         $each_client = new Collection();
    //         foreach ($client as $key => $value) {
    //             $teams = null;
    //             /* getting teams*/
    //             $team_ids = $value->team_id;
    //             $team_ids = explode(",",$team_ids);
    //             foreach($team_ids as $t) {
    //                 $clientteam = DB::table('teams')
    //                     ->where('id', $t)
    //                     ->get();
    //                 foreach ($clientteam as $ct) {
    //                     if ($teams != null) {
    //                         $teams = $teams . ", " . $ct->team;
    //                     } else {
    //                         $teams = $ct->team;
    //                     }
    //                 }
    //             }
    //             /*getting requirements*/
    //             $requirements=null;
    //             $total = 0;
    //             $client_requirements = DB::table('client_requirements')
    //                 ->join('designations','designations.id','client_requirements.position')
    //                 ->select('client_requirements.total_position','designations.designation')
    //                 ->where('client_requirements.client_id',$value->client_id)
    //                 ->get();
    //             foreach($client_requirements as $cr){
    //                 $total = $total + $cr->total_position;
    //                 if($requirements!=null) {
    //                     $requirements = $requirements . ", " . $cr->designation . "(" . $cr->total_position . ")";
    //                 }
    //                 else{
    //                     $requirements =  $cr->designation . "(" . $cr->total_position . ")";
    //                 }
    //             }
    //             $tenure = '-';
    //             $user = User::find(Auth::user()->id);
    //             $action = null;
    //             if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Allocation')) {
    //                 $action = '<a href="' . route('show.allocation', [$value->id]) . '" ><i class="fa fa-eye black"></i></a>
    //                                    <a href="' . route('allocate_task.allocation', [$value->id]) . '" title="Allocate requirements to team members"><i class="fa fa-sitemap green"></i></a>
    //                                     <a href="' . route('destroy.allocation', [$value->id]) . '" title="Delete Allocation"><i class="fa fa-trash red"></i></a>
    //                                    ';

    //             } else {
    //                 $action = '<a href="' . route('show.requirement', [$value->id]) . '" class="btn btn-sm btn-info">View</a>';

    //             }

    //             $each_client->push([

    //                 'client_code' => $value->client_code . '-' . $value->company_name,
    //                 'requirements' => $requirements,
    //                 'no' => $total,
    //                 'team' => $teams,
    //                 'action' => $action
    //             ]);


    //         }
    //         return DataTables::of($each_client)->addIndexColumn()->rawColumns(['action'])->make(true);
    //     }

    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(Auth::user()->id);
        if (Auth::user()->id == 1 || $user->hasPermissionTo('Add Client Allocation')) {
            $client = Client_basic_details::where('client_status', 'Active')->get();
            $team = Team::all();
            return view('allocation.create', compact('client', 'team'));
        } else {
            return view('access_denied');
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
        $input = $request->except(['_token',]);
        $team_id = $request->team_id;
        // print_r(count($team_id));exit();
        for ($i = 0; $i < count($team_id); $i++) {
            if ($i > 0) {
                $input['team_id'] = $input['team_id'] . "," . $team_id[$i];
            } else {
                $input['team_id'] =  $team_id[$i];
            }
        }
        $input['added_by'] = Auth::user()->id;
        $input['created_at'] = Carbon::now();
        // print_r($client_requirement);
        $id = ClientAllocation::insertGetId($input);
        if ($id > 0) {

            // allocation notification
            $users = $this->getUsersWithPermission('Allocation Notification');
            if ($users) {
                $get_allocation = ClientAllocation::find($id);
                // print_r($get_client);exit();
                // return $users;
                foreach ($users as $user) {
                    $notification = new NewAllocationNotification($get_allocation);
                    $user->notify($notification);
                }
            }
            return redirect()->route('allocate_task.allocation', $id)->with('success', 'Client Allocated to the team successfully.');
        }
    }

    public function list_task($id)
    {
        $allocate = ClientAllocation::find($id);
        $allocates = ClientAllocation::join('client_basic_details', 'client_basic_details.id', 'client_allocations.client_id')
            ->join('teams', 'teams.id', 'client_allocations.team_id')
            ->select(
                'client_allocations.id as id',
                'client_basic_details.client_code',
                'client_basic_details.company_name',
                'teams.team',
                'client_allocations.client_id',
                'client_allocations.team_id'
            )
            ->where('client_allocations.id', $id)
            ->first();

        $teamIds = [];
        $team = explode(",", $allocates->team_id);
        foreach ($team as $value) {
            // Process each value here
            $teamIds[] = $value;
        }

        $team_name = Team::select('team')->whereIn('id', $teamIds)->get();
        // print_r($team_name);
        // exit();

        // print_r($allocates);exit();
        return view('allocation.allocated_task', compact('allocate', 'allocates', 'id', 'team_name'));
    }

    public function task($id)
    {
        //$allocate = ClientAllocation::find($id);

        $allocates = ClientAllocation::join('client_basic_details', 'client_basic_details.id', 'client_allocations.client_id')
            ->join('teams', 'teams.id', 'client_allocations.team_id')
            ->select(
                'client_allocations.id as id',
                'client_basic_details.client_code',
                'client_basic_details.company_name',
                'client_allocations.client_id',
                'client_allocations.team_id'
            )
            ->where('client_allocations.id', $id)
            ->first();
        // print_r($allocates);exit();
        $requirements = Client_requirement::join('designations', 'designations.id', 'client_requirements.position')
            ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
            ->join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->select(
                'client_requirements.id as id',
                'client_requirements.total_position',
                'designations.designation',
                'client_addresses.address as address',
                'states.state as state',
                'cities.city as city'
            )
            ->where('client_requirements.client_id', $allocates->client_id)
            ->where('client_requirements.requirement_status', 'Active')
            ->get();
        // print_r($requirements);exit();


        /*  $tasks = DB::table('tasks')
            //  ->join('client_allocations','client_allocations.id',' tasks.allocation_id')
            //   ->join('client_requirements','client_requirements.id',' tasks.requirement_id')
            ->join('employee_personal_details', 'employee_personal_details.id', 'tasks.employee_id')
            ->select('tasks.id', 'tasks.allocation_id', 'tasks.requirement_id', 'tasks.allocated_no', 'tasks.employee_id', 'employee_personal_details.firstname', 'employee_personal_details.middlename', 'employee_personal_details.lastname')
            ->where('tasks.deleted_by', NULL)
            ->get();*/
        //  return view('allocation.task', compact('allocate', 'tasks', 'team_mem', 'id', 'allocates'));

        $teamIds = [];
        $team = explode(",", $allocates->team_id);
        foreach ($team as $value) {
            // Process each value here
            $teamIds[] = $value;
        }

        $team_mem = Employee_team::join('employee_personal_details', 'employee_personal_details.id', 'employee_teams.emp_id')
            ->select(
                'employee_personal_details.id',
                'employee_personal_details.emp_code',
                'employee_personal_details.firstname',
                'employee_personal_details.lastname',
                'employee_personal_details.middlename'
            )
            ->whereIn('team_id', $teamIds)
            ->where('employee_teams.is_active', 'Y')
            ->get();
        // print_r($team_mem);
        // exit();
        return view('allocation.task', compact('allocates', 'id', 'requirements', 'team_mem'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $allocate = ClientAllocation::find($id);
        $allocates = ClientAllocation::join('client_basic_details', 'client_basic_details.id', 'client_allocations.client_id')
            ->join('teams', 'teams.id', 'client_allocations.team_id')
            ->select(
                'client_allocations.id as id',
                'client_basic_details.client_code',
                'client_basic_details.company_name',
                'teams.team',
                'client_allocations.client_id',
                'client_allocations.team_id'
            )
            ->where('client_allocations.id', $id)
            ->first();

        $teamIds = [];
        $team = explode(",", $allocates->team_id);
        foreach ($team as $value) {
            // Process each value here
            $teamIds[] = $value;
        }

        $team_name = Team::select('team')->whereIn('id', $teamIds)->get();
        // print_r($team_name);
        // exit();
        return view('allocation.show', compact('allocates', 'id', 'team_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        return view('edit.allocation');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        try {

            $exists = ClientAllocation::find($id);
            if ($exists) {

                $input['deleted_by'] = Auth::user()->id;
                $i = ClientAllocation::where('id', $id)
                    ->update($input);
                if ($i) {
                    $j = Task::where('allocation_id', $id)
                        ->update($input);
                }
                $allocationdelete = $exists->delete();
                $exits1 = Task::where('allocation_id', $id)->where('deleted_at', NULL)->get();
                if ($exits1) {
                    foreach ($exits1 as $tas) {
                        Task::find($tas->id)->delete();
                    }
                    return view('allocation.index')->with('success', 'Deleted');
                }
            }
        } catch (\Exception $e) {
            return view('allocation.index')->with('error', 'Error in deletion ' . $e);
        }
    }


    // export client starts
    public function exportallocation(Request $request)
    {
        // print_r($request->all());
        //     exit();
        if ($request->has('export')) {
            $allocations = ClientAllocation::join('users', 'users.id', 'client_allocations.added_by')
                ->join('client_basic_details', 'client_basic_details.id', 'client_allocations.client_id')
                ->select('client_allocations.id as id', 'client_allocations.client_id', 'client_allocations.team_id', 'client_allocations.added_by', 'users.name', 'client_basic_details.client_code', 'client_basic_details.company_name')
                ->where('client_allocations.deleted_at', NULL);

            if ($request->has('client_id') && $request->client_id != '') {
                if ($request->client_id != "all") {
                    $allocations = $allocations->where('client_allocations.client_id', $request->client_id);
                }
            }
            // $allocations = $allocations->orderBy('client_allocations.id')
            // ->get();
            // print_r($allocations);exit();

            $filename = 'allocation.xlsx';

            $response = Excel::download(new AllocationExport($allocations->orderBy('client_allocations.id', 'DESC')->get()), $filename);

            ob_end_clean();
            return $response;
        }
    }


    public function getUsersWithPermission($permissionName)
    {
        // Retrieve users with a specific permission
        $usersWithPermission = User::permission($permissionName)->get();

        return $usersWithPermission;
    }
}
