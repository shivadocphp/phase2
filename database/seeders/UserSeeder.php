<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $user =  User::create( [
        'name' => 'shiva',
        'email' => 'phpdeveloper2.docllp@gmail.com',
        'password' => Hash::make('12345678'),
            ]);
       $user->assignRole('super_admin');
    }
}
