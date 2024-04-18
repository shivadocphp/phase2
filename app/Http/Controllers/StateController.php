<?php

namespace App\Http\Controllers;
use App\Models\Countries;
use App\Models\Country;
use App\Models\State;
use App\Models\States;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = State::join('countries','countries.id','country_id')
            ->select('states.*','countries.country')
            ->paginate(5);
        $countries = Country::all();
        return view('admin.state.index',compact('states','countries'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'state' => 'required|unique:states|max:255',
            'country' => 'required',
        ]);

        State::insert([
            'state'=>$request->state,
            'country_id'=>$request->country,
        ]);
        return Redirect()->back()->with('success',"State inserted successfully");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $qid = $request->id;

        $updateDetails = [
            'country_id' => $request->get('country'),
            'state' => $request->get('state')
        ];
        State::where('id', $qid)->update($updateDetails);
        return Redirect()->back()->with('success', "State updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
