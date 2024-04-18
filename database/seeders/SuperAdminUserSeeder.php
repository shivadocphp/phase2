<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'id' => '1',
            'name' => 'Super Admin',
            'emp_code' => 'JSC0001',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('123456789'),
            'profile_photo_path' => 'images/user/Image_1.png',
            'is_active' => 'Y',
        ];
        $user = User::create($data);
        $user->assignRole('Superadmin');
    }
}
