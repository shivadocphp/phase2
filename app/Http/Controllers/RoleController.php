<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    /*  function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }*/

    public function index(Request $request){
        $permission = Permission::get();
        $roles = Role::orderBy('id','DESC')->paginate(5);
        return view('roles.index',compact('roles','permission'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function store(Request  $request){
        // print_r($request->all());exit();
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
        ]);
        $role = Role::create(['name' => $request->input('name')]);
        $permissions = $request->input('permission');

        $permissions = Permission::whereIn('id',$permissions)->get();
        // $role->permissions()->sync($permissions);

        $role->syncPermissions($permissions);
        // $role->syncPermissions($request->input('permission'));
        return back()->with('success','Role created successfully');
    }

    public function show(Request $request){
        $permission = Permission::get();
        $roles = Role::orderBy('id','DESC')->paginate(5);
        return view('admin.roles.index',compact('roles','permission'))
            ->with('i', ($request->input('page', 1) - 1) * 5);

    }

    public function edit($id){
        $roles = Role::find($id);
        $permission = Permission::get();
        $rolePermissions =Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get('role_has_permissions.permission_id');
        return view('admin.roles.edit',compact('roles','rolePermissions','permission'));
    }

    public function update(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required|array',
        ]);
        $id = $request->input('id');
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        // $role->syncPermissions($request->input('permission'));

        $permissions = $request->input('permission');
        $permissions = Permission::whereIn('id',$permissions)->get();
        $role->syncPermissions($permissions);
        return Redirect()->back()->with('success','Role updated successfully');
    }

}
