<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        // all the roles
        $roleNames = ['Superadmin', 'Admin', 'HRM','SR HR','Manager'];
        // Loop through each role name and create the role
        foreach ($roleNames as $roleName) {
            Role::create(['name' => $roleName]);
        }


        // all the permission
        Permission::create(['name' => 'Add Employees']);
        Permission::create(['name' => 'Edit Employees']);
        Permission::create(['name' => 'View Employees']);
        Permission::create(['name' => 'Delete Employees']);
        Permission::create(['name' => 'Generate Employee Code']);

        Permission::create(['name' => 'Add Attendance']);
        Permission::create(['name' => 'Edit Attendance']);
        Permission::create(['name' => 'View Own Attendance']);
        Permission::create(['name' => 'View All Attendance']);
        Permission::create(['name' => 'Monthly Attendance Report']);

        Permission::create(['name' => 'Manage Team']);

        Permission::create(['name' => 'Add Leave']);
        Permission::create(['name' => 'Update Leave']);
        Permission::create(['name' => 'Mark Leave']);
        Permission::create(['name' => 'View Own Leave']);
        Permission::create(['name' => 'View All Leave']);

        
        Permission::create(['name' => 'Add Client']);
        Permission::create(['name' => 'Edit Client']);
        Permission::create(['name' => 'View Client']);
        
        Permission::create(['name' => 'Add Requirement']);
        Permission::create(['name' => 'Edit Requirement']);
        Permission::create(['name' => 'View Requirement']);  

        Permission::create(['name' => 'View Client Allocation']);
        Permission::create(['name' => 'Add Client Allocation']);
        Permission::create(['name' => 'Delete Client Allocation']);

        Permission::create(['name' => 'View Requirement Allocation']);
        Permission::create(['name' => 'Add Requirement Allocation']);
        Permission::create(['name' => 'Edit Requirement Allocation']);
        Permission::create(['name' => 'Delete Requirement Allocation']);
        
        Permission::create(['name' => 'View Candidates']);
        Permission::create(['name' => 'Add Candidate']);
        Permission::create(['name' => 'Edit Candidate']);
        Permission::create(['name' => 'Edit Candidate Resume']);
        Permission::create(['name' => 'Process Candidate']);
        Permission::create(['name' => 'Bulk Upload Candidate']);
        Permission::create(['name' => 'Track Recruiter']);
        Permission::create(['name' => 'Track Client']);  

        Permission::create(['name' => 'Add Invoice']);
        Permission::create(['name' => 'Edit Invoice']);
        Permission::create(['name' => 'Share Invoice']);
        
        Permission::create(['name' => 'Manage Payroll']);

        Permission::create(['name' => 'Manage Policies']);
        
        Permission::create(['name' => 'Manage Performance']);
        Permission::create(['name' => 'Performance Assessment']);
        Permission::create(['name' => 'Performance Review']);    

        Permission::create(['name' => 'Manage user privilege']); 

        Permission::create(['name' => 'Manage Settings']);

        Permission::create(['name' => 'Manage Report']);

        Permission::create(['name' => 'Employee Notification']);
        Permission::create(['name' => 'Leave Notification']); 
        Permission::create(['name' => 'Client Notification']); 
        Permission::create(['name' => 'Requirement Notification']); 
        Permission::create(['name' => 'Allocation Notification']); 
        Permission::create(['name' => 'Candidate Notification']); 
        Permission::create(['name' => 'Invoice Notification']); // check ok
        

        $adminRole = Role::where('name', 'Superadmin')->first();
        $permissions = Permission::all();
        // Assign all permissions to the Admin3 role
        $adminRole->syncPermissions($permissions->pluck('name')->toArray());
        
        // $adminRole->givePermissionTo([
        //     'create-users',
        //     'edit-users',
        //     'delete-users',
        //     'create-blog-posts',
        //     'edit-blog-posts',
        //     'delete-blog-posts',
        // ]);
        
        
// to create and give a role individually

        // $editorRole = Role::create(['name' => 'Editor']);
        // $editorRole->givePermissionTo([
        //     'create-blog-posts',
        //     'edit-blog-posts',
        //     'delete-blog-posts',
        // ]);

    }
}
