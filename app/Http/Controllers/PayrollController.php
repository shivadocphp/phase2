<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\EmployeePayroll;
use App\Models\Employee_personal_detail;
// use Auth;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Exports\PayrollExport;
use Maatwebsite\Excel\Facades\Excel;


class PayrollController extends Controller
{
    public function listPayrollSlab(Request $request)
    {
        $payroll_data = Payroll::where('status', 1)->orderBy('id', 'DESC')->paginate();
        if ($request->ajax()) {
            return view('admin.payroll.listSlabPagin', compact('payroll_data'));
        }
        return view('admin.payroll.listSlab', compact('payroll_data'));
    }
    public function createPayrollSlab(Request $request)
    {
        return view('admin.payroll.createSlab');
    }

    public function storePayrollSlab(Request $request)
    {
        $rules = [
            'category'     => 'required',
            'gross_sal_limit'  => 'required|numeric',
            'basic_perc'  => 'required|numeric',
            'hra_perc'  => 'required|numeric',
            'epfo_employee_perc'  => 'numeric',
            'epfo_employer_perc'  => 'numeric',
            'esic_employee_perc'  => 'numeric',
            'esic_employer_perc'  => 'numeric',
            'pt'  => 'required|numeric',
        ];

        $customMessages = [
            'gross_sal_limit.required' => 'Gross salary limit is required',
            'basic_perc.required' => 'Basic Salary Percentage is required',
            'hra_perc.required' => 'HRA Percentage is required',
            'epfo_employee_perc.numeric' => 'EPFO Employee Percentage must be numeric',
            'epfo_employer_perc.numeric' => 'EPFO Employer Percentage must be numeric',
            'esic_employee_perc.numeric' => 'ESIC Employee Percentage must be numeric',
            'esic_employer_perc.numeric' => 'ESIC Employer Percentage must be numeric',
        ];

        $validatedData = $this->validate($request, $rules, $customMessages);

        /*if(Auth::user()->hasRole('super-admin')){
            return response()->json(['status' => 'error','message'=>'No permission, Access Denied'],200);
        }*/

        $payroll_count       = Payroll::where('category', trim($request->category))->where('status', 1)->count();
        if ($payroll_count > 1)
            return response()->json(['status' => 'error', 'message' => 'Already Exist.']);

        $payroll                        = new Payroll();
        $payroll->category              = trim($request->category);
        $payroll->gross_sal_limit       = isset($request->gross_sal_limit) ? trim($request->gross_sal_limit) : 0;
        $payroll->basic_perc            = isset($request->basic_perc) ? trim($request->basic_perc) : 0;
        $payroll->hra_perc              = isset($request->hra_perc) ? trim($request->hra_perc) : 0;
        $payroll->hra_perc              = isset($request->hra_perc) ? trim($request->hra_perc) : 0;
        $payroll->epfo_employer_perc    = isset($request->epfo_employer_perc) ? trim($request->epfo_employer_perc) : 0;
        $payroll->esic_employer_perc    = isset($request->esic_employer_perc) ? trim($request->esic_employer_perc) : 0;
        $payroll->epfo_employee_perc    = isset($request->epfo_employee_perc) ? trim($request->epfo_employee_perc) : 0;
        $payroll->esic_employee_perc    = isset($request->esic_employee_perc) ? trim($request->esic_employee_perc) : 0;
        $payroll->pt                    = isset($request->pt) ? trim($request->pt) : 0;
        $payroll->save();


        $msg = 'Payroll slab has been submitted successfully. Thank you !';

        return response()->json(['status' => 'success', 'message' => $msg]);
    }

    public function editPayrollSlab(Request $request, $id)
    {
        $payroll = Payroll::find($id);
        if (!$payroll)
            return response()->json(['status' => 'error', 'message' => 'Invalid id passed']);

        return view('admin.payroll.createSlab', compact('payroll'));
    }

