<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class GenerateExcelReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sample Excel report';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     parent::__construct();
    // }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Define sample data
        $data = [
            ['Name', 'Email'],
            ['John Doe', 'john@example.com'],
            ['Jane Smith', 'jane@example.com'],
            ['Bob Johnson', 'bob@example.com'],
        ];

        // Generate Excel file
        Excel::create('sample_report', function($excel) use ($data) {
            $excel->sheet('Sheet1', function($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
            });
        })->export('xlsx');
        
        $this->info('Excel report generated successfully!');
    }
}
