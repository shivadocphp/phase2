<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequirementStoreRequest;
use App\Models\Client_address;
use App\Models\Client_basic_details;
use App\Models\Client_requirement;
use App\Models\Designation;
use App\Models\Qualification;
use App\Models\Qualificationlevel;
use App\Models\Skill;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Yajra\DataTables\DataTables;
use App\Models\Client_official;


use App\Exports\RequirementExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Notifications\NewRequirementNotification;

class Client_RequirementController extends Controller
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
        return view('requirement.index', compact('clients'));
    }

    public function hold()
    {
        return view('requirement.hold');
    }

    public function prospect()
    {
        return view('requirement.prospect');
    }

    public function closedJSC()
    {
        return view('requirement.closedJSC');
    }

    public function closedClient()
    {
        return view('requirement.closedClient');
    }

    public function fetchClientlocation(Request $request)
    {
        // return $request->all();
        $data['location'] = Client_address::join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->where("client_id", $request->client_id)
            ->get(['client_addresses.id as id', 'client_addresses.address as address', 'states.state as state', 'cities.city as city']);
        $agreed = Client_official::where('client_id', $request->client_id)->select('agreed1', 'agreed2', 'agreed3', 'agreed4')->first();
        // return $data;
        $data['agreed'][] = $agreed->agreed1;
        $data['agreed'][] = $agreed->agreed2;
        $data['agreed'][] = $agreed->agreed3;
        $data['agreed'][] = $agreed->agreed4;
        return response()->json($data);
    }

    public function getActiveRequirements(Request $request)
    {

        if ($request->input('export')) {
            // Export the data
            return $this->exportrequirement($request);
        }

        $client_id = null;
        if ($request->ajax()) {
            $client_id = $request->client_id;
            $client = Client_requirement::join('users', 'users.id', 'client_requirements.added_by')
                ->join('client_basic_details', 'client_basic_details.id', 'client_requirements.client_id')
                ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
                ->join('states', 'states.id', 'client_addresses.state_id')
                ->join('cities', 'cities.id', 'client_addresses.city_id')
                ->join('designations', 'designations.id', 'client_requirements.position')
                ->select(
                    'client_requirements.id as id',
                    'client_requirements.requirement_status',
                    'client_requirements.added_by',
                    'users.name',
                    'client_basic_details.client_code',
                    'client_basic_details.company_name',
                    'client_addresses.address as address',
                    'states.state as state',
                    'cities.city as city',
                    'designations.designation',
                    'client_requirements.total_position',
                    'client_requirements.skills',
                    'client_requirements.created_at'
                )
                ->where('client_requirements.requirement_status', 'Active')
                ->where('client_requirements.deleted_at', NULL);

            if ($client_id != null) {
                if ($client_id != "all") {
                    $client = $client->where('client_requirements.client_id', $client_id);
                }
            }
            $client = $client->orderBy('client_requirements.id', 'desc')
                ->get();

            $clientCount = $client->count();
            // print_r($clientCount);exit();


            $each_client = new Collection();
            foreach ($client as $key => $value) {
                $tenure = '-';
                $skills = null;
                if ($value->skills != null) {
                    $sk = json_decode($value->skills);
                    if ($sk != null) {
                        for ($i = 0; $i < count($sk); $i++) {
                            $getskill = Skill::find($sk[$i])->skill;
                            // print_r($getskill);
                            if ($skills != null) {
                                $skills = $skills . "," . $getskill;
                            } else {
                                $skills = $getskill;
                            }
                        }
                    }
                }

                $added_on = null;
                if ($value->created_at != null) {

                    $a = explode(" ", $value->created_at);
                    $added_on = Carbon::parse($a[0])->format('d-m-Y');
                }
                $user = User::find(Auth::user()->id);
                $action = null;
                if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Requirement')) {
                    $action = '<a href="' . route('show.requirement', [$value->id]) . '" ><i class="fa fa-eye" style="color: black"></i></a>
                                <a href="' . route('edit.requirement', [$value->id]) . '" ><i class="fa fa-edit" style="color: green"></i></a>
                                <a href="' . route('destroy.requirement', [$value->id]) . '"><i class="fa fa-trash" style="color: red"></i></a>
                                <div class="btn-group dropright"><a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" ></i></a>
                                    <div class="dropdown-menu">
                                        <p align="center">Change Status</p>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Hold']) . '">Hold</a>
                                            <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Prospect']) . '">Prospect</a>
                                            <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Closed By client']) . '">Closed By client</a>
                                            <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Closed By JSC']) . '">Closed By JSC</a>
                                    </div>
                                </div>';
                } else {
                    $action = '<a href="' . route('show.requirement', [$value->id]) . '" class="btn btn-sm btn-info">View</a>';
                }
                if ($value->requirement_status == 'Active') {
                    $each_client->push([
                        'client_code' => $value->client_code . '-' . $value->company_name,
                        'position' => $value->designation,
                        'vacancy' => $value->total_position,
                        'location' => $value->address . '-' . $value->city . '-' . $value->state,
                        'skills' => $skills,
                        'details' => '',
                        'added_on' => $added_on,
                        // 'added_by' => $value->name,
                        'action' => $action
                    ]);
                }
            }
            return DataTables::of($each_client)->addIndexColumn()->rawColumns(['action'])->make(true);
        }
    }

    public function getHoldRequirements(Request $request)
    {
        if ($request->input('export')) {
            // Export the data
            return $this->exportrequirement($request);
        }

        if ($request->ajax()) {
            $client = Client_requirement::join('users', 'users.id', 'client_requirements.added_by')
                ->join('client_basic_details', 'client_basic_details.id', 'client_requirements.client_id')
                ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
                ->join('states', 'states.id', 'client_addresses.state_id')
                ->join('cities', 'cities.id', 'client_addresses.city_id')
                ->join('designations', 'designations.id', 'client_requirements.position')
                ->select(
                    'client_requirements.id as id',
                    'client_requirements.requirement_status',
                    'client_requirements.added_by',
                    'users.name',
                    'client_basic_details.client_code',
                    'client_basic_details.company_name',
                    'client_addresses.address as address',
                    'states.state as state',
                    'cities.city as city',
                    'designations.designation',
                    'client_requirements.total_position',
                    'client_requirements.skills',
                    'client_requirements.created_at'
                )
                ->where('client_requirements.requirement_status', 'Hold')
                ->where('client_requirements.deleted_at', NULL)
                ->orderBy('client_requirements.id', 'desc')
                ->get();


            $each_client = new Collection();
            foreach ($client as $key => $value) {
                $tenure = '-';
                $user = User::find(Auth::user()->id);
                $action = null;
                $skills = null;
                if ($value->skills != null) {
                    $sk = json_decode($value->skills);
                    if ($sk != null) {
                        for ($i = 0; $i < count($sk); $i++) {
                            $getskill = Skill::find($sk[$i])->skill;
                            // print_r($getskill);
                            if ($skills != null) {
                                $skills = $skills . "," . $getskill;
                            } else {
                                $skills = $getskill;
                            }
                        }
                    }
                }
                $added_on = null;
                if ($value->created_at != null) {

                    $a = explode(" ", $value->created_at);
                    $added_on = Carbon::parse($a[0])->format('d-m-Y');
                }
                if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Requirement')) {
                    $action = '<a href="' . route('show.requirement', [$value->id]) . '" ><i class="fa fa-eye" style="color: black"></i></a>
                                        <a href="' . route('edit.requirement', [$value->id]) . '" ><i class="fa fa-edit" style="color: green"></i></a>
                                        <a href=""><i class="fa fa-trash" style="color: red"></i></a>
                                         <div class="btn-group dropright"><a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i></a>
                                        <div class="dropdown-menu">
                                         <p align="center">Change Status</p>
                                           <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Hold']) . '">Hold</a>
                    <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Prospect']) . '">Prospect</a>
                      <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Closed By client']) . '">Closed By client</a>
                        <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Closed By JSC']) . '">Closed By JSC</a>

                </div></div>
                                       ';
                } else {
                    $action = '<a href="' . route('show.requirement', [$value->id]) . '" class="btn btn-sm btn-info">View</a>';
                }
                if ($value->requirement_status == 'Hold') {
                    $each_client->push([
                        'client_code' => $value->client_code . '-' . $value->company_name,
                        'position' => $value->designation,
                        'vacancy' => $value->total_position,
                        'location' => $value->address . '-' . $value->city . '-' . $value->state,
                        'skills' => $skills,
                        'details' => '',
                        'added_on' => $added_on,
                        'action' => $action
                    ]);
                }
            }
            return DataTables::of($each_client)->addIndexColumn()->rawColumns(['action'])->make(true);
        }
    }

    public function getProspectRequirements(Request $request)
    {

        if ($request->input('export')) {
            // Export the data
            return $this->exportrequirement($request);
        }

        if ($request->ajax()) {
            $client = Client_requirement::join('users', 'users.id', 'client_requirements.added_by')
                ->join('client_basic_details', 'client_basic_details.id', 'client_requirements.client_id')
                ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
                ->join('states', 'states.id', 'client_addresses.state_id')
                ->join('cities', 'cities.id', 'client_addresses.city_id')
                ->join('designations', 'designations.id', 'client_requirements.position')
                ->select(
                    'client_requirements.id as id',
                    'client_requirements.requirement_status',
                    'client_requirements.added_by',
                    'users.name',
                    'client_basic_details.client_code',
                    'client_basic_details.company_name',
                    'client_addresses.address as address',
                    'states.state as state',
                    'cities.city as city',
                    'designations.designation',
                    'client_requirements.total_position',
                    'client_requirements.skills',
                    'client_requirements.created_at'
                )
                ->where('client_requirements.requirement_status', 'Prospect')
                ->where('client_requirements.deleted_at', NULL)
                ->orderBy('client_requirements.id', 'desc')
                ->get();


            $each_client = new Collection();
            foreach ($client as $key => $value) {
                $added_on = null;
                if ($value->created_at != null) {

                    $a = explode(" ", $value->created_at);
                    $added_on = Carbon::parse($a[0])->format('d-m-Y');
                }
                $tenure = '-';
                $skills = null;
                if ($value->skills != null) {
                    $sk = json_decode($value->skills);
                    if ($sk != null) {
                        for ($i = 0; $i < count($sk); $i++) {
                            $getskill = Skill::find($sk[$i])->skill;
                            // print_r($getskill);
                            if ($skills != null) {
                                $skills = $skills . "," . $getskill;
                            } else {
                                $skills = $getskill;
                            }
                        }
                    }
                }
                $user = User::find(Auth::user()->id);
                $action = null;
                if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Requirement')) {
                    $action = '<a href="' . route('show.requirement', [$value->id]) . '" ><i class="fa fa-eye" style="color: black"></i></a>
                                        <a href="' . route('edit.requirement', [$value->id]) . '" ><i class="fa fa-edit" style="color: green"></i></a>
                                        <a href=""><i class="fa fa-trash" style="color: red"></i></a>
                                         <div class="btn-group dropright"><a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i></a>
                                        <div class="dropdown-menu">
                                         <p align="center">Change Status</p>
                                           <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Hold']) . '">Hold</a>
                    <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Prospect']) . '">Prospect</a>
                      <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Closed By client']) . '">Closed By client</a>
                        <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Closed By JSC']) . '">Closed By JSC</a>

                </div></div>
                                       ';
                } else {
                    $action = '<a href="' . route('show.requirement', [$value->id]) . '" class="btn btn-sm btn-info">View</a>';
                }
                if ($value->requirement_status == 'Prospect') {
                    $each_client->push([

                        'client_code' => $value->client_code . '-' . $value->company_name,
                        'position' => $value->designation,
                        'vacancy' => $value->total_position,
                        'location' => $value->address . '-' . $value->city . '-' . $value->state,
                        'skills' => $skills,
                        'details' => '',
                        // 'position' =>$value->designation .'-'.$value->total_position,
                        'added_on' => $added_on,
                        'action' => $action
                    ]);
                }
            }
            return DataTables::of($each_client)->addIndexColumn()->rawColumns(['action'])->make(true);
        }
    }

    public function getClosedJSCRequirements(Request $request)
    {
        if ($request->input('export')) {
            // Export the data
            return $this->exportrequirement($request);
        }

        if ($request->ajax()) {
            $client = Client_requirement::join('users', 'users.id', 'client_requirements.added_by')
                ->join('client_basic_details', 'client_basic_details.id', 'client_requirements.client_id')
                ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
                ->join('states', 'states.id', 'client_addresses.state_id')
                ->join('cities', 'cities.id', 'client_addresses.city_id')
                ->join('designations', 'designations.id', 'client_requirements.position')
                ->select(
                    'client_requirements.id as id',
                    'client_requirements.requirement_status',
                    'client_requirements.added_by',
                    'users.name',
                    'client_basic_details.client_code',
                    'client_basic_details.company_name',
                    'client_addresses.address as address',
                    'states.state as state',
                    'cities.city as city',
                    'designations.designation',
                    'client_requirements.total_position',
                    'client_requirements.skills',
                    'client_requirements.created_at'
                )
                // ->where('client_requirements.requirement_status', 'Closed by Company')
                ->where('client_requirements.requirement_status', 'Closed By JSC')
                ->where('client_requirements.deleted_at', NULL)
                ->orderBy('client_requirements.id', 'desc')
                ->get();


            $each_client = new Collection();
            foreach ($client as $key => $value) {
                $added_on = null;
                if ($value->created_at != null) {

                    $a = explode(" ", $value->created_at);
                    $added_on = Carbon::parse($a[0])->format('d-m-Y');
                }
                $tenure = '-';
                $skills = null;
                if ($value->skills != null) {
                    $sk = json_decode($value->skills);
                    if ($sk != null) {
                        for ($i = 0; $i < count($sk); $i++) {
                            $getskill = Skill::find($sk[$i])->skill;
                            // print_r($getskill);
                            if ($skills != null) {
                                $skills = $skills . "," . $getskill;
                            } else {
                                $skills = $getskill;
                            }
                        }
                    }
                }
                $user = User::find(Auth::user()->id);
                $action = null;
                if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Requirement')) {
                    $action = '<a href="' . route('show.requirement', [$value->id]) . '" ><i class="fa fa-eye" style="color: black"></i></a>
                                <a href="' . route('edit.requirement', [$value->id]) . '" ><i class="fa fa-edit"></i></a>
                                <a href="' . route('store_again.requirement', [$value->id]) . '"><i class="fa fa-plus-circle" style="color: green"></i></a>
                                       ';
                } else {
                    $action = '<a href="' . route('show.requirement', [$value->id]) . '" class="btn btn-sm btn-info">View</a>';
                }
                // if ($value->requirement_status == 'Closed by Company') {
                if ($value->requirement_status == 'Closed By JSC') {
                    $each_client->push([
                        'client_code' => $value->client_code . '-' . $value->company_name,
                        'position' => $value->designation,
                        'vacancy' => $value->total_position,
                        'location' => $value->address . '-' . $value->city . '-' . $value->state,
                        'skills' => $skills,
                        'details' => '',
                        'added_on' => $added_on,
                        'action' => $action
                    ]);
                }
            }
            return DataTables::of($each_client)->addIndexColumn()->rawColumns(['action'])->make(true);
        }
    }

    public function getClosedClientRequirements(Request $request)
    {
        if ($request->input('export')) {
            // Export the data
            return $this->exportrequirement($request);
        }

        if ($request->ajax()) {
            $client = Client_requirement::join('users', 'users.id', 'client_requirements.added_by')
                ->join('client_basic_details', 'client_basic_details.id', 'client_requirements.client_id')
                ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
                ->join('states', 'states.id', 'client_addresses.state_id')
                ->join('cities', 'cities.id', 'client_addresses.city_id')
                ->join('designations', 'designations.id', 'client_requirements.position')
                ->select(
                    'client_requirements.id as id',
                    'client_requirements.requirement_status',
                    'client_requirements.added_by',
                    'users.name',
                    'client_basic_details.client_code',
                    'client_basic_details.company_name',
                    'client_addresses.address as address',
                    'states.state as state',
                    'cities.city as city',
                    'designations.designation',
                    'client_requirements.total_position',
                    'client_requirements.skills',
                    'client_requirements.created_at'
                )
                // ->where('client_requirements.requirement_status', 'Closed By client')
                ->where('client_requirements.requirement_status', 'Closed By client')
                ->where('client_requirements.deleted_at', NULL)
                ->orderBy('client_requirements.id', 'desc')
                ->get();


            $each_client = new Collection();
            foreach ($client as $key => $value) {
                $added_on = null;
                if ($value->created_at != null) {

                    $a = explode(" ", $value->created_at);
                    $added_on = Carbon::parse($a[0])->format('d-m-Y');
                }
                $tenure = '-';
                $skills = null;
                if ($value->skills != null) {
                    $sk = json_decode($value->skills);
                    if ($sk != null) {
                        for ($i = 0; $i < count($sk); $i++) {
                            $getskill = Skill::find($sk[$i])->skill;
                            // print_r($getskill);
                            if ($skills != null) {
                                $skills = $skills . "," . $getskill;
                            } else {
                                $skills = $getskill;
                            }
                        }
                    }
                }
                $user = User::find(Auth::user()->id);
                $action = null;
                if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Requirement')) {
                    $action = '<a href="' . route('show.requirement', [$value->id]) . '" ><i class="fa fa-eye" style="color: black"></i></a>
                                        <a href="' . route('edit.requirement', [$value->id]) . '" ><i class="fa fa-edit" ></i></a>
                                        <a href="' . route('store_again.requirement', [$value->id]) . '"><i class="fa fa-plus-circle" style="color: green"></i></a>
                                       ';
                } else {
                    $action = '<a href="' . route('show.requirement', [$value->id]) . '" class="btn btn-sm btn-info">View</a>';
                }
                // if ($value->requirement_status == 'Closed by Client') {
                if ($value->requirement_status == 'Closed By client') {
                    $each_client->push([

                        'client_code' => $value->client_code . '-' . $value->company_name,
                        'position' => $value->designation,
                        'vacancy' => $value->total_position,
                        'location' => $value->address . '-' . $value->city . '-' . $value->state,
                        'skills' => $skills,
                        'details' => '',
                        //  'position' =>$value->designation .'-'.$value->total_position,
                        'added_on' => $added_on,
                        'action' => $action
                    ]);
                }
            }
            return DataTables::of($each_client)->addIndexColumn()->rawColumns(['action'])->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $client = Client_basic_details::where('client_status', 'Active')->get();
        $designation = Designation::all();
        $qlevel = Qualificationlevel::all();
        $skills = Skill::all();
        return view('requirement.create', compact('client', 'designation', 'qlevel', 'skills', 'id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required',
            'location' => 'required',
            'agreed' => 'required',
            'position' => 'required',
            'total_position' => 'required|integer',
            'min_years' => 'required|integer',
            'max_years' => 'required|integer',
            'salary_min' => 'required|integer',
            'salary_max' => 'required|integer',
            'requirement_status' => 'required',
            'skills' => 'required',
            'jd' => 'required'
        ]);

        // to check the requirement for the client for a particular location and position already exists
        $check_req_exists = Client_requirement::where('client_id', $request->client_id)
            ->where('location', $request->location)
            ->where('position', $request->position)
            ->get();
        if (!$check_req_exists->isEmpty()) {
            // print_r("requirement having duplicacy for the client with position & location");
            return redirect()->route('create.requirement')->with('error', "There is duplicacy requirement noted for this client! Duplicacy reason may be position & location are same");
        } else {
            // print_r("requirement not have duplicacy! save it");

            $client_requirement = $request->except(['_token',]);
            $client_requirement['skills'] = json_encode($request->skills);
            $client_requirement['added_by'] = Auth::user()->id;
            $client_requirement['created_at'] = Carbon::now();
            // print_r($client_requirement);
            $id = Client_requirement::insertGetId($client_requirement);
            if ($id > 0) {

                // requirement notification
                $users = $this->getUsersWithPermission('Requirement Notification');
                if ($users) {
                    $get_requirement = Client_requirement::find($id);
                    // print_r($get_client);exit();
                    // return $users;
                    foreach ($users as $user) {
                        $notification = new NewRequirementNotification($get_requirement);
                        $user->notify($notification);
                    }
                }


                // $require = Client_requirement::find($id);
                // return view('requirement.edit_conditions', compact('id', 'require'));
                return redirect()->route('edit.requirement_con', $id)->with('success', 'Requirement Added successfully.');
            }
        }
    }

    public function edit_client_require($client_id)
    {
        $requirements = Client_requirement::join('users', 'users.id', 'client_requirements.added_by')
            ->join('client_basic_details', 'client_basic_details.id', 'client_requirements.client_id')
            ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
            ->join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->join('designations', 'designations.id', 'client_requirements.position')
            ->select(
                'client_requirements.id as id',
                'client_requirements.requirement_status',
                'client_requirements.added_by',
                'users.name',
                'client_basic_details.client_code',
                'client_basic_details.company_name',
                'client_addresses.address as address',
                'states.state as state',
                'cities.city as city',
                'designations.designation',
                'client_requirements.total_position',
                'client_requirements.skills',
                'client_requirements.created_at'
            )
            ->where('client_requirements.deleted_at', NULL)
            ->where('client_requirements.client_id', $client_id)
            ->orderBy('client_requirements.id', 'desc')
            ->get();
        $location = Client_address::join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->select('client_addresses.id', 'client_addresses.address', 'states.state', 'cities.city')
            ->where('client_addresses.client_id', $client_id)
            ->get();
        $agreed = Client_official::where('client_id', $client_id)->select('agreed1', 'agreed2', 'agreed3', 'agreed4')->first();
        $agreed_array = array();
        if (isset($agreed)) {
            $agreed_array[] = $agreed->agreed1;
            $agreed_array[] = $agreed->agreed2;
            $agreed_array[] = $agreed->agreed3;
            $agreed_array[] = $agreed->agreed4;
        }

        $designation = Designation::all();
        $qlevel = Qualificationlevel::all();
        $skills = Skill::all();
        $client = Client_basic_details::where('id', $client_id)->first();

        // print_r($client_id);exit();
        return view('Client.edit_requirement', compact('client', 'client_id', 'location', 'qlevel', 'skills', 'designation', 'agreed_array'));
    }

    public function getRequirements(Request $request, $id)
    {
        if ($request->ajax()) {
            $client = Client_requirement::join('users', 'users.id', 'client_requirements.added_by')
                ->join('client_basic_details', 'client_basic_details.id', 'client_requirements.client_id')
                ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
                ->join('states', 'states.id', 'client_addresses.state_id')
                ->join('cities', 'cities.id', 'client_addresses.city_id')
                ->join('designations', 'designations.id', 'client_requirements.position')
                ->select(
                    'client_requirements.id as id',
                    'client_requirements.requirement_status',
                    'client_requirements.added_by',
                    'users.name',
                    'client_basic_details.client_code',
                    'client_basic_details.company_name',
                    'client_addresses.address as address',
                    'states.state as state',
                    'cities.city as city',
                    'designations.designation',
                    'client_requirements.total_position',
                    'client_requirements.skills',
                    'client_requirements.created_at'
                )
                ->where('client_requirements.deleted_at', NULL)
                ->where('client_requirements.client_id', $id)
                ->orderBy('client_requirements.id', 'desc')
                ->get();

            $each_client = new Collection();
            foreach ($client as $key => $value) {
                $tenure = '-';
                $skills = null;
                if ($value->skills != null) {
                    $sk = json_decode($value->skills);
                    if ($sk != null) {
                        for ($i = 0; $i < count($sk); $i++) {
                            $getskill = Skill::find($sk[$i])->skill;
                            // print_r($getskill);
                            if ($skills != null) {
                                $skills = $skills . "," . $getskill;
                            } else {
                                $skills = $getskill;
                            }
                        }
                    }
                }

                $added_on = null;
                if ($value->created_at != null) {

                    $a = explode(" ", $value->created_at);
                    $added_on = Carbon::parse($a[0])->format('d-m-Y');
                }
                $user = User::find(Auth::user()->id);
                $action = null;
                if ($value->requirement_status == 'Closed by Client' || $value->requirement_status == 'Closed by JSC') {
                    $action = $action . '<a href="' . route('store_again.requirement', [$value->id]) . '" title="Add again"><i class="fa fa-plus-circle" style="color: skyblue"></i></a>';
                }
                if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Requirement')) {
                    $action = '<a href="' . route('show.requirement', [$value->id]) . '" ><i class="fa fa-eye" style="color: black"></i></a>
                                        <a href="' . route('edit.requirement', [$value->id]) . '" ><i class="fa fa-edit" style="color: green"></i></a>  '
                        . $action .
                        '  <a href="' . route('destroy.requirement', [$value->id]) . '"><i class="fa fa-trash" style="color: red"></i></a>
                                         <div class="btn-group dropright"><a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" ></i></a>
                                        <div class="dropdown-menu">

                                         <p align="center">Change Status</p>
                                           <div class="dropdown-divider"></div>
                                           <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Active']) . '">Active</a>
                    <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Hold']) . '">Hold</a>
                    <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Prospect']) . '">Prospect</a>
                      <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Closed By client']) . '">Closed By client</a>
                        <a class="dropdown-item" href="' . route('status.requirement', [$value->id, 'Closed By JSC']) . '">Closed By JSC</a>

                </div></div>
                                       ';
                } else {
                    $action = '<a href="' . route('show.requirement', [$value->id]) . '" class="btn btn-sm btn-info">View</a>';
                }

                $each_client->push([

                    'client_code' => $value->client_code . '-' . $value->company_name,
                    'position' => $value->designation,
                    'vacancy' => $value->total_position,
                    'location' => $value->address . '-' . $value->city . '-' . $value->state,
                    'skills' => $skills,
                    'added_on' => $added_on,
                    'status' => $value->requirement_status,
                    // 'added_by' => $value->name,
                    'action' => $action
                ]);
            }
            return DataTables::of($each_client)->addIndexColumn()->rawColumns(['action'])->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */



    public function store_again($id)
    {
        $requirement = Client_requirement::find($id);
        if ($requirement != null) {
            $insert = $requirement->replicate();
            $i = $insert->save();
            if ($i > 0) {
                return \redirect()->route('edit.requirement', $insert->id);
            }
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

        $require = Client_requirement::join('users', 'users.id', 'client_requirements.added_by')
            ->join('client_basic_details', 'client_basic_details.id', 'client_requirements.client_id')
            ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
            ->join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->join('designations', 'designations.id', 'client_requirements.position')
            ->join('qualificationlevels', 'qualificationlevels.id', 'client_requirements.quali_level_id')
            ->leftjoin('qualifications', 'qualifications.id', 'client_requirements.quali_id')
            ->select(
                'client_requirements.id',
                'client_requirements.min_years',
                'client_requirements.max_years',
                'client_requirements.matriculation',
                'client_requirements.plustwo',
                'client_requirements.salary_min',
                'client_requirements.salary_max',
                'client_requirements.cab_facility',
                'client_requirements.hiring_radius',
                'client_requirements.role_type',
                'client_requirements.employement_type',
                'client_requirements.domain',
                'client_requirements.skills',
                'client_requirements.jd',
                'client_requirements.targeted_companies',
                'client_requirements.nonpatch_companies',
                'client_requirements.interview_rounds',
                'client_requirements.open_till',
                'client_requirements.no_consultant',
                'client_requirements.bond',
                'client_requirements.bond_years',
                'client_requirements.tat',
                'client_requirements.updated_by',
                'client_requirements.requirement_status',
                'client_requirements.added_by',
                'users.name',
                'client_basic_details.client_code',
                'client_basic_details.company_name',
                'client_addresses.address as address',
                'states.state as state',
                'cities.city as city',
                'designations.designation',
                'client_requirements.total_position',
                'qualificationlevels.qualificationlevel',
                'qualifications.qualification'
            )
            ->where('client_requirements.id', $id)
            ->first();

        //dd($require);
        return view('requirement.show', compact('require', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client_basic_details::where('client_status', 'Active')->get();
        $designation = Designation::all();
        $qlevel = Qualificationlevel::all();
        $requirement = Client_requirement::find($id);
        $client_address = Client_address::join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->select('client_addresses.id as id', 'client_addresses.address as address', 'states.state', 'cities.city')
            ->where('client_addresses.client_id', $requirement->client_id)->get();
        $agreed = Client_official::where('client_id', $requirement->client_id)->select('agreed1', 'agreed2', 'agreed3', 'agreed4')->first();
        $agreed_array = array();
        if (isset($agreed)) {
            $agreed_array[] = $agreed->agreed1;
            $agreed_array[] = $agreed->agreed2;
            $agreed_array[] = $agreed->agreed3;
            $agreed_array[] = $agreed->agreed4;
        }

        $qualification = Qualification::where('qualificationlevel_id', $requirement->quali_level_id)->get();
        $skills = Skill::all();
        return view('requirement.edit', compact('id', 'client', 'skills', 'designation', 'qlevel', 'requirement', 'client_address', 'qualification', 'agreed_array'));
    }

    public function edit_condition($id)
    {
        $require = Client_requirement::find($id);
        return view('requirement.edit_conditions', compact('id', 'require'));
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
        // print_r($request->all());exit();
        $id = $request->requirement_id;
        if ($request->save == "save_conditon") {
            $updateDetails = [
                'targeted_companies' => $request->targeted_companies,
                'nonpatch_companies' => $request->nonpatch_companies,
                'interview_rounds' => $request->interview_rounds,
                'open_till' => $request->open_till,
                'no_consultant' => $request->no_consultant,
                'bond' => $request->bond,
                'bond_years' => $request->bond_years,
                'tat' => $request->tat,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ];
            $i = Client_requirement::where('id', $id)->update($updateDetails);
            if ($i > 0) {
                // return view('requirement.index')->with('success', 'Requirement updated successfully');
                return redirect()->route('all.requirement')->with('success', "Requirement updated successfully");
            } else {
                return back()->with('error', 'Requirement conditions updated unsuccessfully');
            }
        } else {   // for update position

            // to check the requirement for the client for a particular location and position already exists
            $check_req_exists = Client_requirement::where('client_id', $request->client_id)
                ->where('location', $request->location)
                ->where('position', $request->position)
                // ->where('id',$id)
                ->whereNotIn('id', [$id])
                ->get();
            if (!$check_req_exists->isEmpty()) {
                return Redirect()->back()->with('error', 'There is duplicacy requirement noted for this client! Duplicacy reason may be position & location are same');
            } else {

                $client_requirement = $request->except(['_token', 'save', '_method', 'requirement_id']);;
                // print_r($client_requirement);exit();
                $client_requirement['updated_by'] = Auth::user()->id;
                $client_requirement['skills'] = json_encode($request->skills);
                $client_requirement['updated_at'] = Carbon::now();
                $i = Client_requirement::where('id', $id)->update($client_requirement);
                if ($i > 0) {
                    $i = $id;
                    return Redirect()->back()->with('success', 'Updated details successfully');
                } else {
                    return Redirect()->back()->with('error', 'Updated details unsuccessfully');
                }
            }
        }
    }

    public function change_status($id, $status)
    {
        $update_status = [
            'requirement_status' => $status,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now()
        ];
        $i = Client_requirement::where('id', $id)->update($update_status);
        if ($i > 0) {
            return Redirect()->back()->with('success', 'Changed status successfully');
        } else {
            return Redirect()->back()->with('error', 'Changed status unsuccessfully');
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
        $updatedetails = [
            'deleted_by' => Auth::user()->id,
            'deleted_at' => Carbon::now()
        ];
        $i = Client_requirement::where('id', $id)->update($updatedetails);
        if ($i > 0) {
            return Redirect()->back()->with('success', 'Deleted successfully');
        } else {
            return Redirect()->back()->with('error', 'Deleted unsuccessfully');
        }
    }


    // export client starts
    public function exportrequirement(Request $request)
    {
        // print_r($request->all());
        //     exit();
        if ($request->has('export')) {
            $requirements = Client_requirement::join('users', 'users.id', 'client_requirements.added_by')
                ->join('client_basic_details', 'client_basic_details.id', 'client_requirements.client_id')
                ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
                ->join('states', 'states.id', 'client_addresses.state_id')
                ->join('cities', 'cities.id', 'client_addresses.city_id')
                ->join('designations', 'designations.id', 'client_requirements.position')
                ->select(
                    'client_requirements.id as id',
                    'client_requirements.requirement_status',
                    'client_requirements.added_by',
                    'users.name',
                    'client_basic_details.client_code',
                    'client_basic_details.company_name',
                    'client_addresses.address as address',
                    'states.state as state',
                    'cities.city as city',
                    'designations.designation',
                    'client_requirements.total_position',
                    'client_requirements.skills',
                    'client_requirements.created_at'
                );


            if ($request->has('client_id') && $request->client_id != '') {

                if ($request->client_id != "all") {
                    $requirements = $requirements->where('client_requirements.client_id', $request->client_id);
                }
            }
            // $requirements = $requirements->orderBy('client_requirements.id', 'desc')
            // ->get();



            if ($request->export == 'active') {
                $requirements = $requirements->where('client_requirements.requirement_status', 'Active');
                $filename = 'requirements_active.xlsx';
                // ->orwhere('users.is_active','Y')
            } elseif ($request->export == 'prospect') {
                $requirements = $requirements->where('client_requirements.requirement_status', 'Prospect');
                $filename = 'requirements_prospect.xlsx';
            } elseif ($request->export == 'hold') {
                $requirements = $requirements->where('client_requirements.requirement_status', 'Hold');
                $filename = 'requirements_hold.xlsx';
            } elseif ($request->export == 'closed_by_client') {
                $requirements = $requirements->where('client_requirements.requirement_status', 'Closed By client');
                $filename = 'requirements_close_by_client.xlsx';
            } elseif ($request->export == 'closed_by_company') {
                $requirements = $requirements->where('client_requirements.requirement_status', 'Closed by Company');
                $filename = 'requirements_close_by_company.xlsx';
            } else {
                // Default filename if export parameter is not provided
                $filename = 'employees.xlsx';
            }
            // ->where('client_requirements.requirement_status', 'Active')
            $requirements = $requirements->where('client_requirements.deleted_at', NULL);

            // $requirements = $requirements->get();



            $response = Excel::download(new RequirementExport($requirements->orderBy('client_requirements.id', 'DESC')->get()), $filename);

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
