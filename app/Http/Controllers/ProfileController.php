<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=User::where('id',Auth::user()->id)->first();
        return view('profile',compact('user'));
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
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ]);
        $folderpath         = 'images/user';
        //$path = $request->file('profile_pic')->store('public/'.$folderpath);
        $extension          = $request->file('profile_pic')->getClientOriginalExtension();
        $file_name          = 'Image_'.Auth::user()->id.".".$extension;
        $request->file('profile_pic')->storeAs('public/'.$folderpath, $file_name);
        $file_stored_path   = $folderpath.'/'.$file_name;

        $updateDetails = ["profile_photo_path" => $file_stored_path];
        $i = DB::table('users')
            ->where('id', Auth::user()->id)
            ->update($updateDetails);

        return back()->with('success', 'Image has been updated successfully.');


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
    // public function update(ProfileUpdateRequest $request): RedirectResponse
    public function update(Request $request)
    {
        // Validate the input
    // $request->validate([
    //     'password' => 'required|min:8|confirmed', // Ensure password matches password_confirmation field
    // ]);
        $id = Auth::user()->id;
        $password = $request->get('password');
        $cpassword = $request->get('cpassword');
        if ($password == $cpassword) {
            $password = Hash::make($password);
            $updateDetails = ["password" => $password,];

            $i = DB::table('users')
                ->where('id', $id)
                ->update($updateDetails);

            if ($i) {
                return Redirect()->back()->with('success', "Password updated successfully");
            } else {
                return Redirect()->back()->with('error', "Password updation failed");
            }
        } else {
            return Redirect()->back()->with('error', "Password mismatch");
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
