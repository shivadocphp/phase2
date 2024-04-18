<?php

namespace App\Http\Controllers;

use App\Models\client_agreement;
use App\Models\Client_basic_details;
use App\Models\Client_official;
use App\Models\ServiceType;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class Client_OfficialController extends Controller
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
        //
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
        $service_type = ServiceType::all();
        $client = Client_basic_details::where('id', $id)->first();
        $client_off = Client_official::where('client_id', $id)->first();
        return view('Client.edit_client_official', compact('client', 'client_off', 'service_type'));
    }

    public function update(Request $request, $id)
    {
        // print_r($request->all());exit();
        $updatedetails = [

            'service_type' => $request->service_type,
            'date_empanelment' => $request->date_empanelment,
            'date_renewal' => $request->date_renewal,
            'freezing_period' => $request->freezing_period,
            'rehire_policy' => $request->rehire_policy,
            'profile_validity' => $request->profile_validity,
            'callback_date' => $request->callback_date,
            'callback_time' => $request->callback_time,
            'agreed1' => $request->agreed1,
            'agreed2' => $request->agreed2,
            'agreed3' => $request->agreed3,
            'agreed4' => $request->agreed4,
            'payment1' => $request->payment1,
            'payment2' => $request->payment2,
            'payment3' => $request->payment3,
            'payment4' => $request->payment4,
            'replacement1' => $request->replacement1,
            'replacement2' => $request->replacement2,
            'replacement3' => $request->replacement3,
            'replacement4' => $request->replacement4,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ];
        $i = Client_official::where('client_id', $id)->update($updatedetails);


        if ($i) {
            return Redirect()->back()->with('success', 'Official details updated successfully');
        } else {
            return Redirect()->back()->with('error', 'Official details updated unsuccessfully');
        }
    }

    public function edit_agreement($id)
    {
        $client = Client_basic_details::where('id', $id)->first();
        $client_off = client_agreement::where('client_id', $id)->where('active', 'Y')->get();
        // $client_off = client_agreement::where('client_id', $client->id)->where('active','Y')->get();
        // print_r($client_off);exit();
        return view('Client.upload_agreement', compact('client', 'client_off'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update_agreement(Request $request, $id)
    {
        // print_r($request->all());exit();
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048', // pdf and maximum size of 2MB
        ]);

        $agreement = null;
        if ($request->file()) {
            $fileName = $request->file('file')->getClientOriginalName();
            $folderpath = 'clients';
            $request->file('file')->storeAs('public/' . $folderpath, $fileName);
            $agreement = $folderpath . '/' . $fileName;
        }
        $updatedetails = [
            'client_id' => $id,
            'agreement' => $agreement,
            'active' => 'Y',
            'added_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ];
        $i = client_agreement::insert($updatedetails);
        if ($i) {
            return Redirect()->back()->with('success', 'Agreement updated successfully');
        } else {
            return Redirect()->back()->with('error', 'Official details updated unsuccessfully');
        }
    }

    public function delete_agreement($id)
    {
        $updateDetails = ['active' => 'N', 'updated_by' => Auth::user()->id, 'updated_at' => Carbon::now(),];
        $i = client_agreement::where('id', $id)->update($updateDetails);
        if ($i) {
            return Redirect::back()->with('success', "Agreement deleted Successfully");
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
}
