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

class LeaveExport implements FromCollection, WithStyles, WithColumnWidths   //, WithTitle, ShouldAutoSize    
{
    public $leaves;

    public function __construct($leaves1)
    {
        $this->leaves = $leaves1;
    }

    public function collection()
    {
        // return User::all();   //1st option

        $user_array = [
            ['Leave Details Report', '', ''], // Title row
            ['Sno', 
            'Name', 
            'EMP_Code',
            'Shift_id',
            'Leave_type',
            'Requested Days',
            'Approved Days',
            'Requested Hours',
            'Approved Hours',
            'Date_from',
            'Date_to',
            'Reason',
            'Status',
            'Comments',
        ], // Header row
        ];

        $sno = 1;
        foreach ($this->leaves as $key => $leave) {
            $user_array[] = array(
                'SNO' => $sno++,
                'NAME' => $leave['name'],
                'EMP_Code' => $leave['emp_code'],
                'Shift_id' => $leave['shift_id'],
                'Leave_type' => $leave['leavetype'],
                'Requested Days' => $leave['requested_days'],
                'Approved Days' => $leave['approved_days'],
                'Requested Hours' => $leave['requested_hours'],
                'Approved Hours' => $leave['approved_hours'],
                'Date_from' => $leave['required_from'],
                'Date_to' => $leave['required_to'],
                'Reason' => $leave['reason'],
                'Status' => $leave['leave_status'],
                'Comments' => $leave['comments'],
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
            'C' => 9,
            'D' => 8,
            'E' => 11,
            'F' => 13,
            'G' => 13,
            'H' => 13,
            'I' => 13,
            'J' => 11,
            'K' => 11,
            'L' => 15,
            'M' => 6,
            'N' => 15,
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
