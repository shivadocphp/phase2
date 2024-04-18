<?php

namespace App\Http\Controllers;

use App\Models\CandidateDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Validator;

class CandidateDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function processed(Request $request, $id, $file)
    {
        // print_r($file);exit();
        if ($request->ajax()) {
            $candidateDetail = CandidateDetail::join('users', 'users.id', 'candidate_details.added_by')
                ->join('candidate_basic_details', 'candidate_basic_details.id', 'candidate_details.candidate_id')
                ->join('client_basic_details', 'client_basic_details.id', 'candidate_details.client_id')
                ->join('client_requirements', 'client_requirements.id', 'candidate_details.requirement_id')
                ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
                ->join('states', 'states.id', 'client_addresses.state_id')
                ->join('cities', 'cities.id', 'client_addresses.city_id')
                ->join('designations', 'designations.id', 'client_requirements.position')
                ->select(
                    'candidate_details.id',
                    'client_basic_details.client_code',
                    'client_basic_details.company_name',
                    'designations.designation',
                    'states.state',
                    'cities.city',
                    'client_addresses.address',
                    'candidate_details.call_status',
                    'candidate_details.comments',
                    'users.name',
                    'candidate_details.added_by',
                    'candidate_basic_details.candidate_name',
                    'candidate_details.requirement_id',
                    'candidate_details.client_id',
                    'candidate_details.interview_mode',
                    'candidate_details.available_time',
                    'candidate_details.call_back_date',
                    'candidate_details.call_back_time',
                    'candidate_details.call_back_status',
                    'candidate_details.requirement_status',
                    'candidate_details.call_status',
                    'candidate_details.comments'
                )
                ->where('candidate_details.candidate_id', $id)
                ->where('candidate_details.deleted_at', NULL)
                ->orderBy('candidate_details.id', 'desc')
                ->get();
            // print_r($candidateDetail);exit();
            $each_process = new Collection();
            foreach ($candidateDetail as $key => $value) {
                $user = User::find(Auth::user()->id);

                $processed = null;
                $requirement = $value->designation . " , " . $value->address . "," . $value->city . "," . $value->state;
                $client = $value->client_code . " - " . $value->company_name;
                $action = '<a href="#" class="showModal" data-myrequirement="' . $requirement . '"data-myclient="' . $client . '"
                        data-myinterview_mode="' . $value->interview_mode . '"   data-myavailable_time="' . $value->available_time . '"   data-mycall_back_date="' . $value->call_back_date . '"
                        data-mycall_back_time="' . $value->call_back_time . '"   data-mycall_back_status="' . $value->call_back_status . '"
                        data-mycall_status="' . $value->call_status . '"   data-mycomments="' . $value->comments . '" data-myrequirement_status="' . $value->requirement_status . '"
                        data-myid="' . $value->id . '" data-toggle="modal" data-target="#showModal" placeholder="View Detail">
                        <i class="fa fa-eye" style="color: black"></i></a>
                                         ';
                // if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Resume')) {
                    if ($file == 'edit') {
                        if (Auth::user()->id == $value->added_by) {
                            $action = $action . '<a href="#" class="editModal" data-myrequirement="' . $requirement . '"data-myclient="' . $client . '"
                                data-myinterview_mode="' . $value->interview_mode . '"   data-myavailable_time="' . $value->available_time . '"   data-mycall_back_date="' . $value->call_back_date . '"
                                data-mycall_back_time="' . $value->call_back_time . '"   data-mycall_back_status="' . $value->call_back_status . '"
                                data-mycall_status="' . $value->call_status . '"   data-mycomments="' . $value->comments . '" data-myrequirement_status="' . $value->requirement_status . '"
                                data-myid="' . $value->id . '" data-toggle="modal" data-target="#editModal" placeholder="Edit"><i class="fa fa-edit" style="color: green"></i></a>
                                <a href="' . route('destroy.candidatedetail', [$value->id]) . '" ><i class="fa fa-trash" style="color: red"></i></a>';
                        }
                    }
                // }
                $each_process->push([
                    'requirement' => $requirement,
                    'client' => $client,
                    'call_status' => $value->call_status,
                    'requirement_status' => $value->requirement_status,
                    'processed_by' => $value->name,
                    'comments' => $value->comments,
                    'action' => $action
                ]);
            }
            return DataTables::of($each_process)->addIndexColumn()->rawColumns(['action'])->make(true);
        }
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*  $validator = Validator::make($request->all(), [
            'requirement_id' => 'required',
            'client_id' => 'required',
            'interview_mode' => 'required',
            'available_time' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }*/
        $id = $request->candidate_id;
        $name = $request->candidate_name;
        $check = CandidateDetail::where('requirement_id', $request->requirement_id)->where('candidate_id', $id)->get();

        if (count($check) != 0) {
            return redirect()->route('edit_detail.candidate', [$id, $name])->with('error', 'Already Exists! Candidate already processed with the same requirements');
        } else {
            $input = $request->except(['_token', 'candidate_name']);
            $input['added_by'] = Auth::user()->id;
            $input['created_at'] = Carbon::now();
            $i = CandidateDetail::insertGetId($input);

            if ($i > 0) {
                return redirect()->route('edit_detail.candidate', [$id, $name])->with('success', 'Added Successfully');
            } else {
                return redirect()->back()->with('error', 'Added unuccessfully');
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
        //
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
        $name = $request->candidate_name;
        $id = $request->candidate_id;
        $rowid = $request->id;
        $input = $request->except(['_token', 'candidate_name', 'candidate_id', '_method', 'id']);
        $input['updated_by'] = Auth::user()->id;
        $input['updated_at'] = Carbon::now();
        $i = CandidateDetail::where('id', $rowid)->update($input);
        if ($i > 0) {
            return redirect()->route('edit_detail.candidate', [$id, $name])->with('success', 'Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Added unsuccessfully');
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
        $exists = CandidateDetail::find($id);
        if ($exists) {
            $updateDetails = [
                'deleted_by' => Auth::user()->id,
                'deleted_at' => Carbon::now()
            ];
            $i = CandidateDetail::where('id', $id)->update($updateDetails);
            if ($i) {
                return redirect()->back()->with('success', 'Deleted successfully');
            } else {
                return redirect()->back()->with('error', 'Deleted unuccessfully');
            }
        } else {
            return redirect()->back()->with('error', 'No such value exists');
        }
    }
}
