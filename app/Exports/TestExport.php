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

class TestExport implements FromCollection, WithStyles, WithColumnWidths   //, WithTitle, ShouldAutoSize    
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
            ['Users Report', '', ''], // Title row
            ['SNO', 'NAME', 'EMAIL'], // Header row
        ];

        $sno = 1;
        foreach ($this->users as $key => $user) {
            $user_array[] = array(
                'SNO' => $sno++,
                'NAME' => $user['name'],
                'EMAIL' => $user['email'],
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
        $widths = [];
        for ($i = 'A'; $i <= 'Z'; $i++) {
            $widths[$i] = 20;
        }
        return $widths;


        // 2nd option(for individual column)
        // return [
        //     'A' => 46.14,
        //     'B' => 26.57,
        //     'C' => 26.57,
        // ];
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
