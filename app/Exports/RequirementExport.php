<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Skill;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\WithTitle;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class RequirementExport implements FromCollection, WithStyles, WithColumnWidths   //, WithTitle, ShouldAutoSize    
{
    public $requirements;

    public function __construct($requirements1)
    {
        $this->requirements = $requirements1;
    }

    public function collection()
    {
        // return User::all();   //1st option

        $user_array = [
            ['Requirements Details Report', '', ''], // Title row
            [
                'Sno',
                'Company Name',
                'Client Code',
                'Address',
                'Designation',
                'Status',
                'Skills',
                'Total Position',
                'Added On',
                'Added By',
            ], // Header row
        ];

        $sno = 1;
        foreach ($this->requirements as $key => $requirement) {

            $skills = null;
            if ($requirement['skills'] != null) {
                $sk = json_decode($requirement['skills']);
                if ($sk != null) {
                    for ($i = 0; $i < count($sk); $i++) {
                        $getskill = Skill::find($sk[$i])->skill;
                        // print_r($getskill);
                        if ($skills != null) {
                            $skills = $skills . "," . $getskill;
                        } else {
                            $skills = $getskill;
                        }
                    }
                }
            }

            $added_on = null;
            if ($requirement['created_at'] != null) {

                $a = explode(" ", $requirement['created_at']);
                $added_on = Carbon::parse($a[0])->format('d-m-Y');
            }


            $user_array[] = array(
                'SNO' => $sno++,
                'Company Name' => $requirement['company_name'],
                'Client Code' => $requirement['client_code'],
                'address' => $requirement['address'] . $requirement['city'] . $requirement['state'],
                'Designation' => $requirement['designation'],
                'Status' => $requirement['requirement_status'],
                'Skills' => $skills,
                'Total Position' => $requirement['total_position'],
                'Added On' => $added_on,
                'Added By' => $requirement['name'],
            );
        };
        return collect($user_array);
    }
    public function styles(Worksheet $sheet)
    {
        // 1st option(for all column)
        $styles = [];
        for ($i = 'A'; $i <= 'Z'; $i++) {
            $styles[$i] = ['font' => ['size' => '10']];
        }
        $styles[1] = ['font' => ['bold' => true, 'size' => '13']];
        $styles[2] = ['font' => ['bold' => true, 'size' => '11']];
        return $styles;
    }

    public function columnWidths(): array
    {
        // 2nd option(for individual column)
        return [
            'A' => 4,
            'B' => 16,
            'C' => 14,
            'D' => 18,
            'E' => 14,
            'F' => 10,
            'G' => 15,
            'H' => 13,
            'I' => 13,
            'J' => 14,
        ];
    }
}