    public function updatePayrollSlab(Request $request, $id)
    {
        $rules = [
            'category'     => 'required|unique:payrolls' . ',id,' . $id . '|max:255',
            'gross_sal_limit'  => 'required|numeric',
            'basic_perc'  => 'required|numeric',
            'hra_perc'  => 'required|numeric',
            'epfo_employee_perc'  => 'numeric',
            'epfo_employer_perc'  => 'numeric',
            'esic_employee_perc'  => 'numeric',
            'esic_employer_perc'  => 'numeric',
            'pt'  => 'required|numeric',
        ];

        $customMessages = [
            'gross_sal_limit.required' => 'Gross salary limit is required',
            'basic_perc.required' => 'Basic Salary Percentage is required',
            'hra_perc.required' => 'HRA Percentage is required',
            'epfo_employee_perc.numeric' => 'EPFO Employee Percentage must be numeric',
            'epfo_employer_perc.numeric' => 'EPFO Employer Percentage must be numeric',
            'esic_employee_perc.numeric' => 'ESIC Employee Percentage must be numeric',
            'esic_employer_perc.numeric' => 'ESIC Employer Percentage must be numeric',
        ];

        $validatedData = $this->validate($request, $rules, $customMessages);

        /*if(Auth::user()->hasRole('super-admin')){
            return response()->json(['status' => 'error','message'=>'No permission, Access Denied'],200);
        }*/

        $payroll       = Payroll::find($id);
        if (!$payroll)
            return response()->json(['status' => 'error', 'message' => 'Invalid Id.']);

        $payroll->category              = trim($request->category);
        $payroll->gross_sal_limit       = isset($request->gross_sal_limit) ? trim($request->gross_sal_limit) : 0;
        $payroll->basic_perc            = isset($request->basic_perc) ? trim($request->basic_perc) : 0;
        $payroll->hra_perc              = isset($request->hra_perc) ? trim($request->hra_perc) : 0;
        $payroll->hra_perc              = isset($request->hra_perc) ? trim($request->hra_perc) : 0;
        $payroll->epfo_employer_perc    = isset($request->epfo_employer_perc) ? trim($request->epfo_employer_perc) : 0;
        $payroll->esic_employer_perc    = isset($request->esic_employer_perc) ? trim($request->esic_employer_perc) : 0;
        $payroll->epfo_employee_perc    = isset($request->epfo_employee_perc) ? trim($request->epfo_employee_perc) : 0;
        $payroll->esic_employee_perc    = isset($request->esic_employee_perc) ? trim($request->esic_employee_perc) : 0;
        $payroll->pt                    = isset($request->pt) ? trim($request->pt) : 0;
        $payroll->save();


        $msg = 'Payroll slab has been updated successfully. Thank you !';

        return response()->json(['status' => 'success', 'message' => $msg]);
    }

    public function deletePayrollSlab(Request $request, $id)
    {
        $payroll       = Payroll::find($id);
        if (!$payroll)
            return response()->json(['status' => 'error', 'message' => 'Invalid Id.']);

        $payroll->status = 3;
        $payroll->save();

        $msg = 'Payroll slab has been deleted successfully. Thank you !';

        return response()->json(['status' => 'success', 'message' => $msg]);
    }

    public function listPayroll(Request $request)
    {

        if ($request->input('export')) {
            return $this->exportpayroll($request);
        }


        $emp_id = null;
        $month = null;
        $year = null;

        $employee = Employee_personal_detail::select(
            'employee_personal_details.id',
            'employee_personal_details.emp_code',
            'employee_personal_details.subtitle',
            'employee_personal_details.firstname',
            'employee_personal_details.lastname',
            'employee_personal_details.middlename'
        )   //, 'users.*'
            // ->join('users', 'employee_personal_details.emp_code', '=', 'users.emp_code')
            // ->where('users.is_active', 'Y')
            ->where('employee_personal_details.is_active', 'Y')
            ->orderBy('employee_personal_details.emp_code')
            ->get();

        if ($request->ajax()) {
            $employee_payrolls = EmployeePayroll::with('employee');
            // ->select('id', DB::raw("CONCAT(month, ' ', year) AS month_year"), 'basic', 'hra','fixed_gross',
            // 'epfo_employer','esic_employer','epfo_employee','esic_employee','ctc','pt','net_pay')
            // ->addSelect(DB::raw("CONCAT(month, ' ', year) AS month_year"))

            $emp_id = $request->emp_id;
            $month = $request->month;
            $year = $request->year;
            if ($emp_id != null) {
                if ($emp_id != 'all') {
                    $employee_payrolls = $employee_payrolls->where('emp_id', $emp_id);
                }
                $employee_payrolls = $employee_payrolls->where('month', $month)
                    ->where('year', $year);
            }
            $employee_payrolls = $employee_payrolls->where('status', 1)->latest()->get();

            return Datatables::of($employee_payrolls)
                ->addIndexColumn()
                ->addColumn('manage', function ($row) {
                    $btn = '<div class="btn-group btn-group-sm">';
                    $btn = $btn . '<a href="' . route('payroll.edit', $row->id) . '" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-success btn-sm editUser"><i class="fas fa-edit" ></i></a>';
                    $btn = $btn . '</div>';

                    return $btn;
                })
                ->rawColumns(['manage'])
                ->make(true);
        }
        return view('admin.payroll.index', compact('employee'));    //
    }

