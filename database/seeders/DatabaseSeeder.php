<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\SuperAdminUserSeeder;
use Database\Seeders\CountriesSeeder;
use Database\Seeders\StatesSeeder;
use Database\Seeders\CitiesSeeder;
use Database\Seeders\EmpCodesSeeder;
use Database\Seeders\LeaveTypesSeeder;
use Database\Seeders\PayRollSeeder;
use Database\Seeders\CompanySeeder;
use Exception;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // try {
        //     DB::beginTransaction();
        //     $this->call([
        //         RoleSeeder::class
        //     ]);
        //     // $this->call([
        //     //     SettingSeeder::class
        //     // ]);
        //     $this->call([
        //         SuperAdminUserSeeder::class
        //     ]);
        //     $this->call([
        //         CountriesSeeder::class
        //     ]);
        //     $this->call([
        //         StatesSeeder::class
        //     ]);
        //     // $this->call([
        //     //     CitiesSeeder::class
        //     // ]);
        //     DB::commit();
        // } catch (Exception $exception) {
        //     DB::rollBack();
        // }

        $this->call([
            RoleAndPermissionSeeder::class,
            SuperAdminUserSeeder::class,
            CountriesSeeder::class,
            StatesSeeder::class,
            CitiesSeeder::class,
            EmpCodesSeeder::class,
            LeaveTypesSeeder::class,
            PayRollSeeder::class,
            CompanySeeder::class,
            SettingSeeder::class,
        ]);

    }
}
