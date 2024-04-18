<?php

namespace App\Http\Controllers;

use App\Models\Client_basic_details;
use App\Models\Client_address;
use App\Models\Country;
use App\Models\Designation;
use App\Models\Emp_code;
use App\Models\Industrytype;
use App\Models\Qualificationlevel;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables;
use App\Models\Client_official;

use App\Exports\ClientExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Notifications\NewClientNotification;

class Client_BasicDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Client.index');
    }
    public function getActiveClients(Request $request)
    {
        if ($request->ajax()) {
            $client = Client_basic_details::join('users', 'users.id', 'client_basic_details.added_by')
                ->select('client_basic_details.*', 'users.name')
                ->where('client_basic_details.client_status', 'Active')
                ->orderBy('id', 'desc')
                ->get();
            // print_r($client);exit();

            $each_client = new Collection();
            foreach ($client as $key => $value) {
                // print_r($value);
                $tenure = '-';
                $user = User::find(Auth::user()->id);
                $action = null;
                if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Client')) {
                    //$h = "<a href="' . route('show.client', [$value->id]) . '"><i class="fa fa-eye" style="color: black"></i></a>";
                    $action = '<a href="' . route('edit.client', [$value->id]) . '" title="edit"><i class="fa fa-edit" style="color: green"></i></a>
                                <div class="btn-group dropright">
                                    <a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="change status"><i class="fa fa-cog"></i></a>
                                    <div class="dropdown-menu"> <p align="center">Change Status</p>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="' . route('status.client', [$value->id, 'Inactive']) . '">Inactive</a>
                                        <a class="dropdown-item" href="' . route('status.client', [$value->id, 'Prospect']) . '">Prospect</a>
                                        <a class="dropdown-item" href="' . route('status.client', [$value->id, 'Blacklisted']) . '">Blacklisted</a>
                                    </div>
                                </div> ';
                } else {
                    $action = '<a href="' . route('show.client', [$value->id]) . '"><i class="fa fa-eye" style="color: black"></i></a>';
                }
                if ($value->client_status == 'Active') {
                    $each_client->push([
                        'client_code' => $value->client_code,
                        'client_name' => $value->company_name,
                        'profile' => '',
                        'added_by' => $value->name,
                        'added_on' => $value->created_at->format('Y-m-d H:i:s'),
                        'action' => $action
                    ]);
                }
            }
            // exit();
            return DataTables::of($each_client)->addIndexColumn()->rawColumns(['action'])->make(true);
        }
    }

    public function inactive()
    {
        return view('Client.inactive');
    }
    public function getInactiveClients(Request $request)
    {
        if ($request->ajax()) {
            $client = Client_basic_details::join('users', 'users.id', 'client_basic_details.added_by')
                ->select('client_basic_details.*', 'users.name')
                ->where('client_basic_details.client_status', 'Inactive')
                ->orderBy('id')
                ->get();

            $each_client = new Collection();
            foreach ($client as $key => $value) {
                $tenure = '-';
                $user = User::find(Auth::user()->id);
                $action = null;
                if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Client')) {
                    //$h = '<a href="' . route('show.client', [$value->id]) . '" ><i class="fa fa-eye" style="color: black"></i></a>';
                    $action = '<a href="' . route('edit.client', [$value->id]) . '" title="edit"><i class="fa fa-edit" style="color: green"></i></a>
                                <div class="btn-group dropright"><a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="change status"><i class="fa fa-cog"></i></a>
                                    <div class="dropdown-menu"> <p align="center">Change Status</p>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="' . route('status.client', [$value->id, 'Active']) . '">Active</a>
                                        <a class="dropdown-item" href="' . route('status.client', [$value->id, 'Prospect']) . '">Prospect</a>
                                        <a class="dropdown-item" href="' . route('status.client', [$value->id, 'Blacklisted']) . '">Blacklisted</a>
                                    </div>
                                </div>  ';
                } else {
                    $action = '<a href="' . route('show.client', [$value->id]) . '" ><i class="fa fa-eye" style="color: black"></i></a>';
                }
                if ($value->client_status == 'Inactive') {
                    $each_client->push([
                        'client_code' => $value->client_code,
                        'client_name' => $value->company_name,
                        'profile' => '',
                        'added_by' => $value->name,
                        'added_on' => $value->created_at->format('Y-m-d H:i:s'),
                        'action' => $action
                    ]);
                }
            }
            return DataTables::of($each_client)->addIndexColumn()->rawColumns(['action'])->make(true);
        }
    }

    public function prospect()
    {
        return view('Client.prospect');
    }
    public function getProspectClients(Request $request)
    {
        if ($request->ajax()) {
            $client = Client_basic_details::join('users', 'users.id', 'client_basic_details.added_by')
                ->select('client_basic_details.*', 'users.name')
                ->where('client_basic_details.client_status', 'Prospect')
                ->orderBy('id')
                ->get();

            $each_client = new Collection();
            foreach ($client as $key => $value) {
                $tenure = '-';
                $user = User::find(Auth::user()->id);
                $action = null;
                if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Client')) {
                    //$h = '<a href="' . route('show.client', [$value->id]) . '" ><i class="fa fa-eye" style="color: black"></i></a>';
                    $action = '<a href="' . route('edit.client', [$value->id]) . '" title="edit"><i class="fa fa-edit" style="color: green"></i></a>
                                <div class="btn-group dropright"><a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="change status"><i class="fa fa-cog"></i></a>
                                    <div class="dropdown-menu"> <p align="center">Change Status</p>
                                        <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="' . route('status.client', [$value->id, 'Active']) . '">Active</a>
                                            <a class="dropdown-item" href="' . route('status.client', [$value->id, 'Inactive']) . '">Inactive</a>
                                            <a class="dropdown-item" href="' . route('status.client', [$value->id, 'Blacklisted']) . '">Blacklisted</a>
                                    </div>
                                </div> ';
                } else {
                    $action = '<a href="' . route('show.client', [$value->id]) . '" ><i class="fa fa-eye" style="color: black"></i></a>';
                }
                if ($value->client_status == 'Prospect') {
                    $each_client->push([
                        'client_code' => $value->client_code,
                        'client_name' => $value->company_name,
                        'profile' => '',
                        'added_by' => $value->name,
                        'added_on' => $value->created_at->format('Y-m-d H:i:s'),
                        'action' => $action
                    ]);
                }
            }
            return DataTables::of($each_client)->addIndexColumn()->rawColumns(['action'])->make(true);
        }
    }

    public function blacklisted()
    {
        return view('Client.blacklisted');
    }
    public function getBlacklistedClients(Request $request)
    {
        if ($request->ajax()) {
            $client = Client_basic_details::join('users', 'users.id', 'client_basic_details.added_by')
                ->select('client_basic_details.*', 'users.name')
                ->where('client_basic_details.client_status', 'Blacklisted')
                ->orderBy('id')
                ->get();

            $each_client = new Collection();
            foreach ($client as $key => $value) {
                $tenure = '-';
                $user = User::find(Auth::user()->id);
                $action = null;
                if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Client')) {
                    //$h = '<a href="' . route('show.client', [$value->id]) . '" ><i class="fa fa-eye" style="color: black"></i></a>';
                    $action = '<a href="' . route('edit.client', [$value->id]) . '" title="edit"><i class="fa fa-edit" style="color: green"></i></a>
                                    <div class="btn-group dropright"><a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="change status"><i class="fa fa-cog"></i></a>
                                        <div class="dropdown-menu"> <p align="center">Change Status</p>
                                           <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="' . route('status.client', [$value->id, 'Active']) . '">Active</a>
                                            <a class="dropdown-item" href="' . route('status.client', [$value->id, 'Inactive']) . '">Inactive</a>
                                            <a class="dropdown-item" href="' . route('status.client', [$value->id, 'Prospect']) . '">Prospect</a>
                                        </div>
                                    </div>  ';
                } else {
                    $action = '<a href="' . route('show.client', [$value->id]) . '" ><i class="fa fa-eye" style="color: black"></i></a>';
                }
                if ($value->client_status == 'Blacklisted') {
                    $each_client->push([
                        'client_code' => $value->client_code,
                        'client_name' => $value->company_name,
                        'profile' => '',
                        'added_by' => $value->name,
                        'added_on' => $value->created_at->format('Y-m-d H:i:s'),
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
    public function create()
    {
        // exit();
        $designations = Designation::all();
        $industry_type = Industrytype::all();
        return view('Client.create', compact('designations', 'industry_type'));
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
        $validated = $request->validate([
            'company_name' => 'required|max:255',
            'company_emailID' => 'required',
            'company_contact_no' => 'required',
            'industry_type_id' => 'required',
            'ceo' => 'required',
            'ceo_contact' => 'required',
            'ceo_emailID' => 'required',
            'hr_spoc' => 'required',
            'hr_desig' => 'required',
            'fspoc' => 'required',
            'fspoc_designation' => 'required',
            'fspoc_email' => 'required',
            'fspoc_contact' => 'required',
            'client_status' => 'required',
            'website' => 'required',

        ]);
        DB::beginTransaction();
        try {
            $c_code = Emp_code::where('type', 'client')->get();
            $prefix = null;
            $cl_code = 0;
            $clientcode = null;
            foreach ($c_code as $e) {
                $cl_code = $e->emp_code;
                $prefix = $e->prefix;
            }
            $client_code = $cl_code + 1;
            //echo $emp_code;
            //  exit();
            if ($client_code <= 9) {
                $clientcode = $prefix . "000" . $client_code;
            } else if ($client_code <= 99) {
                $clientcode = $prefix . "00" . $client_code;
            } else if ($client_code <= 999) {
                $clientcode = $prefix . "0" . $client_code;
            } else if ($client_code <= 9999) {
                $clientcode = $prefix . "" . $client_code;
            }
            $data = array();
            $data['client_code'] = $clientcode;
            $data['company_name'] = $request->company_name;
            $data['company_emailID'] = $request->company_emailID;
            $data['company_contact_no'] = $request->company_contact_no;
            $data['industry_type_id'] = $request->industry_type_id;
            $data['ceo'] = $request->ceo;
            $data['ceo_contact'] = $request->ceo_contact;
            $data['ceo_emailID'] = $request->ceo_emailID;
            $data['hr_spoc'] = $request->hr_spoc;
            $data['hr_desig'] = $request->hr_desig;
            $data['fspoc'] = $request->fspoc;
            $data['fspoc_designation'] = $request->fspoc_designation;
            $data['fspoc_contact'] = $request->fspoc_contact;
            $data['fspoc_email'] = $request->fspoc_email;
            $data['client_status'] = $request->client_status;
            $data['website'] = $request->website;
            $data['comments'] = $request->comments;
            $data['added_by'] = Auth::user()->id;
            $data['created_at'] = Carbon::now();
            $i = Client_basic_details::insertGetId($data);

            if ($i) {
                $data1 = array();
                $data1['client_id'] = $i;
                $data1['added_by'] = Auth::user()->id;
                $data1['created_at'] = Carbon::now();
                $j = Client_official::insertGetId($data1);
                if ($j) {
                    $updateDetails = ['emp_code' => $client_code];
                    $k = Emp_code::where('type', 'client')
                        ->update($updateDetails);
                    if ($k) {
                        DB::commit();
                        // $client = Client_basic_details::where('id', $i)->first();
                        // $designations = Designation::all();
                        // $industry_type = Industrytype::all();
                        // return view('Client.edit', compact('client', 'designations', 'industry_type'))->with('success', "Client details added successfully");

                        // client notification
                        $users = $this->getUsersWithPermission('Client Notification');
                        if ($users) {
                        $get_client = Client_basic_details::find($i);
                        // print_r($get_client);exit();
                        // return $users;
                            foreach ($users as $user) {
                                $notification = new NewClientNotification($get_client);
                                $user->notify($notification);
                            }
                        }
                        return redirect()->route('edit.client', ['id' => $i])->with('success', "Client details added successfully");
                    } else {
                        DB::rollBack();
                        return Redirect()->back()->with('success', "Client details added unsuccessfully1");
                    }
                } else {
                    DB::rollBack();
                    return Redirect()->back()->with('error', "Client details added unsuccessfully2");
                }
            } else {
                DB::rollBack();
                return Redirect()->back()->with('error', "Client details added unsuccessfully3");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('error', "Client details added unsuccessfully3. $e");
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
        $client = Client_basic_details::where('id', $id)->first();
        $client_address = Client_address::where('client_id', $id)->where('active', 'Y')->get();
        $address_ext = 0;
        if ($client_address != null) {
            $address_ext = 1;
        }
        return view('Client.view', compact('client', 'id', 'client_address', 'address_ext'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client_basic_details::where('id', $id)->first();
        $designations = Designation::all();
        $industry_type = Industrytype::all();
        return view('Client.edit', compact('designations', 'industry_type', 'client'));
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
        $validated = $request->validate([
            'company_name' => 'required|max:255',
            'company_emailID' => 'required',
            'company_contact_no' => 'required',
            'industry_type_id' => 'required',
            'ceo' => 'required',
            'ceo_contact' => 'required',
            'ceo_emailID' => 'required',
            'hr_spoc' => 'required',
            'hr_desig' => 'required',
            'fspoc' => 'required',
            'fspoc_designation' => 'required',
            'fspoc_contact' => 'required',
            'fspoc_email' => 'required',
            'client_status' => 'required',
            'website' => 'required',

        ]);
        DB::beginTransaction();
        try {

            $data = array();

            $data['company_name'] = $request->company_name;
            $data['company_emailID'] = $request->company_emailID;
            $data['company_contact_no'] = $request->company_contact_no;
            $data['industry_type_id'] = $request->industry_type_id;
            $data['ceo'] = $request->ceo;
            $data['ceo_contact'] = $request->ceo_contact;
            $data['ceo_emailID'] = $request->ceo_emailID;
            $data['hr_spoc'] = $request->hr_spoc;
            $data['hr_desig'] = $request->hr_desig;
            $data['fspoc'] = $request->fspoc;
            $data['fspoc_designation'] = $request->fspoc_designation;
            $data['fspoc_contact'] = $request->fspoc_contact;
            $data['fspoc_email'] = $request->fspoc_email;

            $data['client_status'] = $request->client_status;
            $data['website'] = $request->website;
            $data['comments'] = $request->comments;
            $data['added_by'] = Auth::user()->id;
            $data['created_at'] = Carbon::now();
            $i = Client_basic_details::where('id', $id)->update($data);

            if ($i) {
                DB::commit();
                return Redirect()->back()->with('success', "Client details updated unsuccessfully");
            } else {
                DB::rollBack();
                return Redirect()->back()->with('success', "Client details updated unsuccessfully3");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('error', "Client details added unsuccessfully3. $e");
        }
    }

    public function change_status($id, $status)
    {
        // print_r($status);exit();
        $data = array();
        $data['client_status'] = $status;
        $data['updated_by'] = Auth::user()->id;
        $data['updated_at'] = Carbon::now();
        $i = Client_basic_details::where('id', $id)->update($data);
        if ($i) {
            return Redirect()->back()->with('success', 'Status Changed Successfully');
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
        //
    }


    // export client starts
    public function exportclient(Request $request)
    {
        //    print_r($request->all());exit();

        // Apply filters, if any
        if ($request->has('export')) {
            $clients = Client_basic_details::join('users', 'users.id', 'client_basic_details.added_by')
                ->select('client_basic_details.*', 'users.name')
                ->where('client_basic_details.client_status', 'Active');
            // ->orderBy('id', 'desc')
            // ->get();



            // if ($request->export == 'active') {
            // $clients->where('employee_personal_details.is_active', 'Y');
            $filename = 'clients.xlsx';
            // ->orwhere('users.is_active','Y')
            // } elseif ($request->export == 'inactive') {
            //     $clients->where('employee_personal_details.is_active', 'N');
            //     $filename = 'inactive_employees.xlsx';
            // } else {
            //     // Default filename if export parameter is not provided
            //     $filename = 'employees.xlsx';
            // }

            // $users =$users->get();
            // print_r($users);exit();

            $response = Excel::download(new ClientExport($clients->orderBy('id', 'DESC')->get()), $filename);

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
