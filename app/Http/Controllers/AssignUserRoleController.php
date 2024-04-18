<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Employee_official_detail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class AssignUserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.userRole.index');
    }

    // old
    // public function getuserRole()
    // {
    //     /*if(Auth::user()->id==1)
    //         $user = User::where('id', '!=', 1)->get();
    //     else*/
    //         $user = User::where('id', '!=', 1)->get();

    //     $each_user = new Collection();
    //     foreach ($user as $key => $value) {
    //         $permission = null;
    //         foreach ($value->getAllPermissions() as $v) {
    //             $permission .= $v->name . ",";
    //         }
    //         $each_user->push([
    //             'emp_code' => $value->emp_code,
    //             'emp_name' => $value->name,
    //             'roles' => $permission,
    //             'action' => '<a href="' . route('edit.user_role', [$value->id]) . '"><i class="fa fa-edit" style="color:green"></i></a>',
    //         ]);
    //     }
    //     return DataTables::of($each_user)->addIndexColumn()
    //         ->rawColumns(['action'])->make(true);

    // }

    public function getuserRole()
    {
        $users = User::where('id', '!=', 1)->get();

        $each_user = new Collection();
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->implode(', ');
            $permissions = $user->getAllPermissions()->pluck('name')->implode(', ');

            $each_user->push([
                'emp_code' => $user->emp_code,
                'emp_name' => $user->name,
                'roles' => $roles,
                'permissions' => $permissions,
                'action' => '<a href="' . route('edit.user_role', [$user->id]) . '"><i class="fa fa-edit" style="color:green"></i></a>',
            ]);
        }

        return DataTables::of($each_user)->addIndexColumn()
            ->rawColumns(['action'])->make(true);
    }

    /**
     *
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::all();
        $roles = Role::all();
        $permission = Permission::all();
        return view('admin.userRole.create', compact('user', 'roles', 'permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->input('user');
        $user = User::find($id);
        // $user->assignRole($request->input('roles'));
        /*  $rolePermissions =Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
              ->where("role_has_permissions.role_id",$id)
              ->get('role_has_permissions.permission_id');
          foreach($rolePermissions as $rp){
              $per = $rp->permission_id;
              $permission = Permission::where('id',$per)->first();
              $user->syncPermissions($permission->name);
          }*/
        $user->givePermissionTo($request->input('permissions'));

        return view('admin.userRole.index')->with('success', 'User Role Assigned successfully');
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
    // old
    // public function edit($id)
    // {
    //     $user = User::find($id);
    //     /* $roles = Role::get();
    //      $userRole = DB::table('model_has_roles')->where('model_id',$id)->get();*/
    //     $emp_desig = Employee_official_detail::where('emp_code', $user->emp_code)->first();
    //     $desig = $emp_desig->designation_id;
    //     $empdesignation = Designation::where('id', $desig)->first();
    //     $designation = $empdesignation->designation;
    //     $permission = Permission::all();
    //     $userPermission = DB::table('model_has_permissions')->where('model_id', $id)->get();
    //     return view('admin.userRole.edit', compact('user', 'id', 'permission', 'userPermission', 'designation'));
    // }

    public function edit($id)
    {
        $user = User::find($id);
        $allRoles = Role::all(); // Fetch all roles
        $userRoles = $user->roles;
        // print_r($userRoles);exit();
        return view('admin.userRole.edit', compact('user', 'id', 'allRoles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    //  old
    // public function update(Request $request)
    // {
    //     $id = $request->input('user');
    //     $user = User::find($id);
    //     DB::table('model_has_permissions')->where('model_id', $id)->delete();
    //     $user->givePermissionTo($request->input('permission'));
    //     return view('admin.userRole.index')
    //         ->with('success', 'User updated successfully');
    // }

    public function update(Request $request)
    {
        // print_r($request->all());exit();
        $userId = $request->input('user');
        $user = User::findOrFail($userId);
        $roleId = $request->input('roles');
        $role = Role::findOrFail($roleId); // Fetch the role by ID

        // Check if the user already has the role
        if ($user->roles->isNotEmpty()) {
            $user->roles()->sync($role);
            // return view('admin.userRole.index')->with('success', 'User Role Changed successfully');
            return redirect()->back()->with('success', 'User roles Changed successfully');
        }
        // echo "failed";exit();
        $user->assignRole($role);
        // return view('admin.userRole.index')->with('success', 'User Role updated successfully');
        return redirect()->back()->with('success', 'User roles updated successfully');
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
