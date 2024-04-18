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
use App\Models\Employee_team;

class TeamMemberExport implements FromCollection, WithStyles, WithColumnWidths   //, WithTitle, ShouldAutoSize    
{
    public $teams;

    public function __construct($teams1)
    {
        $this->teams = $teams1;
    }

    public function collection()
    {
        // return User::all();   //1st option

        $team_array = [
            ['Team Members Report', '', ''], // Title row
            [
                'Sno',
                'Team Name',
                'Team Leader',
                'Member Name',
                'Description',
            ], // Header row
        ];

        $sno = 1;
        foreach ($this->teams as $key => $team) {
            $teamLeader = $team['subtitle'] . ' ' . $team['firstname'] . ' ' . $team['middlename'] . ' ' . $team['lastname'];
            $members = Employee_team::join('employee_personal_details', 'employee_personal_details.id', 'employee_teams.emp_id')
                ->select(
                    'employee_personal_details.subtitle',
                    'employee_personal_details.firstname',
                    'employee_personal_details.middlename',
                    'employee_personal_details.lastname'
                )
                ->where('employee_teams.is_active', 'Y')
                ->where('team_id', $team['id'])
                ->where('member_type', 'M')
                ->get();
            // print_r($members);exit();
            // Build array for each team and its members
            if ($members->isNotEmpty()) {
                foreach ($members as $member) {
                    $team_array[] = [
                        'SNO' => $sno++,
                        'Team Name' => $team['team'],
                        'Team Leader' => $teamLeader,
                        'Member Name' => $member['subtitle'] . ' ' . $member['firstname'] . ' ' . $member['middlename'] . ' ' . $member['lastname'],
                        'Description' => $team['description'],
                    ];
                }
            } else {
                $team_array[] = [
                    'SNO' => $sno++,
                    'Team Name' => $team['team'],
                    'Team Leader' => $teamLeader,
                    'Member Name' => 'No members in this team',
                    'Description' => $team['description'],
                ];
            }
        }

        return collect($team_array);
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
            'A' => 5,
            'B' => 15,
            'C' => 20,
            'D' => 20,
            'E' => 12,
        ];
    }


    // not working
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge cells for the title row
                $event->sheet->getDelegate()->mergeCells('A1:C1');
            },
        ];
    }
}
