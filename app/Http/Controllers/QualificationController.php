<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use App\Models\Qualificationlevel;

use App\Models\Qualification;
use App\Models\States;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $qualifications = Qualification::join('qualificationlevels','qualificationlevels.id','qualificationlevel_id')
            ->select('qualifications.*','qualificationlevels.qualificationlevel')
            ->paginate(5);
        $qlevels = Qualificationlevel::all();
        return view('admin.qualification.index',compact('qualifications','qlevels'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'qualification' => 'required',
            'qualificationlevel' => 'required',
        ]);

        Qualification::insert([
            'qualification'=>$request->qualification,
            'qualificationlevel_id'=>$request->qualificationlevel,
        ]);
        return Redirect()->back()->with('success',"Qualification inserted successfully");
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
            'qualificationlevel_id' => $request->get('qualificationlevel'),
            'qualification' => $request->get('qualification')
        ];
        Qualification::where('id', $qid)->update($updateDetails);
        return Redirect()->back()->with('success', "Qualification updated successfully");
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
