<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::paginate(5);
        return view('admin.country.index', compact('countries'));
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
            'code' => 'required|unique:countries|max:255',
            'country' => 'required|unique:countries|max:255',
            'phonecode' => 'required|unique:countries|max:10',
        ]);

        Country::insert([
            'code' => $request->code,
            'country' => $request->country,
            'phonecode' => $request->phonecode,
        ]);
        return Redirect()->back()->with('success', "Country inserted successfully");
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
        $country_id = $request->country_id;

        $updateDetails = [
            'code' => $request->get('code'),
            'country' => $request->get('country'),
            'phonecode' => $request->get('phonecode')
        ];
        Country::where('id', $country_id)->update($updateDetails);
        return Redirect()->back()->with('success', "Country updated successfully");
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
