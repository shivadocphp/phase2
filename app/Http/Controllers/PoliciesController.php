<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Policy;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;

class PoliciesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->id==1){
           
             $policies  = Policy::orderBy('id','DESC')->paginate(6);
          //   echo $policies;exit();
        }else{
          $policies = Policy::where('is_active',1)->orderBy('id','DESC')->paginate(6);  
        }
        return view('policy.index',compact('policies'));
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
     
        $fileName = $request->file('document')->getClientOriginalName();
        $folderpath = 'policies';
        $request->file('document')->storeAs('public/' . $folderpath, $fileName);
        $upload_path = $folderpath . '/' . $fileName;
        
 
        $save =array();
        $save['policy'] = $request->policy;
        $save['document'] = $upload_path;
        $save['is_active'] = 1;
        $save['created_by'] = Auth::user()->id;
        $save['created_at'] = Carbon::now();
        $i =  Policy::insertGetId($save);
        if($i){
            return redirect()->back()->with('success','Uploaded successfully');
            
        }else{
            return redirect()->back()->with('error','Error on uploading.Please try again');
        }
        
       
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
     	$name=null;
     	$upload_path = null;
    	if($request->file('document')!=null){

            $fileName = $request->file('document')->getClientOriginalName();
            $folderpath = 'policies';
            $request->file('document')->storeAs('public/' . $folderpath, $fileName);
            $upload_path = $folderpath . '/' . $fileName;
	        
 	    }
 	
        $save =array();
        $save['policy'] = $request->policy;
        if($upload_path!=null){
        	$save['document'] = $upload_path;
        }
        
        $save['is_active'] = 1;
        $save['updated_by'] = Auth::user()->id;
        $save['updated_at'] = Carbon::now();
       // $i =  Policy::insertGetId($save);
        $did = $request->id;

        $i= Policy::where('id', $did)->update($save);
        
        if($i){
            return redirect()->back()->with('success','Updated successfully');
            
        }else{
            return redirect()->back()->with('error','Error on updating.Please try again');
        }

    }
    
    
    public function status($id,$status){
    $save['is_active'] = $status;
    	$i = Policy::where('id', $id)->update($save);
            
         if($i){
            return redirect()->back()->with('success','Status Updated successfully');
            
        }else{
            return redirect()->back()->with('error','Error on updating status.Please try again');
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