    public function createPayroll(Request $request)
    {
        $employees = Employee_personal_detail::where('is_active', 'Y')->get();
        $categories = Payroll::where('status', 1)->get();
        return view('admin.payroll.create', compact('employees', 'categories'));
    }

    public function calcultatePayroll(Request $request)
    {
        $gross_sal = trim($request->gross_sal);
        $category = trim($request->category);
        //$page_from       = isset($request->page_from) ? trim($request->page_from) : NULL;
        if ($gross_sal == '' && is_nan($gross_sal)) {
            return response()->json(['status' => 'error', 'message' => 'Please provide a valid gross Salary', 'data' => 'NA']);
        }

        $res_array                   = array();
        $res_array['basic']          = 0;
        $res_array['hra']              = 0;
        $res_array['fixed_gross']      = 0;
        $res_array['epfo_employer'] = 0;
        $res_array['epfo_employee'] = 0;
        $res_array['esci_employer'] = 0;
        $res_array['esci_employee'] = 0;
        $res_array['ctc']              = 0;
        $res_array['pt']              = 0;
        $res_array['net_pay']          = 0;

        if ($category == 'Category 1') {
            $res_array['basic']          = $gross_sal * 0.8;
            $res_array['hra']              = $gross_sal * 0.2;
            $res_array['fixed_gross']      = $res_array['basic'] + $res_array['hra'];
            $res_array['epfo_employer'] = 0;
            $res_array['epfo_employee'] = 0;
            $res_array['esci_employer'] = 0;
            $res_array['esci_employee'] = 0;
            $res_array['ctc']              = $res_array['basic'] + $res_array['hra'];
            $res_array['pt']              = 209;
            $res_array['net_pay']          = $res_array['ctc'] - $res_array['pt'];
        } elseif ($category == 'Category 2') {

            $res_array['basic']          = $gross_sal * 0.8;
            $res_array['hra']              = $gross_sal * 0.2;
            $res_array['fixed_gross']      = $res_array['basic'] + $res_array['hra'];
            $res_array['epfo_employer'] = $res_array['basic'] * 0.13;
            $res_array['epfo_employee'] = $res_array['basic'] * 0.12;
            $res_array['esci_employer'] = $gross_sal * 0.0325;
            $res_array['esci_employee'] = $gross_sal * 0.0075;
            $res_array['ctc']              = $res_array['basic'] + $res_array['hra'] + $res_array['epfo_employer'] + $res_array['esci_employer'];
            $res_array['pt']              = 209;
            $res_array['net_pay']          = $res_array['ctc'] - ($res_array['epfo_employee'] + $res_array['esci_employee'] + $res_array['pt']);
        } elseif ($category == 'Category 3') {
            $res_array['basic']          = $gross_sal * 0.8;
            $res_array['hra']              = $gross_sal * 0.2;
            $res_array['fixed_gross']      = $res_array['basic'] + $res_array['hra'];
            $res_array['epfo_employer'] = $res_array['basic'] * 0.13;
            $res_array['epfo_employee'] = $res_array['basic'] * 0.12;
            $res_array['esci_employer'] = 0;
            $res_array['esci_employee'] = 0;
            $res_array['ctc']              = $res_array['basic'] + $res_array['hra'] + $res_array['epfo_employer'];
            $res_array['pt']              = 209;
            $res_array['net_pay']          = $res_array['ctc'] - ($res_array['epfo_employee'] + $res_array['pt']);
        }

        return response()->json(['status' => 'success', 'message' => 'Available', 'data' => $res_array]);
    }
    // old
    // public function storePayroll(Request $request)
    // {
    //     // print_r($request->all());exit();
    //     $rules = [
    //         // 'employee'   =>'required|numeric', 
    //         'category'     => 'required',
    //         'gross_sal'  => 'required|numeric',
    //         'start_month'  => 'required|numeric',
    //         'end_month'  => 'required|numeric',
    //         'year'  => 'required|numeric',
    //     ];

