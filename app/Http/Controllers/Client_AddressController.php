<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Client_address;
use App\Models\Client_basic_details;
use App\Models\Country;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class Client_AddressController extends Controller
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
        // print_r($request->all());exit();
        $validated = $request->validate([
            'client_id' => 'required|max:255',
            'address' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'country_id' => 'required',
            'pincode' => 'required',
            'start_mon_year' => 'required',
            'address_type' => 'required',
        ]);
        $data = array();
        $data['client_id'] = $request->client_id;
        $data['address'] = $request->address;
        $data['state_id'] = $request->state_id;
        $data['city_id'] = $request->city_id;
        $data['country_id'] = $request->country_id;
        $data['pincode'] = $request->pincode;
        $data['start_mon_year'] = $request->start_mon_year;
        $data['gst'] = $request->gst;
        $data['address_type'] = $request->address_type;
        $data['added_by'] = Auth::user()->id;
        $data['created_at'] = Carbon::now();
        $data['active'] = 'Y';
        $i = Client_address::insertGetId($data);
        if ($i) {
            return Redirect()->back()->with('success', "Client Address added successfully");
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
        $client = Client_basic_details::where('id', $id)->first();
        $client_address = Client_address::where('client_id', $id)->where('active', 'Y')->get();
        $i = 0;
        if ($client_address != null) {
            $i = 1;
        }
        $country = Country::all();
        $state = State::all();
        $city = City::all();
        return view('Client.edit_client_address', compact('state', 'client', 'i', 'client_address', 'country'));
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
        /*  $validated = $request->validate([

            'address' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'country_id' => 'required',
            'pincode' => 'required',
            'start_mon_year' => 'required',
            'address_type' => 'required',
        ]);*/
        $data = array();
        // $client_id = $request->client_id;

        $k = $request->loopid;

        $data['address'] = $request->address;
        $data['state_id'] = $request->state;
        $data['city_id'] = $request->city;
        $data['country_id'] = $request->country_id;
        $data['pincode'] = $request->pincode;
        $data['start_mon_year'] = $request->start_mon_year;
        $data['gst'] = $request->gst;
        $data['address_type'] = $request->address_type;
        $data['updated_by'] = Auth::user()->id;
        $data['updated_at'] = Carbon::now();
        $data['active'] = 'Y';
        $i = Client_address::where('id', $id)->update($data);
        if ($i) {
            return Redirect::back()->with('success', "Address updated Successfully");
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
        $updateDetails = ['active' => 'N'];
        $i = Client_address::where('id', $id)->update($updateDetails);
        if ($i) {

            return Redirect::back()->with('success', "Address deleted Successfully");
        }
    }
}
