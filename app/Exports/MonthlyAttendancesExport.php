<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


class MonthlyAttendancesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $columns_header;
    // protected $columns;
    protected $data;
    // protected $search;

    // public function __construct($columns_header, $columns, $data, $search)
    public function __construct($columns_header, $data)
    {
        $this->columns_header = $columns_header;
        // $this->columns = $columns;
        $this->data = $data;
        // $this->search = $search;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return $this->columns_header;
    }


    public function styles(Worksheet $sheet)
    {
        // 1st option(for all column)
        $styles = [];
        for ($i = 'A'; $i <= 'Z'; $i++) {
            $styles[$i] = ['font' => ['size' => '10']];
        }
        $styles[1] = ['font' => ['bold' => true, 'size' => '10']];
        $styles[3] = ['font' => ['bold' => true, 'size' => '10']];
        // Merge cells for the Monthly Attendance Report
        $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        $highestColumnLetter = Coordinate::stringFromColumnIndex($highestColumnIndex);
        $sheet->mergeCells('A1:' . $highestColumnLetter . '1');
        // $sheet->mergeCells('AJ3:AR3');
        // $sheet->getStyle('AJ3:AR3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C3:AG3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        return $styles;
    }

    public function columnWidths(): array
    {

        $widths = [];
        for ($i = 'B'; $i <= 'Z'; $i++) {
            $widths[$i] = 4;
        }
        $widths['A'] = 3;
        $widths['B'] = 15;
        $widths['AH'] = 7;
        return $widths;
    }
}