    //     $customMessages = [];

    //     $validatedData = $this->validate($request, $rules, $customMessages);

    //     /*if(Auth::user()->hasRole('super-admin')){
    //         return response()->json(['status' => 'error','message'=>'No permission, Access Denied'],200);
    //     }*/

    //     // $employee = trim($request->employee);
    //     $gross_sal = trim($request->gross_sal);
    //     $category  = trim($request->category);
    //     $start_month  = trim($request->start_month);
    //     $end_month  = trim($request->end_month);
    //     $year  = trim($request->year);
    //     // if($start_month==$end_month){
    //     //     $month = $start_month;
    //     // }else{

    //     // }

    //     //$page_from       = isset($request->page_from) ? trim($request->page_from) : NULL;
    //     // if($gross_sal=='' && is_nan($gross_sal)){
    //     //     return response()->json(['status'=>'error','message'=>'Please provide a valid gross Salary','data'=>'NA']);
    //     // }

    //     // $employee_pers       = Employee_personal_detail::find($employee);
    //     // if(!$employee_pers){
    //     //     return response()->json(['status' => 'error', 'message' => 'Invalid Employee id.']);
    //     // }
    //     // $emp_id    = $employee_pers->id;

    //     $payroll       = Payroll::where('category', $category)->where('status', 1)->first();
    //     if (!$payroll) {
    //         return response()->json(['status' => 'error', 'message' => 'Invalid Category.']);
    //     }
    //     $payroll_id         = $payroll->id;
    //     $payroll_category   = $payroll->category;
    //     $gross_sal_limit   = $payroll->gross_sal_limit;

    //     if ($gross_sal < $gross_sal_limit)
    //         return response()->json(['status' => 'error', 'message' => 'The gross salary doesnt fall in the selected category.']);

    //     // to check the duplicacy
    //     // $inb_dup_count      = EmployeePayroll::where('emp_id',$employee_pers->id)->where('status',1)->count();
    //     // if($inb_dup_count>1)
    //     //     return response()->json(['status' => 'error', 'message' => 'Already added.']);

    //     $added = false;
    //     for ($i = $start_month; $i <= $end_month; $i++) {
    //         foreach ($request->employee as $emp) {
    //             // to check the duplicacy
    //             $payroll_dup_check  = EmployeePayroll::where('emp_id', $emp)
    //                 ->where('month', $i)
    //                 ->where('year', $year)
    //                 ->where('status', 1)->count();
    //             // print_r($payroll_dup_check);exit();
    //             if ($payroll_dup_check > 0) {
    //                 // if duplicacy found
    //                 // print_r('duplicate entry found');exit();
    //             } else {

    //                 $basic         = $gross_sal * ($payroll->basic_perc / 100);
    //                 $hra           = $gross_sal * ($payroll->hra_perc / 100);
    //                 $fixed_gross   = $basic + $hra;
    //                 $epfo_employer = $basic * ($payroll->epfo_employer_perc / 100);
    //                 $epfo_employee = $basic * ($payroll->epfo_employee_perc / 100);
    //                 $esic_employer = $gross_sal * ($payroll->esic_employer_perc / 100);
    //                 $esic_employee = $gross_sal * ($payroll->esic_employee_perc / 100);
    //                 $ctc           = $basic + $hra + $epfo_employer + $esic_employer;
    //                 $pt            = $payroll->pt;
    //                 $net_pay       = $ctc - ($pt + $epfo_employee + $esic_employee);

    //                 /*if($category =='Category 1'){
    //     	        $res_array['basic']  		= $gross_sal*0.8;
    //                 $res_array['hra']  			= $gross_sal*0.2;
    //                 $res_array['fixed_gross']  	= $res_array['basic'] + $res_array['hra'];
    //                 $res_array['epfo_employer'] = 0;
    //                 $res_array['epfo_employee'] = 0;
    //                 $res_array['esci_employer'] = 0;
    //                 $res_array['esci_employee'] = 0;
    //                 $res_array['ctc']  			= $res_array['basic'] + $res_array['hra'];
    //                 $res_array['pt']  			= 209;
    //                 $res_array['net_pay']  		= $res_array['ctc'] - $res_array['pt'];
    //             }elseif($category =='Category 2'){

