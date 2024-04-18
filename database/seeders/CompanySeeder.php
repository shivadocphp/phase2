<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
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
                'company_name' => 'Job Store Consulting',
                'company_address' => 'No 51, 1st Floor, Sri Shanmugaa Towers, 9th Street , Tatabad, Gandhipuram, Coimbatore, Tamil Nadu 641012',
                'pan' => 'BICPS3577F',
                'gstin' => '33BICPS3577F1Z6',
                'cgst' => '9',
                'sgst' => '9',
                'igst' => '18',
                'email_id' => 'info@jobstore.com',
                'landline_no' => '0422 – 4365355',
                'bank' => 'HDFC Bank',
                'mobile_no' => '2147483647',
                'account_name' => 'Job Store Consulting',
                'account_no' => '50200049251672',
                'ifsc' => 'HDFC000003',
                'branch' => 'Trichy Road',
            ]
        ];
        Company::insert($data);
    }
}
