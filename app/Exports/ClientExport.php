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

class ClientExport implements FromCollection, WithStyles, WithColumnWidths   //, WithTitle, ShouldAutoSize    
{
    public $clients;

    public function __construct($clients1)
    {
        $this->clients = $clients1;
    }

    public function collection()
    {
        // return User::all();   //1st option

        $user_array = [
            ['Client Details Report', '', ''], // Title row
            ['Sno', 
            'Company Name', 
            'Company Email',
            'Client Code',
            'Company no.',
            'Industry',
            'Ceo',
            'Ceo no.',
            'Ceo Email',
            'Status',
            'Website',
            'Comments',
        ], // Header row
        ];

        $sno = 1;
        foreach ($this->clients as $key => $client) {
            $user_array[] = array(
                'SNO' => $sno++,
                'Company Name' => $client['company_name'],
                'Company Email' => $client['company_emailID'],
                'Client Code' => $client['client_code'],
                'Company no.' => $client['company_contact_no'],
                'Ceo' => $client['ceo'],
                'Ceo no.' => $client['ceo_contact'],
                'Ceo Email' => $client['ceo_emailID'],
                'Status' => $client['client_status'],
                'Website' => $client['website'],
                'Comments' => $client['comments'],
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
            'B' => 15,
            'C' => 18,
            'D' => 13,
            'E' => 15,
            'F' => 12,
            'G' => 12,
            'H' => 10,
            'I' => 13,
            'J' => 14,
            'K' => 13,
            'L' => 12,
        ];
    }

}
