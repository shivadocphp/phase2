<?php

namespace Database\Seeders;

use App\Models\Emp_code;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpCodesSeeder extends Seeder
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
                'emp_code' => '0',
                'prefix' => 'JSC',
                'type' => 'Employee Code'
            ],
            [
                'emp_code' => '0',
                'prefix' => 'CLI',
                'type' => 'client'
            ],
            [
                'emp_code' => '0',
                'prefix' => 'JSC',
                'type' => 'invoice_code'
            ]
        ];
        Emp_code::insert($data);
    }
}