    //     	        $res_array['basic']  		= $gross_sal*0.8;
    //                 $res_array['hra']  			= $gross_sal*0.2;
    //                 $res_array['fixed_gross']  	= $res_array['basic'] + $res_array['hra'];
    //                 $res_array['epfo_employer'] = $res_array['basic']*0.13;
    //                 $res_array['epfo_employee'] = $res_array['basic']*0.12;
    //                 $res_array['esci_employer'] = $gross_sal*0.0325;
    //                 $res_array['esci_employee'] = $gross_sal*0.0075;
    //                 $res_array['ctc']  			= $res_array['basic'] + $res_array['hra'] + $res_array['epfo_employer'] + $res_array['esci_employer'];
    //                 $res_array['pt']  			= 209;
    //                 $res_array['net_pay']  		= $res_array['ctc'] - ($res_array['epfo_employee']+$res_array['esci_employee'] + $res_array['pt']);
    //             }elseif($category =='Category 3'){
    //     	        $res_array['basic']  		= $gross_sal*0.8;
    //                 $res_array['hra']  			= $gross_sal*0.2;
    //                 $res_array['fixed_gross']  	= $res_array['basic'] + $res_array['hra'];
    //                 $res_array['epfo_employer'] = $res_array['basic']*0.13;
    //                 $res_array['epfo_employee'] = $res_array['basic']*0.12;
    //                 $res_array['esci_employer'] = 0;
    //                 $res_array['esci_employee'] = 0;
    //                 $res_array['ctc']  			= $res_array['basic'] + $res_array['hra']+$res_array['epfo_employer'];
    //                 $res_array['pt']  			= 209;
    //                 $res_array['net_pay']  		= $res_array['ctc'] - ($res_array['epfo_employee'] + $res_array['pt']);
    //             }*/

    //                 $emp_payroll                = new EmployeePayroll();
    //                 $emp_payroll->month         = $i;  //month
    //                 $emp_payroll->year          = trim($request->year); //year
    //                 $emp_payroll->emp_id        = $emp;     //$employee_pers->id;
    //                 $emp_payroll->payroll_id    = $payroll->id;
    //                 $emp_payroll->category      = $payroll->category;
    //                 $emp_payroll->gross_sal     = $gross_sal;
    //                 $emp_payroll->basic         = $basic;
    //                 $emp_payroll->hra           = $hra;
    //                 $emp_payroll->fixed_gross   = $fixed_gross;
    //                 $emp_payroll->epfo_employer = $epfo_employer;
    //                 $emp_payroll->epfo_employee = $epfo_employee;
    //                 $emp_payroll->esic_employer = $esic_employer;
    //                 $emp_payroll->esic_employee = $esic_employee;
    //                 $emp_payroll->ctc           = $ctc;
    //                 $emp_payroll->pt            = $pt;
    //                 $emp_payroll->net_pay       = $net_pay;
    //                 $emp_payroll->created_by    = Auth::user()->id;
    //                 $emp_payroll->save();
    //                 $added = true;
    //             }
    //         }
    //     }
    //     if ($added) {
    //         return response()->json(['status' => 'success', 'message' => 'Employee payroll has been submitted successfully. Thank you !']);
    //     } else {
    //         return response()->json(['status' => 'error', 'message' => "No records inserted!"]);
    //     }
    // }

    // new
    public function storePayroll(Request $request)
    {
        // print_r($request->all());exit();
        $rules = [
            // 'employee'   =>'required|numeric', 
            'category'     => 'required',
            'gross_sal'  => 'required|numeric',
            'start_month'  => 'required|numeric',
            // 'end_month'  => 'required|numeric',
            'year'  => 'required|numeric',
        ];

        $customMessages = [];

        $validatedData = $this->validate($request, $rules, $customMessages);

        $gross_sal = trim($request->gross_sal);
        $category  = trim($request->category);
        $start_month  = trim($request->start_month);
        // $end_month  = trim($request->end_month);
        $year  = trim($request->year);

        $payroll       = Payroll::where('category', $category)->where('status', 1)->first();
        if (!$payroll) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Category.']);
        }
        $payroll_id         = $payroll->id;
        $payroll_category   = $payroll->category;
        $gross_sal_limit   = $payroll->gross_sal_limit;

