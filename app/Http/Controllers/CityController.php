<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Districts;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::join('states', 'states.id', 'state_id')
            ->select('cities.*', 'states.state')
            ->paginate(10);
        $countries = Country::all();
        $states = State::all();
        return view('admin.city.index', compact('cities', 'countries', 'states'));
    }

    public function dataAjax(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = City::select("id", "city")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }


    public function fetchState(Request $request)
    {
        $data['state'] = State::where("country_id", $request->country_id)->get(["state", "id"]);
        return response()->json($data);
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
        $validated = $request->validate([
            'state' => 'required',
            'district' => 'required',
        ]);

        City::insert([
            'state_id' => $request->state,
            'city' => $request->district,
        ]);
        return Redirect()->back()->with('success', "District inserted successfully");
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
        $did = $request->id;

        $updateDetails = [
            'state_id' => $request->get('state'),
            'city' => $request->get('district')
        ];
        City::where('id', $did)
            ->update($updateDetails);
        return Redirect()->back()->with('success', "District updated successfully");
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
