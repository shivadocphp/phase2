<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Team;
use App\Models\Skill;
use App\Models\CandidateDetail;
use Carbon\Carbon;
use Maatwebsite\Excel\Sheet\DataType;

use Maatwebsite\Excel\Concerns\WithTitle;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class CandidateExport implements FromCollection, WithStyles, WithColumnWidths   //, WithTitle, ShouldAutoSize    
{
    public $candidates;

    public function __construct($candidates1)
    {
        $this->candidates = $candidates1;
    }

    public function collection()
    {
        // return User::all();   //1st option

        $user_array = [
            ['Candidates Details Report', '', ''], // Title row
            [
                'Sno',
                'Candidate Name',
                'Mobile',
                'Whatsapp',
                'Email',
                'Skills',
                'Candidate Status',
                'Candidate Added By',
                '',
                'Requirement',
                'Client',
                'Call Status',
                'Requirement Status',
                'Processed By',
                'Comments',

            ], // Header row
        ];

        $sno = 1;
        foreach ($this->candidates as $key => $candidate) {
            // print_r($allocation);exit();
            $skills = null;
                if ($candidate['skills'] != null) {
                    $sk = json_decode($candidate['skills']);
                    if ($sk != null) {
                        for ($i = 0; $i < count($sk); $i++) {
                            $getskill = Skill::find($sk[$i])->skill;
                            // print_r($getskill);
                            if ($skills != null) {
                                $skills = $skills . "," . $getskill;
                            } else {
                                $skills  = $getskill;
                            }
                        }
                    }
                }


            $candidateDetail = CandidateDetail::join('users', 'users.id', 'candidate_details.added_by')
                ->join('candidate_basic_details', 'candidate_basic_details.id', 'candidate_details.candidate_id')
                ->join('client_basic_details', 'client_basic_details.id', 'candidate_details.client_id')
                ->join('client_requirements', 'client_requirements.id', 'candidate_details.requirement_id')
                ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
                ->join('states', 'states.id', 'client_addresses.state_id')
                ->join('cities', 'cities.id', 'client_addresses.city_id')
                ->join('designations', 'designations.id', 'client_requirements.position')
                ->select(
                    'candidate_details.id',
                    'client_basic_details.client_code',
                    'client_basic_details.company_name',
                    'designations.designation',
                    'states.state',
                    'cities.city',
                    'client_addresses.address',
                    'candidate_details.call_status',
                    'candidate_details.comments',
                    'users.name',
                    'candidate_details.added_by',
                    'candidate_basic_details.candidate_name',
                    'candidate_details.requirement_id',
                    'candidate_details.client_id',
                    'candidate_details.interview_mode',
                    'candidate_details.available_time',
                    'candidate_details.call_back_date',
                    'candidate_details.call_back_time',
                    'candidate_details.call_back_status',
                    'candidate_details.requirement_status',
                    // 'candidate_details.call_status',
                    // 'candidate_details.comments'
                )
                ->where('candidate_details.candidate_id', $candidate['candidate_id'])
                ->where('candidate_details.deleted_at', NULL)
                ->orderBy('candidate_details.id', 'desc')
                ->get();

            $requirement = array();
            $client = array();
            $call_status = array();
            $requirement_status = array();
            $processed_by = array();
            $comments = array();
            foreach ($candidateDetail as $key => $value) {

                $requirement[] = $value->designation . " , " . $value->address . "," . $value->city . "," . $value->state;
                $client[] = $value->client_code . " - " . $value->company_name;

                $call_status[] = $value->call_status;
                $requirement_status[] = $value->requirement_status;
                $processed_by[] = $value->name;
                $comments[] = $value->comments;
            }



            $user_array[] = array(
                'Sno' => $sno++,
                'Candidate Name' => $candidate['candidate_name'],
                'Mobile' => $candidate['contact_no'],
                'Whatsapp' => $candidate['whatsapp_no'],
                'Email' => $candidate['candidate_email'],
                'Skills' => $skills,
                'Candidate Status' => $candidate['status'],
                'Candidate Added By' => $candidate['name'],
                '' => '',
                'Requirement' => implode(PHP_EOL, $requirement),
                'Client' => implode(PHP_EOL, $client),
                'Call Status' => implode(PHP_EOL, $call_status),
                'Requirement Status' => implode(PHP_EOL, $requirement_status),
                'Processed By' => implode(PHP_EOL, $processed_by),
                'Comments' => implode(PHP_EOL, $comments),

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

        $styles[2]['alignment'] = ['wrapText' => true];
        $styles['J']['alignment'] = ['wrapText' => true];
        $styles['K']['alignment'] = ['wrapText' => true];
        $styles['L']['alignment'] = ['wrapText' => true];
        $styles['M']['alignment'] = ['wrapText' => true];
        $styles['N']['alignment'] = ['wrapText' => true];
        $styles['O']['alignment'] = ['wrapText' => true];

        return $styles;
    }

    public function columnWidths(): array
    {
        // 2nd option(for individual column)
        return [
            'A' => 4,
            'B' => 15,
            'C' => 12,
            'D' => 11,
            'E' => 17,
            'F' => 14,
            'G' => 16,
            'H' => 15,
            'I' => 2,
            'J' => 30,
            'K' => 8,
            'L' => 8,
            'M' => 8,
            'N' => 8,
            'O' => 8,
        ];
    }
}
