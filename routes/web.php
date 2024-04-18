<?php


use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Employee_AttendanceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AssignUserRoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\GSTController;
use App\Http\Controllers\EmployementmodeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\IndustrytypeController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\LeavetypeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LoginImageController;
use App\Http\Controllers\ExpendituretypeController;
use App\Http\Controllers\QualificationLevelController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PoliciesController;
use App\Http\Controllers\PerformanceAssessmentController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\Employee_PersonalDetailController;
use App\Http\Controllers\Employee_LeaveController;
use App\Http\Controllers\Team_ManagementController;
use App\Http\Controllers\Employee_OfficialDetailController;
use App\Http\Controllers\Employee_BankDetailController;
use App\Http\Controllers\Employee_PIPDetailController;
use App\Http\Controllers\Employee_SalaryDetailController;
use App\Http\Controllers\Client_BasicDetailController;
use App\Http\Controllers\Client_AddressController;
use App\Http\Controllers\Client_OfficialController;
use App\Http\Controllers\Client_RequirementController;
use App\Http\Controllers\Client_allocationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CandidateBasicDetailsController;
use App\Http\Controllers\CandidateDetailController;
use App\Http\Controllers\RecruiterCandidateController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;


use App\Http\Controllers\WeekoffController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});
Route::get('/login', function () {
    return view('login');
})->name('login');


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    // Artisan::call('optimize');
    // Artisan::call('db:seed');
    // Artisan::call('migrate');
    // return 'Cache cleared successfully';
    return redirect()->back()->with('success', 'Cache cleared successfully');
})->name('clear.cache');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/testnotify', [HomeController::class, 'testnotify'])->name('testnotify');
Route::get('/testnotification', [HomeController::class, 'testnotification'])->name('testnotification');
Route::get('/testmarkasread/{id}', [HomeController::class, 'testmarkasread'])->name('testmarkasread');

