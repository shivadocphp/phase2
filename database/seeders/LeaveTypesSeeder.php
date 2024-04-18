<?php

namespace Database\Seeders;

use App\Models\Leavetype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'leavetype' => 'Casual',
            ],
            [
                'id' => '2',
                'leavetype' => 'Sick',
            ],
            [
                'id' => '3',
                'leavetype' => 'Permission',
            ],
            [
                'id' => '4',
                'leavetype' => 'Others',
            ],
            [
                'id' => '5',
                'leavetype' => 'Compensation Off',
            ],
            [
                'id' => '6',
                'leavetype' => 'Half Day Casual',
            ],
            [
                'id' => '7',
                'leavetype' => 'Half Day Sick Leave',
            ],
        ];
        Leavetype::insert($data);
    }
}
