<?php

namespace App\Http\Controllers;

use App\Models\LoginImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher;
use App\Models\Setting;

class LoginImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $login_images = LoginImage::paginate(5);
        $settings = Setting::first();
        return view('admin.site.index', compact('login_images', 'settings'));
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
        $request->validate([
            'login_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'current_image' => 'required',
        ]);
        $folderpath         = 'images/login';
        //$file_name_original = $request->file('login_image')->getClientOriginalName();
        $extension          = $request->file('login_image')->getClientOriginalExtension();
        $file_name          = 'Image_' . date('Y-m-d') . '_' . time() . "." . $extension;
        //$path = $request->file('login_image')->store('public/'.$folderpath);
        $request->file('login_image')->storeAs('public/' . $folderpath, $file_name);
        $file_stored_path   = $folderpath . '/' . $file_name;

        $post = new LoginImage();
        $post->current_image = $request->current_image;
        $post->image_location = $file_stored_path;
        $post->save();

        return back()->with('success', 'Image has been added successfully.');
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
    public function update($id)
    {

        $updateDetails = [
            'current_image' => 'Y',
        ];
        $updateDetails1 = [
            'current_image' => 'N',
        ];
        $i = LoginImage::where('id', $id)->update($updateDetails);
        if ($i) {
            $j = LoginImage::where('id', '!=', $id)
                ->update($updateDetails1);
            if ($j) {
                return Redirect()->back()->with('success', "Login Image updated successfully");
            } else {
                return Redirect()->back()->with('error', "Login image upadtion error");
            }
        } else {
            return Redirect()->back()->with('error', "Not able to set as login image");
        }
    }

    public function updatesettings(Request $request)
    {

        $emailAddresses = explode(',', $request->email_send_to);



        // Sample JSON array of email addresses
        // $jsonEmailAddresses = '["phpdeveloper@gmail.com","shiva@gmail.com","test@gmail.com"]';
        // // Decode JSON string into a PHP array
        // $emailAddresses = json_decode($jsonEmailAddresses, true);
        // // Add one more email address to the array
        // $newEmailAddress = "example@example.com";
        // $emailAddresses[] = $newEmailAddress;
        // // Encode the array back into JSON format
        // $newJsonEmailAddresses = json_encode($emailAddresses);




        $updateDetails = [
            'late_mark_at' => $request->late_mark_at,
            'email_send_to' => $emailAddresses,
        ];
        $i = Setting::where('id', 1)->update($updateDetails);
        if ($i) {
            return Redirect()->back()->with('success', "Login Image updated successfully");
        } else {
            return Redirect()->back()->with('error', "Not able to set as login image");
        }
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
