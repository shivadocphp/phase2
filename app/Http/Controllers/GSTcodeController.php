<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Gstcode;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GSTcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gst = Gstcode::join('states','states.id','state_id')
            ->select('gstcodes.*','states.state')
            ->paginate(10);
        $countries = Country::all();
        $states = State::all();
        return view('admin.gst.index',compact('gst','countries','states'));
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
            'state' => 'required',
            'gstcode' => 'required|unique:gst',
        ]);

        Gstcode::insert([
            'state_id'=>$request->state,
            'gstcode'=>$request->gstcode,
        ]);
        return Redirect()->back()->with('success',"GST Code inserted successfully");
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
        $gid = $request->id;

        $updateDetails = [
            'state_id' => $request->get('state'),
            'gstcode' => $request->get('gstcode')
        ];
        Gstcode::where('id', $gid)->update($updateDetails);
        return Redirect()->back()->with('success', "GST Code updated successfully");
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
