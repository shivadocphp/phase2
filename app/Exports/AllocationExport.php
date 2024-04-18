<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Team;
use App\Models\Client_requirement;
use App\Models\Task;
use Carbon\Carbon;
use Maatwebsite\Excel\Sheet\DataType;

use Maatwebsite\Excel\Concerns\WithTitle;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class AllocationExport implements FromCollection, WithStyles, WithColumnWidths   //, WithTitle, ShouldAutoSize    
{
    public $allocations;

    public function __construct($allocations1)
    {
        $this->allocations = $allocations1;
    }

    public function collection()
    {
        // return User::all();   //1st option

        $user_array = [
            ['Allocation Details Report', '', ''], // Title row
            [
                'Sno',
                'Client',
                'Requirements',
                'Total Position',
                'Team Created By',
                'Teams',
                'Employee',
                'Alocated Position',
                'Task Added By',

            ], // Header row
        ];

        $sno = 1;
        foreach ($this->allocations as $key => $allocation) {
            // print_r($allocation);exit();
            $teams = null;
            /* getting teams*/
            $team_ids = $allocation['team_id'];
            $team_ids = explode(",", $team_ids);
            foreach ($team_ids as $t) {
                $clientteam = Team::where('id', $t)
                    ->get();
                foreach ($clientteam as $ct) {
                    if ($teams != null) {
                        $teams = $teams . ", " . $ct->team;
                    } else {
                        $teams = $ct->team;
                    }
                }
            }

            $requirements = null;
            $total = 0;
            $client_requirements = Client_requirement::join('designations', 'designations.id', 'client_requirements.position')
                ->select('client_requirements.total_position', 'designations.designation')
                ->where('client_requirements.client_id', $allocation['client_id'])
                ->get();
            foreach ($client_requirements as $cr) {
                $total = $total + $cr->total_position;
                if ($requirements != null) {
                    $requirements = $requirements . ", " . $cr->designation . "(" . $cr->total_position . ")";
                } else {
                    $requirements =  $cr->designation . "(" . $cr->total_position . ")";
                }
            }


            $tasks = Task::join('employee_personal_details', 'employee_personal_details.id', 'tasks.employee_id')
                ->leftJoin('users', 'users.id', 'tasks.added_by')
                ->select(
                    'tasks.id',
                    'tasks.allocation_id',
                    'tasks.requirement_id',
                    'tasks.allocated_no',
                    'tasks.employee_id',
                    'employee_personal_details.emp_code',
                    'employee_personal_details.firstname',
                    'employee_personal_details.middlename',
                    'employee_personal_details.lastname',
                    'users.name as added_by_name'
                )
                ->where('tasks.deleted_by', NULL)
                ->where('tasks.allocation_id', $allocation['id'])   //added
                ->get();
            $employee = array();
            $allocated_no = array();
            $added_by = array();
            foreach ($tasks as $key => $value) {

                $employee[]= $value->emp_code . " - " . $value->firstname . " " . $value->middlename . " " . $value->lastname;

                $allocated_no[] = $value->allocated_no;

                $added_by[] = $value->added_by_name;
            }



            $user_array[] = array(
                'SNO' => $sno++,
                'Client' => $allocation['client_code'] ."-". $allocation['company_name'],
                'Requirements' => $requirements,
                'Total Position' => $total,
                'Team Created By' => $allocation['name'],
                'Teams' => $teams,
                'Employee' => implode(PHP_EOL, $employee),
                'Alocated Position' => implode(PHP_EOL, $allocated_no),
                'Task Added By' => implode(PHP_EOL, $added_by)
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
        $styles['G']['alignment'] = ['wrapText' => true];
        $styles['H']['alignment'] = ['wrapText' => true];
        $styles['I']['alignment'] = ['wrapText' => true];
        return $styles;
    }

    public function columnWidths(): array
    {
        // 2nd option(for individual column)
        return [
            'A' => 4,
            'B' => 16,
            'C' => 17,
            'D' => 13,
            'E' => 17,
            'F' => 14,
            'G' => 20,
            'H' => 15,
            'I' => 15,
        ];
    }
}