Route::middleware('auth')->group(function () {

    // start the routes for here
    //Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [HomeController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/dashboard/interview', [HomeController::class, 'getDashboardInterviewData'])->name('dashboard.interview');


    //EMPLOYEES
    Route::group(['middleware' => ['permission:Add Employees']], function () {

        //employee_personal
        Route::get('/employees/add', [Employee_PersonalDetailController::class, 'create'])->name('create.employee');
        Route::post('/employees/store', [Employee_PersonalDetailController::class, 'store'])->name('store.employee');
        //employee_official
        Route::post('/employees/add_official', [Employee_OfficialDetailController::class, 'create_official'])->name('create.emp_official');
        Route::post('/employees/store_emp_official', [Employee_OfficialDetailController::class, 'store_emp_official'])->name('store.employee_official');
        //employee_bank
        Route::get('/employees/add_bank', [Employee_BankDetailController::class, 'create_bank'])->name('create.emp_bank');
        Route::post('/employees/store_emp_bank', [Employee_BankDetailController::class, 'store_emp_bank'])->name('store.employee_bank');
    });
    Route::group(['middleware' => ['permission:View Employees']], function () {

        Route::get('/employees/all', [Employee_PersonalDetailController::class, 'index'])->name('all.employee');
        Route::get('/employees/all_inactive', [Employee_PersonalDetailController::class, 'inactive'])->name('inactive.employee');
        Route::get('/employees/all_ipj', [Employee_PersonalDetailController::class, 'ipj'])->name('ipj.employee');
        //Route::get('/employees/list', [\App\Http\Controllers\Employee_PersonalDetailController::class, 'getEmployees'])->name('employees.list');
        Route::get('/employees/getActiveEmployees', [Employee_PersonalDetailController::class, 'getActiveEmployees'])->name('active_emp.list');
        Route::get('/employees/getIPJEmployees', [Employee_PersonalDetailController::class, 'getIPJEmployees'])->name('ipj.list');
        Route::get('/employees/getInactiveEmployees', [Employee_PersonalDetailController::class, 'getInactiveEmployees'])->name('inactive.list');
        Route::get('/employees/show/{id}', [Employee_PersonalDetailController::class, 'show'])->name('show.employee');
    });
    Route::group(['middleware' => ['permission:Edit Employees']], function () {
        Route::get('/employees/edit_status_disable/{id}/{code}', [Employee_PersonalDetailController::class, 'edit_status_disable'])->name('edit.employee_status_disable');
        Route::get('/employees/edit_status_enable/{id}/{code}', [Employee_PersonalDetailController::class, 'edit_status_enable'])->name('edit.employee_status_enable');

        Route::get('/employees/edit/{id}', [Employee_PersonalDetailController::class, 'edit'])->name('edit.employee_personal');
        Route::get('/employees/edit_emp_bank/{id}', [Employee_BankDetailController::class, 'edit_emp_bank'])->name('edit.emp_bank');
        Route::get('/employees/edit_emp_official/{id}', [Employee_OfficialDetailController::class, 'edit_emp_official'])->name('edit.emp_official');
        Route::get('/employees/edit_emp_pip/{id}', [Employee_PIPDetailController::class, 'edit_emp_pip'])->name('edit.emp_pip');
        Route::get('/employees/edit_emp_image/{id}', [Employee_PersonalDetailController::class, 'edit_emp_image'])->name('edit.emp_image');
        Route::get('/employees/edit_emp_salary/{id}', [Employee_SalaryDetailController::class, 'edit_emp_salary'])->name('edit.emp_salary');

        Route::patch('/employees/update', [Employee_PersonalDetailController::class, 'update'])->name('update.employee');
        Route::patch('/employees/update_emp_bank', [Employee_BankDetailController::class, 'update_emp_bank'])->name('update.emp_bank');
        Route::patch('/employees/update_emp_official', [Employee_OfficialDetailController::class, 'update_emp_official'])->name('update.emp_official');
        Route::patch('/employees/update_emp_pip', [Employee_PIPDetailController::class, 'update_emp_pip'])->name('update.emp_pip');
        Route::patch('/employees/update_emp_salary', [Employee_SalaryDetailController::class, 'update_emp_salary'])->name('update.emp_salary');
        Route::patch('/employees/update_emp_image', [Employee_PersonalDetailController::class, 'update_emp_image'])->name('update.emp_image');
    });
    Route::group(['middleware' => ['permission:Generate Employee Code']], function () {
        Route::get('/employees/edit_code/{id}', [Employee_PersonalDetailController::class, 'edit_code'])->name('edit.employee_code');
    });
    Route::group(['middleware' => ['permission:Delete Employees']], function () {
        Route::get('/employees/edit_status_disable/{id}/{code}', [Employee_PersonalDetailController::class, 'edit_status_disable'])->name('edit.employee_status_disable');
        Route::get('/employees/edit_status_enable/{id}/{code}', [Employee_PersonalDetailController::class, 'edit_status_enable'])->name('edit.employee_status_enable');
    });

    //Attendance
    //Route::get('/employees/store_emp_login', [App\Http\Controllers\Employee_AttendanceController::class, 'store_emp_login'])->name('store.emp_login');
    Route::post('/employees/store_emp_login', [Employee_AttendanceController::class, 'store_emp_login'])->name('store.emp_login');
    Route::patch('/employees/update_emp_login', [Employee_AttendanceController::class, 'update_emp_login'])->name('update.update_emp_login');
    Route::get('/employees/attendance', [Employee_AttendanceController::class, 'index'])->name('all.attendance');
    Route::get('/employees/getAttendances', [Employee_AttendanceController::class, 'getAtendances'])->name('attendances.list');
    Route::group(['middleware' => ['permission:Add Attendance']], function () {
        Route::post('/employees/attendance/add', [Employee_AttendanceController::class, 'addAttendances'])->name('attendances.add');
    });
    Route::group(['middleware' => ['permission:Edit Attendance']], function () {
        Route::patch('/employees/update_at', [Employee_AttendanceController::class, 'update'])->name('update.attendance');
    });
    Route::group(['middleware' => ['permission:Monthly Attendance Report']], function () {
        Route::get('/employees/getMonthlyReport', [Employee_AttendanceController::class, 'getMonthlyReport'])->name('attendances.monthly');
        Route::post('/employees/getMonthlyReport', [Employee_AttendanceController::class, 'getMonthlyReport'])->name('attendances.monthly');
        Route::get('/employees/getMonthlyAttendances', [Employee_AttendanceController::class, 'getMonthlyAttendances'])->name('attendances.monthly.list');
        Route::post('/employees/getMonthlyAttendances', [Employee_AttendanceController::class, 'getMonthlyAttendances'])->name('attendances.monthly.list');
        Route::get('/employees/getMonthlyAttendancesCols', [Employee_AttendanceController::class, 'getMonthlyAttendancesCols'])->name('attendances.monthly.cols');
        Route::get('/employees/attendance_details/{id}', [Employee_AttendanceController::class, 'attendance_details']);
        Route::get('/exportSampleExcel', [Employee_AttendanceController::class, 'exportSampleExcel'])->name('exportSampleExcel');
    });

    Route::post('/generate-report', [Employee_AttendanceController::class, 'generateReport'])->name('generateReport');
    Route::get('/generateExcel', [Employee_AttendanceController::class, 'generateExcel'])->name('generateExcel');


    //Manage Team
    Route::post('api/fetch-detail', [TeamController::class, 'team_detail']);
    Route::post('api/fetch-team', [TeamController::class, 'edit']);
    Route::post('api/fetch-employee-team', [TeamController::class, 'team_add']);
    Route::group(['middleware' => ['permission:Manage Team']], function () {
        //team
        Route::get('/team/all', [TeamController::class, 'index'])->name('all.team');
        Route::patch('/team/add', [TeamController::class, 'store'])->name('store.team');
        Route::patch('/team/update', [TeamController::class, 'update'])->name('update.team');
        //Route::get('/employee_team/{id}','App\Http\Controllers\TeamController@team_detail');
        Route::patch('/team/delete_member', [TeamController::class, 'destroy'])->name('delete.member');
        Route::patch('/team/add_member', [TeamController::class, 'create'])->name('add.member');
        Route::get('/team_management/all', [Team_ManagementController::class, 'index'])->name('all.team_emp');
        //employee team
        Route::get('/team_management/edit/{id}', [Team_ManagementController::class, 'edit'])->name('edit.team_emp');
        Route::post('/team_management/store', [Team_ManagementController::class, 'store'])->name('store.team_emp');
        Route::get('/team_management/getTeam', [TeamController::class, 'getTeam'])->name('team.list');
    });

    //Leave Management
    Route::group(['middleware' => ['permission:View Own Leave|View All Leave']], function () {
        Route::get('/employees/emp_leave', [Employee_LeaveController::class, 'index'])->name('all.emp_leave');
        Route::get('/employees/getLeaves', [Employee_LeaveController::class, 'getLeaves'])->name('leaves.list');
    });
    Route::group(['middleware' => ['permission:Add Leave']], function () {
        Route::patch('/employees/store_emp_leave', [Employee_LeaveController::class, 'store_emp_leave'])->name('store.employee_leave');
    });
    Route::group(['middleware' => ['permission:Update Leave']], function () {
        Route::patch('/employees/approve_emp_leave', [Employee_LeaveController::class, 'approve_emp_leave'])->name('approve.employee_leave');
        Route::patch('/employees/disapprove_emp_leave', [Employee_LeaveController::class, 'disapprove_emp_leave'])->name('disapprove.employee_leave');
        Route::patch('/employees/cancel_emp_leave', [Employee_LeaveController::class, 'cancel_emp_leave'])->name('cancel.employee_leave');
        //Route::patch('/employees/update_emp_leave', [App\Http\Controllers\Employee_LeaveController::class, 'update_emp_leave'])->name('update.emp_leave');
    });
    Route::post('api/comp-work', [Employee_LeaveController::class, 'comp_work']);
    //Route::resource('clients', \App\Http\Controllers\Client_BasicDetailController::class);

    //Clients
    Route::group(['middleware' => ['permission:View Client']], function () {
        Route::get('/clients/all', [Client_BasicDetailController::class, 'index'])->name('all.client');
        Route::get('/clients/inactive', [Client_BasicDetailController::class, 'inactive'])->name('inactive.client');
        Route::get('/clients/prospect', [Client_BasicDetailController::class, 'prospect'])->name('prospect.client');
        Route::get('/clients/blacklisted', [Client_BasicDetailController::class, 'blacklisted'])->name('blacklisted.client');
        Route::get('/clients/show/{id}', [Client_BasicDetailController::class, 'show'])->name('show.client');
        Route::get('/employees/getActiveClients', [Client_BasicDetailController::class, 'getActiveClients'])->name('active_clients.list');
        Route::get('/employees/getInactiveClients', [Client_BasicDetailController::class, 'getInactiveClients'])->name('inactive_clients.list');
        Route::get('/employees/getProspectClients', [Client_BasicDetailController::class, 'getProspectClients'])->name('prospect_clients.list');
        Route::get('/employees/getBLacklistedClients', [Client_BasicDetailController::class, 'getBlacklistedClients'])->name('blacklisted_clients.list');
    });
    Route::group(['middleware' => ['permission:Add Client']], function () {
        Route::get('/clients/create', [Client_BasicDetailController::class, 'create'])->name('create.client');
        Route::post('/clients/store', [Client_BasicDetailController::class, 'store'])->name('store.client');
        Route::get('/clients/edit/{id}', [Client_BasicDetailController::class, 'edit'])->name('edit.client');
    });
    Route::group(['middleware' => ['permission:Edit Client']], function () {
        Route::get('/clients/edit/{id}', [Client_BasicDetailController::class, 'edit'])->name('edit.client');
        Route::patch('/clients/update/{id}', [Client_BasicDetailController::class, 'update'])->name('update.client');
        Route::post('/clients/store_address', [Client_AddressController::class, 'store'])->name('store.client_address');
        Route::get('/clients/edit_address/{id}', [Client_AddressController::class, 'edit'])->name('edit.clientaddress');
        Route::patch('/clients/update_address/{id}', [Client_AddressController::class, 'update'])->name('update.client_address');
        Route::get('/clients/delete_address/{id}', [Client_AddressController::class, 'destroy'])->name('delete.clientaddress');
        Route::get('/clients/edit_official/{id}', [Client_OfficialController::class, 'edit'])->name('edit.clientofficial');
        Route::patch('/clients/update_official/{id}', [Client_OfficialController::class, 'update'])->name('update.client_official');
        Route::get('/clients/edit_agreement/{id}', [Client_OfficialController::class, 'edit_agreement'])->name('edit.clientagreement');
        Route::patch('/clients/update_agreement/{id}', [Client_OfficialController::class, 'update_agreement'])->name('update.client_agreement');
        Route::get('/clients/delete_agreement/{id}', [Client_OfficialController::class, 'delete_agreement'])->name('client.delete_agreement');
        Route::get('/clients/change_status/{id}/{status}', [Client_BasicDetailController::class, 'change_status'])->name('status.client');

        // Route::get('/clients/edit_requirement/{id}', [Client_RequirementController::class, 'edit_client_require'])->name('edit.clientrequirement');
        Route::get('/clients/edit_requirement/{id}', [Client_RequirementController::class, 'edit_client_require'])->name('edit.clientrequirement')->middleware('permission:View Requirements');
    });

    //Requirement
    Route::group(['middleware' => ['permission:View Requirement']], function () {

        Route::get('/clients/edit_requirement/{id}', [Client_RequirementController::class, 'edit_client_require'])->name('edit.clientrequirement');
        Route::get('/requirement/all', [Client_RequirementController::class, 'index'])->name('all.requirement');
        Route::get('/requirement/hold', [Client_RequirementController::class, 'hold'])->name('hold.requirement');
        Route::get('/requirement/prospect', [Client_RequirementController::class, 'prospect'])->name('prospect.requirement');
        Route::get('/requirement/closedJSC', [Client_RequirementController::class, 'closedJSC'])->name('closedJSC.requirement');
        Route::get('/requirement/closedClient', [Client_RequirementController::class, 'closedClient'])->name('closedClient.requirement');

        Route::get('/employees/getRequirements/{id}', [Client_RequirementController::class, 'getRequirements'])->name('requirements.list');
        Route::get('/employees/getActiveRequirements', [Client_RequirementController::class, 'getActiveRequirements'])->name('active_requirements.list');
        Route::get('/employees/getHoldRequirements', [Client_RequirementController::class, 'getHoldRequirements'])->name('hold_requirements.list');
        Route::get('/employees/getProspectRequirements', [Client_RequirementController::class, 'getProspectRequirements'])->name('prospect_requirements.list');
        Route::get('/employees/getClosedJSCRequirements', [Client_RequirementController::class, 'getClosedJSCRequirements'])->name('closedJSC_requirements.list');
        Route::get('/employees/getClosedClientRequirements', [Client_RequirementController::class, 'getClosedClientRequirements'])->name('closedClient_requirements.list');
    });
    Route::group(['middleware' => ['permission:Edit Requirement']], function () {
        Route::get('/requirement/change_status/{id}/{status}', [Client_RequirementController::class, 'change_status'])->name('status.requirement');
        Route::get('/requirement/store_again/{id}', [Client_RequirementController::class, 'store_again'])->name('store_again.requirement');
        Route::get('/requirement/destroy/{id}', [Client_RequirementController::class, 'destroy'])->name('destroy.requirement');
        Route::get('/requirement/edit/{id}', [Client_RequirementController::class, 'edit'])->name('edit.requirement');
        Route::get('/requirement/edit_condition/{id}', [Client_RequirementController::class, 'edit_condition'])->name('edit.requirement_con');
        Route::patch('/requirement/update', [Client_RequirementController::class, 'update'])->name('update.requirement');
    });
    Route::group(['middleware' => ['permission:Add Requirement']], function () {
        // for particular client
        Route::get('/requirement/create/{id?}', [Client_RequirementController::class, 'create'])->name('create.requirement');
        Route::get('/requirement/show/{id}', [Client_RequirementController::class, 'show'])->name('show.requirement');
        Route::post('/requirement/store', [Client_RequirementController::class, 'store'])->name('store.requirement');
    });
    Route::post('api/fetch-clientlocation', [Client_RequirementController::class, 'fetchClientlocation']);


    //Client Allocation
    Route::group(['middleware' => ['permission:View Client Allocation']], function () {
        Route::get('/allocation/all', [Client_allocationController::class, 'index'])->name('all.allocation');
        Route::get('/employees/getAllocation', [Client_allocationController::class, 'getAllocation'])->name('getAllocation.list');
        Route::get('/allocation/show/{id}', [Client_allocationController::class, 'show'])->name('show.allocation');
    });
    Route::group(['middleware' => ['permission:Add Client Allocation']], function () {
        Route::get('/allocation/create', [Client_allocationController::class, 'create'])->name('create.allocation');
        Route::post('/allocation/store', [Client_allocationController::class, 'store'])->name('store.allocation');
    });
    Route::group(['middleware' => ['permission:Delete Client Allocation']], function () {
        Route::get('/allocation/destroy/{id}', [Client_allocationController::class, 'destroy'])->name('destroy.allocation');
    });

    //Task Allocation
    Route::group(['middleware' => ['permission:View Requirement Allocation']], function () {
        Route::get('/allocation/task/{id}', [Client_allocationController::class, 'task'])->name('allocate_task.allocation');
        Route::get('/allocation/list_task/{id}', [Client_allocationController::class, 'list_task'])->name('list_task.allocation');
        Route::get('/employees/getTask/{type}', [TaskController::class, 'getTask'])->name('getTask.list');
    });
    Route::group(['middleware' => ['permission:Add Requirement Allocation']], function () {
        Route::post('/taskallocation/store/{id}', [TaskController::class, 'store'])->name('store.taskallocation');
    });
    Route::group(['middleware' => ['permission:Edit Requirement Allocation']], function () {
        Route::patch('/taskallocation/update', [TaskController::class, 'update'])->name('update.taskallocation');
    });
    Route::group(['middleware' => ['permission:Delete Requirement Allocation']], function () {
        Route::get('/taskallocation/destroy/{id}', [TaskController::class, 'destroy'])->name('destroy.taskallocation');
    });




    //Candidate
    Route::group(['middleware' => ['permission:View Candidate']], function () {
        Route::get('/candidate/all', [CandidateBasicDetailsController::class, 'index'])->name('all.candidate');
        Route::get('/employees/getCandidates', [CandidateBasicDetailsController::class, 'getCandidates'])->name('getCandidates.list');
        Route::get('/candidate/show/{id}', [CandidateBasicDetailsController::class, 'show'])->name('show.candidate');
    });
    Route::group(['middleware' => ['permission:Add Candidate']], function () {
        Route::get('/candidate/create', [CandidateBasicDetailsController::class, 'create'])->name('create.candidate');
        Route::post('/candidate/store', [CandidateBasicDetailsController::class, 'store'])->name('store.candidate');
    });
    Route::group(['middleware' => ['permission:Edit Candidate']], function () {
        Route::get('/candidate/edit/{id}', [CandidateBasicDetailsController::class, 'edit'])->name('edit.candidate');
        Route::patch('/candidate/{id}/update', [CandidateBasicDetailsController::class, 'update'])->name('update.candidate');
    });
    Route::group(['middleware' => ['permission:Process Candidate']], function () {
        Route::get('/candidate/edit_detail/{id}/{name}', [CandidateBasicDetailsController::class, 'edit_detail'])->name('edit_detail.candidate');
        Route::get('/employees/processed/{id}/{file}', [CandidateDetailController::class, 'processed'])->name('processed.list');
        Route::post('/candidatedetail/store', [CandidateDetailController::class, 'store'])->name('store.candidatedetail');
        Route::patch('/candidatedetail/update', [CandidateDetailController::class, 'update'])->name('update.candidatedetail');
        Route::get('/candidatedetail/destroy/{id}', [CandidateDetailController::class, 'destroy'])->name('destroy.candidatedetail');
        Route::get('/employees/invoice/{id}', [CandidateDetailController::class, 'invoice'])->name('processed.invoice');
    });
    Route::group(['middleware' => ['permission:Bulk Upload Candidate']], function () {
        Route::get('/candidate/bulk_upload', [CandidateBasicDetailsController::class, 'bulk_upload'])->name('bulk_upload.candidate');
        Route::post('/candidate/upload_bulk', [CandidateBasicDetailsController::class, 'upload'])->name('upload.candidate');
    });
    // Route::group(['middleware' => ['permission:Delete Candidate']], function () {
    // });

    Route::post('api/fetch-clientRequirement', [CandidateBasicDetailsController::class, 'fetchClientRequirement']);
    Route::post('api/fetch-client', [CandidateBasicDetailsController::class, 'fetchClient']);
    Route::get('autocompleteCandidate', [CandidateBasicDetailsController::class, 'autocompleteCandidate'])->name('autocomplete');
    Route::get('autocompletelocation', [CandidateBasicDetailsController::class, 'autocompletelocation'])->name('autocompletelocation');


    // Route::post('/candidatedetail/recruiter', [RecruiterCandidateController::class, 'index'])->name('recruiter.candidate');
    //Track Recruiter
    Route::group(['middleware' => ['permission:Track Recruiter']], function () {
        Route::get('/track/all', [TrackController::class, 'index'])->name('track.all');
        Route::get('/employees/getDailyTrack', [TrackController::class, 'getDailyTrack'])->name('getDailyTrack.list');
    });
    //Track Client
    Route::group(['middleware' => ['permission:Track Client']], function () {
        Route::get('/track/client', [TrackController::class, 'client_track'])->name('track.client');
        Route::get('/employees/getDailyTrackClient', [TrackController::class, 'getDailyTrackClient'])->name('getDailyTrackClient.list');
    });


    //Invoice
    Route::group(['middleware' => ['permission:Add Invoice']], function () {
        Route::get('/invoice/all', [InvoiceController::class, 'index'])->name('all.invoice');
        Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('create.invoice');
        Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('store.invoice');
        Route::get('/invoice/show/{id}', [InvoiceController::class, 'show'])->name('show.invoice');
        Route::get('/employees/getInvoices', [InvoiceController::class, 'getInvoices'])->name('getInvoices.list');
        // Route::get('/employees/getInvoicesFilter', [InvoiceController::class, 'getInvoicesFilter'])->name('getInvoicesFilter.list');
    });
    Route::group(['middleware' => ['permission:Edit Invoice']], function () {
        Route::get('/invoice/edit/{id}', [InvoiceController::class, 'edit'])->name('edit.invoice');
        Route::post('/invoice/{id}/update', [InvoiceController::class, 'update'])->name('update.invoice');
    });
    Route::group(['middleware' => ['permission:Share Invoice']], function () {
        Route::get('/invoice/pdf/{id}', [InvoiceController::class, 'generateInvoicePDF'])->name('pdf.invoice');
        Route::get('/invoice/mail/{id}', [InvoiceController::class, 'mailInvoice'])->name('mail.invoice');
        Route::get('/invoice/whatsapp/{id}', [InvoiceController::class, 'whatsappInvoice'])->name('whatsapp.invoice');
    });
    Route::post('api/fetch-invoicelocation', [InvoiceController::class, 'location']);

    //Manage Payroll
    Route::group(['middleware' => ['permission:Manage Payroll']], function () {

        Route::get('/payroll/slab/list', [PayrollController::class, 'listPayrollSlab'])->name('payroll.slab.list');
        Route::post('/payroll/slab/list', [PayrollController::class, 'listPayrollSlab'])->name('payroll.slab.list');
        Route::get('/payroll/slab/create', [PayrollController::class, 'createPayrollSlab'])->name('payroll.slab.create');
        Route::post('/payroll/slab/create', [PayrollController::class, 'storePayrollSlab'])->name('payroll.slab.store');
        Route::get('/payroll/slab/edit/{id}', [PayrollController::class, 'editPayrollSlab'])->name('payroll.slab.edit');
        Route::post('/payroll/slab/edit/{id}', [PayrollController::class, 'updatePayrollSlab'])->name('payroll.slab.update');
        Route::get('/payroll/slab/delete/{id}', [PayrollController::class, 'deletePayrollSlab'])->name('payroll.slab.delete');
        Route::post('/payroll/slab/delete/{id}', [PayrollController::class, 'deletePayrollSlab'])->name('payroll.slab.delete');


        Route::get('/payroll/list', [PayrollController::class, 'listPayroll'])->name('payroll.list');
        Route::post('/payroll/list', [PayrollController::class, 'listPayroll'])->name('payroll.list');
        Route::get('/payroll/create', [PayrollController::class, 'createPayroll'])->name('payroll.create');
        Route::post('/payroll/create', [PayrollController::class, 'storePayroll'])->name('payroll.store');
        Route::get('/payroll/edit/{id}', [PayrollController::class, 'editPayroll'])->name('payroll.edit');
        Route::post('/payroll/edit/{id}', [PayrollController::class, 'updatePayroll'])->name('payroll.update');
        Route::get('/payroll/employee/calculate', [PayrollController::class, 'calcultatePayroll'])->name('payroll.employee.calculate');
        Route::post('/payroll/employee/calculate', [PayrollController::class, 'calcultatePayroll'])->name('payroll.employee.calculate');
    });



    //Manage Policies
    Route::group(['middleware' => ['permission:Manage Policies']], function () {
        Route::get('/policy/all', [PoliciesController::class, 'index'])->name('all.policy');
        Route::post('/policy/add', [PoliciesController::class, 'store'])->name('store.policy');
        Route::patch('/policy/update', [PoliciesController::class, 'update'])->name('update.policy');
        Route::get('/policy/status/{id}/{status}', [PoliciesController::class, 'status'])->name('status.policy');
    });

    //Manage Performance
    Route::group(['middleware' => ['permission:Manage Performance']], function () {
        // performanceassessment
        Route::get('/performanceassessment/all', [PerformanceAssessmentController::class, 'index'])->name('all.performanceassessment');
        Route::post('/performanceassessment/add', [PerformanceAssessmentController::class, 'store'])->name('store.performanceassessment');
        Route::patch('/performanceassessment/update', [PerformanceAssessmentController::class, 'update'])->name('update.performanceassessment');
        Route::get('/performanceassessment/manager_employee/{id}', [PerformanceAssessmentController::class, 'manager_employee'])->name('employee.manager_employee');
        Route::post('/performanceassessment/manager_employee_filter/{id}', [PerformanceAssessmentController::class, 'manager_employee_filter'])->name('employee.manager_employee_filter');
        Route::post('/performanceassessment/managerscore', [PerformanceAssessmentController::class, 'managerscore'])->name('employee.managerscore');
        // performancereview
        Route::get('/performancereview/all', [PerformanceReviewController::class, 'index'])->name('all.performancereview');
        Route::post('/performancereview/add', [PerformanceReviewController::class, 'store'])->name('store.performancereview');
        Route::patch('/performancereview/update', [PerformanceReviewController::class, 'update'])->name('update.performancereview');
    });
    //Performance Assessment
    Route::group(['middleware' => ['permission:Performance Assessment']], function () {
        Route::get('/performanceassessment/employee', [PerformanceAssessmentController::class, 'employee'])->name('employee.performanceassessment');
        Route::post('/performanceassessment/employeescore', [PerformanceAssessmentController::class, 'employeescore'])->name('employee.performanceassessmentscore');
        // Route::get('/performanceassessment/status/{id}/{status}', [PerformanceAssessmentController::class, 'status'])->name('status.performanceassessment');
    });
    //Performance Review
    Route::group(['middleware' => ['permission:Performance Review']], function () {
        // Route::get('/performancereview/status/{id}/{status}', [PerformanceReviewController::class, 'status'])->name('status.performancereview');
        Route::get('/performancereview/employee', [PerformanceReviewController::class, 'employee'])->name('employee.review');
        Route::post('/performancereview/employeereview', [PerformanceReviewController::class, 'employeereview'])->name('employee.performancereview');

        // Route::post('/performancereview/employee/{id}', [PerformanceReviewController::class, 'employee_filter'])->name('employee.review_filter');
    });

    //Manage user privilege
    Route::group(['middleware' => ['permission:Manage user privilege']], function () {

        Route::resource('roles', RoleController::class);
        Route::get('/roles/all', [RoleController::class, 'index'])->name('all.role');
        Route::post('/roles/add', [RoleController::class, 'store'])->name('store.role');
        Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('edit.role');
        Route::patch('/roles/update', [RoleController::class, 'update'])->name('update.role');
        //Assign User roles
        Route::get('/userRole/all', [AssignUserRoleController::class, 'index'])->name('all.user_role');
        Route::get('/userRole/create', [AssignUserRoleController::class, 'create'])->name('create.user_role');
        Route::get('/userRole/edit/{id}', [AssignUserRoleController::class, 'edit'])->name('edit.user_role');
        Route::post('/userRole/add', [AssignUserRoleController::class, 'store'])->name('store.user_role');
        Route::patch('/userRole/update', [AssignUserRoleController::class, 'update'])->name('update.user_role');
        Route::get('/userRole/getuserRole', [AssignUserRoleController::class, 'getuserRole'])->name('userRole.list');

        //Permissions
        Route::get('/permissions/all', [PermissionController::class, 'index'])->name('all.permission');
        Route::post('/permissions/add', [PermissionController::class, 'store'])->name('store.permission');
        Route::patch('/permissions/update', [PermissionController::class, 'update'])->name('update.permission');
    });

    Route::post('api/fetch-states', [CityController::class, 'fetchState']);
    Route::get('/select2-autocomplete-ajax', [CityController::class, 'dataAjax']);

    //Manage Settings
    Route::group(['middleware' => ['permission:Manage Settings']], function () {
        //site
        Route::get('/loginImage/all', [LoginImageController::class, 'index'])->name('all.loginImage');
        Route::post('/loginImage/add', [LoginImageController::class, 'store'])->name('store.loginImage');
        Route::get('/loginImage/update/{id}', [LoginImageController::class, 'update'])->name('update.loginImage');
        Route::post('/settings/update', [LoginImageController::class, 'updatesettings'])->name('update.settings');

        //Employement mode
        Route::get('/employementmode/all', [EmployementmodeController::class, 'index'])->name('all.emp_mode');
        Route::post('/employementmode/add', [EmployementmodeController::class, 'store'])->name('store.emp_mode');
        Route::patch('/employementmode/update', [EmployementmodeController::class, 'update'])->name('update.emp_mode');
        Route::get('/employementmode/history/{id}', [EmployementmodeController::class, 'history'])->name('emp_mode.history');

        //Designations
        Route::get('/designation/all', [DesignationController::class, 'index'])->name('all.designation');
        Route::post('/designation/add', [DesignationController::class, 'store'])->name('store.designation');
        Route::patch('/designation/update', [DesignationController::class, 'update'])->name('update.designation');
        Route::get('/designation/history/{id}', [DesignationController::class, 'history'])->name('designation.history');

        //Departments
        Route::get('/department/all', [DepartmentController::class, 'index'])->name('all.department');
        Route::post('/department/add', [DepartmentController::class, 'store'])->name('store.department');
        Route::patch('/department/update', [DepartmentController::class, 'update'])->name('update.department');
        Route::get('/department/history/{id}', [DepartmentController::class, 'history'])->name('department.history');

        //Leave type
        Route::get('/leavetype/all', [LeavetypeController::class, 'index'])->name('all.leavetype');
        Route::post('/leavetype/add', [LeavetypeController::class, 'store'])->name('store.leavetype');
        Route::patch('/leavetype/update', [LeavetypeController::class, 'update'])->name('update.leavetype');
        Route::get('/leavetype/history/{id}', [LeavetypeController::class, 'history'])->name('leavetype.history');

        //Holidays
        Route::get('/holiday/all', [HolidayController::class, 'index'])->name('all.holiday');
        Route::post('/holiday/add', [HolidayController::class, 'store'])->name('store.holiday');
        Route::patch('/holiday/update', [HolidayController::class, 'update'])->name('update.holiday');
        Route::get('/holiday/history/{id}', [HolidayController::class, 'history'])->name('holiday.history');
        Route::get('/holiday/destroy/{id}', [HolidayController::class, 'destroy'])->name('destroy.holiday');

        //Industry type
        Route::get('/industrytype/all', [IndustrytypeController::class, 'index'])->name('all.industrytype');
        Route::post('/industrytype/add', [IndustrytypeController::class, 'store'])->name('store.industrytype');
        Route::patch('/industrytype/update', [IndustrytypeController::class, 'update'])->name('update.industrytype');

        //Service type
        Route::get('/servicetype/all', [ServiceTypeController::class, 'index'])->name('all.servicetype');
        Route::post('/servicetype/add', [ServiceTypeController::class, 'store'])->name('store.servicetype');
        Route::patch('/servicetype/update', [ServiceTypeController::class, 'update'])->name('update.servicetype');
        //GST
        Route::get('/gst/all', [GSTController::class, 'index'])->name('all.gst');
        Route::post('/gst/add', [GSTController::class, 'store'])->name('store.gst');
        //Route::post('api/fetch-states', [DistrictController::class, 'fetchState']);
        Route::patch('/gst/update', [GSTController::class, 'update'])->name('update.gst');

        //Qualification Level
        Route::get('/qualificationlevels/all', [QualificationLevelController::class, 'index'])->name('all.qualificationlevels');
        Route::post('/qualificationlevels/add', [QualificationLevelController::class, 'store'])->name('store.qualificationlevels');
        Route::patch('/qualificationlevels/update', [QualificationLevelController::class, 'update'])->name('update.qualificationlevels');

        //Qualification
        Route::get('/qualification/all', [QualificationController::class, 'index'])->name('all.qualification');
        Route::post('/qualification/add', [QualificationController::class, 'store'])->name('store.qualification');
        Route::patch('/qualification/update', [QualificationController::class, 'update'])->name('update.qualification');

        //Company
        Route::get('/company/all', [CompanyController::class, 'index'])->name('all.company');
        Route::post('/company/add', [CompanyController::class, 'store'])->name('store.company');
        Route::patch('/company/update', [CompanyController::class, 'update'])->name('update.company');

        //country
        Route::get('/country/all', [CountryController::class, 'index'])->name('all.country');
        Route::post('/country/add', [CountryController::class, 'store'])->name('store.country');
        Route::patch('/country/update', [CountryController::class, 'update'])->name('update.country');

        //State Controller
        Route::get('/state/all', [StateController::class, 'index'])->name('all.state');
        Route::post('/state/add', [StateController::class, 'store'])->name('store.state');
        Route::patch('/state/update', [StateController::class, 'update'])->name('update.state');

        //City
        Route::get('/city/all', [CityController::class, 'index'])->name('all.city');
        Route::post('/city/add', [CityController::class, 'store'])->name('store.city');
        Route::patch('/city/update', [CityController::class, 'update'])->name('update.city');
        //Route::post('api/fetch-cities', [DropdownController::class, 'fetchCity']);

        //Skills
        Route::get('/skill/all', [SkillController::class, 'index'])->name('all.skill');
        Route::post('/skill/add', [SkillController::class, 'store'])->name('store.skill');
        Route::patch('/skill/update', [SkillController::class, 'update'])->name('update.skill');

        //Blood Groups
        /*Route::get('/bloodgroup/all', [App\Http\Controllers\BloodGroupController::class, 'index'])->name('all.bloodgroup');
            Route::post('/bloodgroup/add', [App\Http\Controllers\BloodGroupController::class, 'store'])->name('store.bloodgroup');
            Route::patch('/bloodgroup/update', [App\Http\Controllers\BloodGroupController::class, 'update'])->name('update.bloodgroup');*/

        //Expenditure type
        Route::get('/expendituretype/all', [ExpendituretypeController::class, 'index'])->name('all.expendituretype');
        Route::post('/expendituretype/add', [ExpendituretypeController::class, 'store'])->name('store.expendituretype');
        Route::patch('/expendituretype/update', [ExpendituretypeController::class, 'update'])->name('update.expendituretype');
    });

    //Route::post('autocomplete/fetch', [\App\Http\Controllers\Employee_PersonalDetailController::class, 'fetch']);
    //Route::post('api/api/fetchStateFromDistrict', [\App\Http\Controllers\Employee_PersonalDetailController::class, 'fetchStateFromDistrict']);
    Route::post('api/fetch-qualification', [Employee_PersonalDetailController::class, 'fetchQualification']);
    Route::post('api/fetch-city', [Employee_PersonalDetailController::class, 'fetchCity']);
    //Route::get('/employees/getStudents', [\App\Http\Controllers\Employee_AttendanceController::class, 'getStudents'])->name('attendance.list');


    //Mark week off
    Route::get('/weekoff/all', [WeekoffController::class, 'index'])->name('all.weekoff');
    Route::post('/weekoff/add', [WeekoffController::class, 'store'])->name('store.weekoff');
    Route::get('/weekoff/{id}', [WeekoffController::class, 'destroy'])->name('destroy.weekoff');






    Route::middleware(['auth:sanctum', 'verified'])->get('/employees', function () {
        //$users = DB::table('users')->get();
        return view('employeeprofile.index');
    })->name('employees');
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::patch('update/profile', [ProfileController::class, 'update'])->name('update.profile');
    Route::post('update/profileimage', [ProfileController::class, 'store'])->name('update.profileimage');

    Route::get('/user/logout', [HomeController::class, 'logout'])->name('user.logout');

    // Route::get('/create-symlink', function () {
    //     symlink(storage_path('/app/public'), public_path('storage'));
    //     echo "Symlink Created. Thanks";
    // });


    Route::get('/employee-export', [Employee_PersonalDetailController::class, 'exportemployee'])->name('export.employee');
    Route::get('/teammember-export', [TeamController::class, 'exportteammember'])->name('export.teammember');
    Route::get('/client-export', [Client_BasicDetailController::class, 'exportclient'])->name('export.client');
    Route::get('/requirement-export', [Client_RequirementController::class, 'exportrequirement'])->name('export.requirement');

    // end of the routes for
});


// to delete public/storage folder and link storage  //not working
// use Illuminate\Support\Facades\File;
// Route::get('/delete-directory', function () {
//     $folderPath = public_path('storage');
//     if (File::exists($folderPath)) {
//         $success = File::deleteDirectory($folderPath);
//         if ($success) {
//             return 'Directory deleted successfully';
//         } else {
//             return 'Failed to delete directory';
//         }
//     } else {
//         return 'Directory does not exist';
//     }
//     Artisan::call('storage.link');
// })->name('delete.directory');





Route::post('/upload', [TestController::class, 'upload'])->name('profile.upload');

Route::get('test_users', [TestController::class, 'test_users'])->name('test_users.index');
Route::get('test_users_list', [TestController::class, 'test_users_list'])->name('test_users_list.index');

Route::get('/export-users', [TestController::class, 'export'])->name('export.users');

Route::get('/test/mail', [TestController::class, 'testmail'])->name('mail.testmail');



require __DIR__ . '/auth.php';