        if ($gross_sal < $gross_sal_limit)
            return response()->json(['status' => 'error', 'message' => 'The gross salary doesnt fall in the selected category.']);

        $added = false;
        // for ($i = $start_month; $i <= $end_month; $i++) {
            foreach ($request->employee as $emp) {
                // to check the duplicacy
                $payroll_dup_check  = EmployeePayroll::where('emp_id', $emp)
                    ->where('month', $start_month)
                    ->where('year', $year)
                    ->where('status', 1)->count();
                if ($payroll_dup_check > 0) {
                    continue;
                } else {

                    $basic         = $gross_sal * ($payroll->basic_perc / 100);
                    $hra           = $gross_sal * ($payroll->hra_perc / 100);
                    $fixed_gross   = $basic + $hra;
                    $epfo_employer = $basic * ($payroll->epfo_employer_perc / 100);
                    $epfo_employee = $basic * ($payroll->epfo_employee_perc / 100);
                    $esic_employer = $gross_sal * ($payroll->esic_employer_perc / 100);
                    $esic_employee = $gross_sal * ($payroll->esic_employee_perc / 100);
                    $ctc           = $basic + $hra + $epfo_employer + $esic_employer;
                    $pt            = $payroll->pt;
                    $net_pay       = $ctc - ($pt + $epfo_employee + $esic_employee);

                    $emp_payroll                = new EmployeePayroll();
                    $emp_payroll->month         = $start_month;  //month
                    $emp_payroll->year          = trim($request->year); //year
                    $emp_payroll->emp_id        = $emp;     //$employee_pers->id;
                    $emp_payroll->payroll_id    = $payroll->id;
                    $emp_payroll->category      = $payroll->category;
                    $emp_payroll->gross_sal     = $gross_sal;
                    $emp_payroll->basic         = $basic;
                    $emp_payroll->hra           = $hra;
                    $emp_payroll->fixed_gross   = $fixed_gross;
                    $emp_payroll->epfo_employer = $epfo_employer;
                    $emp_payroll->epfo_employee = $epfo_employee;
                    $emp_payroll->esic_employer = $esic_employer;
                    $emp_payroll->esic_employee = $esic_employee;
                    $emp_payroll->ctc           = $ctc;
                    $emp_payroll->pt            = $pt;
                    $emp_payroll->net_pay       = $net_pay;
                    $emp_payroll->created_by    = Auth::user()->id;
                    $emp_payroll->save();
                    $added = true;
                }
            }
        // }
        if ($added) {
            return response()->json(['status' => 'success', 'message' => 'Employee payroll has been submitted successfully. Thank you !']);
        } else {
            return response()->json(['status' => 'error', 'message' => "No records inserted!"]);
        }
    }

    public function editPayroll(Request $request, $id)
    {
        // $employees = Employee_personal_detail::where('is_active','Y')->get();
        $categories = Payroll::where('status', 1)->get();
        $emp_payroll    = EmployeePayroll::find($id);
        if (!$emp_payroll)
            return response()->json(['status' => 'error', 'message' => 'Invalid Id.']);

        $employees = Employee_personal_detail::where('id', $emp_payroll->emp_id)->where('is_active', 'Y')->first();
        // print_r($employees);exit();
        // return view('admin.payroll.create',compact('employees','categories','emp_payroll'));
        return view('admin.payroll.edit', compact('employees', 'categories', 'emp_payroll'));
    }

    public function updatePayroll(Request $request, $id)
    {
        // print_r($request->all());exit();
        $rules = [
            'employee'   => 'required|numeric',
            'category'   => 'required',
            'gross_sal'  => 'required|numeric',
            'month'  => 'required|numeric',
            'year'  => 'required|numeric',
        ];

        $customMessages = [];

        $validatedData = $this->validate($request, $rules, $customMessages);

        /*if(Auth::user()->hasRole('super-admin')){
            return response()->json(['status' => 'error','message'=>'No permission, Access Denied'],200);
        }*/

        $employee       = trim($request->employee);
        $gross_sal      = trim($request->gross_sal);
        $category       = trim($request->category);
        $emp_payroll    = EmployeePayroll::find($id);
        if (!$emp_payroll)
            return response()->json(['status' => 'error', 'message' => 'Invalid Id.']);

        if ($emp_payroll->emp_id != $employee)
            return response()->json(['status' => 'error', 'message' => 'Not able to change employee.']);

        $employee_pers       = Employee_personal_detail::find($employee);
        if (!$employee_pers) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Employee id.']);
        }
        $emp_id    = $employee_pers->id;

        if ($gross_sal == '' && is_nan($gross_sal)) {
            return response()->json(['status' => 'error', 'message' => 'Please provide a valid gross Salary', 'data' => 'NA']);
        }

        $payroll       = Payroll::where('category', $category)->where('status', 1)->first();
        if (!$payroll) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Category.']);
        }
        $payroll_id        = $payroll->id;
        $payroll_category  = $payroll->category;
        $gross_sal_limit   = $payroll->gross_sal_limit;

        if ($gross_sal < $gross_sal_limit)
            return response()->json(['status' => 'error', 'message' => 'The gross salary doesnt fall in the selected category.']);

        // $inb_dup_count      = EmployeePayroll::where('emp_id',$employee_pers->id)->where('status',1)->count();
        // if($inb_dup_count>1)
        //     return response()->json(['status' => 'error', 'message' => 'Already added.']);


        $basic         = $gross_sal * ($payroll->basic_perc / 100);
        $hra           = $gross_sal * ($payroll->hra_perc / 100);
        $fixed_gross   = $basic + $hra;
        $epfo_employer = $basic * ($payroll->epfo_employer_perc / 100);
        $epfo_employee = $basic * ($payroll->epfo_employee_perc / 100);
        $esic_employer = $gross_sal * ($payroll->esic_employer_perc / 100);
        $esic_employee = $gross_sal * ($payroll->esic_employee_perc / 100);
        $ctc           = $basic + $hra + $epfo_employer + $esic_employer;
        $pt            = $payroll->pt;
        $net_pay       = $ctc - ($pt + $epfo_employee + $esic_employee);

        $emp_payroll->payroll_id    = $payroll->id;
        $emp_payroll->category      = $payroll->category;
        $emp_payroll->gross_sal     = $gross_sal;
        $emp_payroll->basic         = $basic;
        $emp_payroll->hra           = $hra;
        $emp_payroll->fixed_gross   = $fixed_gross;
        $emp_payroll->epfo_employer = $epfo_employer;
        $emp_payroll->epfo_employee = $epfo_employee;
        $emp_payroll->esic_employer = $esic_employer;
        $emp_payroll->esic_employee = $esic_employee;
        $emp_payroll->ctc           = $ctc;
        $emp_payroll->pt            = $pt;
        $emp_payroll->net_pay       = $net_pay;
        $emp_payroll->updated_by    = Auth::user()->id;
        // $emp_payroll->updated_at    = Auth::user()->id;
        $emp_payroll->save();


        $msg = 'Employee payroll has been updated successfully. Thank you !';

        return response()->json(['status' => 'success', 'message' => $msg]);
    }


    // for export
    public function exportpayroll(Request $request)
    {
        //  print_r($request->all());exit();
        if ($request->has('export')) {

            $employee_payrolls = EmployeePayroll::with('employee')
                ->join('users', 'users.id', '=', 'employee_payrolls.created_by');


            $emp_id = $request->emp_id;
            $month = $request->month;
            $year = $request->year;

            if ($request->has('emp_id') && $emp_id != null) {
                if ($emp_id != 'all') {
                    $employee_payrolls = $employee_payrolls->where('emp_id', $emp_id);
                }
                $employee_payrolls = $employee_payrolls->where('month', $month)
                    ->where('year', $year);
            }

            $employee_payrolls = $employee_payrolls->where('status', 1);
            // ->latest()->get();

            //  print_r($employee_payrolls);exit();

            $filename = 'payroll.xlsx';
            //  $response = Excel::download(new PayrollExport($employee_payrolls->orderBy('created_at', 'DESC')->get()),  $filename);
            $response = Excel::download(new PayrollExport($employee_payrolls->orderBy('created_at', 'DESC')->get(['employee_payrolls.*', 'users.name as added_by_name'])),  $filename);

            ob_end_clean();
            return $response;
        }
    }
}
