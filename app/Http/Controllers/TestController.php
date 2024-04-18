<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Collection;
use App\Exports\TestExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Mail;


class TestController extends Controller
{
    /**
     * Display the user's profile form.
     */

    // to test the datatable with export options
    public function test_users()
    {
        return view('test_users');
    }
    // for datatable
    public function test_users_list(Request $request)
    {

        if ($request->input('export')) {
            // Export the data
            return $this->export($request);
        }
       
        $users = User::select(['id', 'name', 'email', 'created_at', 'updated_at']);
        if ($request->has('user_id') && $request->user_id != '') {
            $users->where('id', $request->input('user_id'));
        }

        // $each_employee = new Collection();
        // foreach ($users as $key => $value) {

        //     $each_employee->push([
        //         'id' => $value->id,
        //         'name' => $value->name,
        //         'email' => $value->email,
        //         // 'profile'=>'',
        //         'action' => "delete"
        //     ]);

        // }
        // return DataTables::of($each_employee)->addIndexColumn()->rawColumns(['action'])->make(true);
        
        return DataTables::of($users)->addIndexColumn()->rawColumns(['action'])->make(true);
        // return DataTables::of($users)->make(true);
    }

    // for export
    public function export(Request $request)
    {
        // print_r($request->all());exit();
        // return Excel::download(new TestExport, 'users.xlsx');   // 1 option

        // 2 option
        $users = User::query();
        $users->where('is_active','Y');

        // if ($request->input('type') && $request->input('type') == 'xlsx') {
        //     $response = Excel::download(new TestExport($projects->with(['category', 'client', 'subCategory'])->orderBy('created_at', 'DESC')->get()), 'projects.xlsx',  \Maatwebsite\Excel\Excel::XLSX);
        // }
        // if ($request->input('type') && $request->input('type') == 'csv') {
        //     $response = Excel::download(new TestExport($projects->with(['category', 'client', 'subCategory'])->orderBy('created_at', 'DESC')->get()), 'timetracks.csv',  \Maatwebsite\Excel\Excel::CSV);
        // }
        // if ($request->input('type') && $request->input('type') == 'pdf') {
        //     $response = Excel::download(new TestExport($projects->with(['category', 'client', 'subCategory'])->orderBy('created_at', 'DESC')->get()), 'timetracks.pdf',  \Maatwebsite\Excel\Excel::XLSX);
        // }

        // Apply filters, if any
        if ($request->has('user_id') && $request->user_id != '') {
            $users->where('id', $request->input('user_id'));
        }

        $response = Excel::download(new TestExport($users->orderBy('created_at', 'DESC')->get()), 'users.xlsx',  \Maatwebsite\Excel\Excel::XLSX);

        ob_end_clean();
        return $response;
    }

    


    public function testmail()
    {
        $data = array(
        'name' => "Tutorials Point",
        'body' => "This is a basic testing email from Laravel."
    );
    
    Mail::send([], $data, function($message) {
        $message->from('phpdeveloper2.docllp@gmail.com', 'Virat Gandhi');
        $message->to('phpdeveloper2.docllp@gmail.com', 'Tutorials Point')->subject('Laravel Basic Testing Mail');
        $message->text('This is a basic testing email from Laravel.');
    });
    
    echo "Basic Email Sent. Check your inbox.";
    }
}
