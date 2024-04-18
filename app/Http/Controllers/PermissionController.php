<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    //
    public function index()
    {
        $permission = Permission::paginate(5);
        return view('admin.permission.index', compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'permission' => 'required',
        ]);
        $permission = Permission::create(['name' => $request->input('permission')]);
        return Redirect()->back()->with('success', 'Permission created Successfully');
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $updateDetails = [
            'name' => $request->get('permission')
        ];
        $i = Permission::where('id', $id)->update($updateDetails);
        return Redirect()->back()->with('success', "Permission updated successfully");
    }
}
