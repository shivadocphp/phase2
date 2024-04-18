<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Expendituretype;

class ExpendituretypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $e_types =Expendituretype::paginate(5);
        //echo $subtitles;
        //exit();
        return view('admin.expendituretype.index',compact('e_types'));

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
            'expendituretype' => 'required|unique:expendituretypes|max:255',
        ]);

        $data = array();
        $data['date'] = $request->date;
        $data['note'] = $request->note;
        $data['expendituretype'] = $request->expendituretype;
        Expendituretype::insert($data);
        return Redirect()->back()->with('success',"Type of Expenditure inserted successfully");
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
        $id = $request->id;

        $updateDetails = [
            'expendituretype' => $request->get('expendituretype'),
            'date' => $request->get('date'),
            'note' => $request->get('note'),

        ];
        Expendituretype::where('id', $id)->update($updateDetails);
        return Redirect()->back()->with('success', "Expenditure type updated successfully");
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
