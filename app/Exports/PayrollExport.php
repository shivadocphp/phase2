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

class PayrollExport implements FromCollection, WithStyles, WithColumnWidths   //, WithTitle, ShouldAutoSize    
{
    public $employee_payrolls;

    public function __construct($employee_payrolls1)
    {
        $this->employee_payrolls = $employee_payrolls1;
    }

    public function collection()
    {
        // return User::all();   //1st option

        $user_array = [
            ['Employee Payroll Details Report', '', ''], // Title row
            ['Sno',
            'Employee Code',
            'Payroll Category',
            'Month/Year',
            'Gross Salary',
            'Basic',
            'Hra',
            'Fixed Gross',
            'EPFO Employer',
            'ESIC Employer',
            'EPFO Employee',
            'ESIC Employee',
            'CTC',
            'PT',
            'Net Pay',
            'Added By',
            // 'Updated By',
        ],
        ];

        $sno = 1;
        foreach ($this->employee_payrolls as $key => $employee_payroll) {
            $user_array[] = array(
                'SNO' => $sno++,
                'Employee Code' => $employee_payroll->employee['emp_code'],
                'Payroll Category' => $employee_payroll['category'],
                'Month/Year' => $employee_payroll['month']."/".$employee_payroll['year'],
                'Gross Salary' => $employee_payroll['gross_sal'],
                'Basic' => $employee_payroll['basic'],
                'Hra' => $employee_payroll['hra'],
                'Fixed Gross' => $employee_payroll['fixed_gross'],
                'EPFO Employer' => $employee_payroll['epfo_employer'],
                'ESIC Employer' => $employee_payroll['esic_employer'],
                'EPFO Employee' => $employee_payroll['epfo_employee'],
                'ESIC Employee' => $employee_payroll['esic_employee'],
                'CTC' => $employee_payroll['ctc'],
                'PT' => $employee_payroll['pt'],
                'Net Pay' => $employee_payroll['net_pay'],
                'Added By' => $employee_payroll['added_by_name'],
                // 'Updated By' => $employee_payroll['updated_by'],
                //'Team' => join(",", Team::whereIn('id', TeamProject::where('project_id', $product['id'])->pluck('team_id'))->pluck('name')->toArray()),
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
        return [
            'A' => 4,
            'B' => 14,
            'C' => 10,
            'D' => 11,
            'E' => 12,
            'F' => 8,
            'G' => 10,
            'H' => 11,
            'I' => 13,
            'J' => 13,
            'K' => 13,
            'L' => 13,
            'M' => 7,
            'N' => 6,
            'O' => 9,
            'P' => 9,
            'Q' => 10,
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
