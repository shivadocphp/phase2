<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\WithTitle;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class EmployeeExport implements FromCollection, WithStyles, WithColumnWidths   //, WithTitle, ShouldAutoSize    
{
    public $users;

    public function __construct($users1)
    {
        $this->users = $users1;
    }

    public function collection()
    {
        // return User::all();   //1st option

        $user_array = [
            ['Employee Details Report', '', ''], // Title row
            ['Sno', 
            'Name', 
            'Email',
            'EMP_Code',
            'Original_password',
            'Shift_id',
            'Gender',
            'DOB',
            'Personal_Id',
            'emp_mode',
            'Joining_date',
            'Department',
            'Designation',
            'country',
            'state',
            'city',
        ], // Header row
        ];

        $sno = 1;
        foreach ($this->users as $key => $user) {
            $user_array[] = array(
                'SNO' => $sno++,
                'NAME' => $user['name'],
                'EMAIL' => $user['email'],
                'EMP_Code' => $user['emp_code'],
                'Original_password' => $user['original_password'],
                'Shift_id' => $user['shift_id'],
                'Gender' => $user['gender'],
                'DOB' => $user['dob'],
                'Personal_Id' => $user['personal_emailID'],
                'emp_mode' => $user['employementmode'],
                'Joining_date' => $user['joining_date'],
                'Department' => $user['department'],
                'Designation' => $user['designation'],
                'Country' => $user['country'],
                'State' => $user['state'],
                'City' => $user['city'],
                // 'Team' => join(",", Team::whereIn('id', TeamProject::where('project_id', $product['id'])->pluck('team_id'))->pluck('name')->toArray()),
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


        // 2nd option(for individual column)
        // return [
        //     'A' => ['font' => ['size' => '10']],
        //     'B' => ['font' => ['size' => '10']],
        //     'C' => ['font' => ['size' => '10']],
        //     1    => ['font' => ['bold' => true, 'size' => '11']],
        // ];
    }

    public function columnWidths(): array
    {
        // 1st option(for all column)
        // $widths = [];
        // for ($i = 'A'; $i <= 'Z'; $i++) {
        //     $widths[$i] = 20;
        // }
        // return $widths;


        // 2nd option(for individual column)
        return [
            'A' => 4,
            'B' => 15,
            'C' => 18,
            'D' => 10,
            'E' => 10,
            'F' => 6,
            'G' => 7,
            'H' => 10,
            'I' => 20,
            'J' => 10,
            'K' => 11,
            'L' => 12,
            'M' => 12,
            'N' => 8,
            'O' => 8,
            'P' => 8,
        ];
    }


    // not working
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Merge cells for the title row
                $event->sheet->getDelegate()->mergeCells('A1:C1');
            },
        ];
    }
}
