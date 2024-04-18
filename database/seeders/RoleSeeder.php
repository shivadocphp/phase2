<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $data = [
        //     [
        //         'name' => 'Superadmin',
        //         'guard_name' => 'web'
        //     ],
        //     [
        //         'name' => 'admin',
        //         'guard_name' => 'web'
        //     ],
        //     [
        //         'name' => 'HRM',
        //         'guard_name' => 'web'
        //     ],
        //     [
        //         'name' => 'SR HR',
        //         'guard_name' => 'web'
        //     ],
        //     [
        //         'name' => 'admin2',
        //         'guard_name' => 'web'
        //     ]
        // ];
        // Role::insert($data);

        // option 2
        $roleNames = ['Superadmin', 'admin', 'HRM','SR HR','admin2'];

        // Loop through each role name and create the role
        foreach ($roleNames as $roleName) {
            Role::create(['name' => $roleName]);
        }
    }
}
