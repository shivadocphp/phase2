<?php

namespace Database\Seeders;

use App\Models\Payroll;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PayRollSeeder extends Seeder
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
                'category' => 'Category 1',
                'gross_sal_limit' => '15001',
                'basic_perc' => '80',
                'hra_perc' => '20',
                'epfo_employer_perc' => 0,
                'epfo_employee_perc' => 0,
                'esic_employer_perc' => 0,
                'esic_employee_perc' => 0,
                'pt' => '290',
                'status' => '1',
            ],
            [
                'id' => '2',
                'category' => 'Category 2',
                'gross_sal_limit' => '21001',
                'basic_perc' => '80',
                'hra_perc' => '20',
                'epfo_employer_perc' => '13',
                'epfo_employee_perc' => '12',
                'esic_employer_perc' => '3.25',
                'esic_employee_perc' => '0.75',
                'pt' => '290',
                'status' => '1',
            ],
            [
                'id' => '3',
                'category' => 'Category 3',
                'gross_sal_limit' => '21001',
                'basic_perc' => '80',
                'hra_perc' => '20',
                'epfo_employer_perc' => '13',
                'epfo_employee_perc' => '12',
                'esic_employer_perc' => 0,
                'esic_employee_perc' => 0,
                'pt' => '290',
                'status' => '1',
            ],
        ];
        Payroll::insert($data);
    }
}
