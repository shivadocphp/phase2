<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
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
                'late_mark_at' => '15',
                'email_send_to' => 'phpdeveloper2.docllp@gmail.com',
            ]
        ];
        Setting::insert($data);
    }
}
