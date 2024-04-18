<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PerformanceAssessment;
use App\Models\PerformanceAssessmentEntry;
use App\Models\PerformanceAssessmentTotal;
use App\Models\PerformanceReviewEntry;
use App\Models\PerformanceReview;
use Carbon\Carbon;
use App\Models\Employee_personal_detail;

class PerformanceAssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assessment = PerformanceAssessment::paginate(10);
        return view('admin.Forms.performanceassessment', compact('assessment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        PerformanceAssessment::insert([
            'assessment_parameter' => $request->assessment_parameter,
            'description' => $request->description,
            'weightage' => $request->weightage,
            'is_active' => 1,
        ]);
        return Redirect()->back()->with('success', "Parameter inserted successfully");
    }



    /********function to store employee assessment****/
    public function employee()
    {
        $year = date('Y');
        $month = date('m');
        if ($month > 4) {
            $year = $year - 1;
        }
        $start_date = date('d-m-Y', strtotime(($year) . '-04-01'));
        $end_date = date('d-m-Y', strtotime(($year + 1) . '-03-31'));

        $financial_year = $start_date . " to " . $end_date;
        $name = Auth::user()->emp_code . " - " . Auth::user()->name;

        $details = Employee_personal_detail::leftJoin('employee_official_details', 'employee_official_details.emp_id', 'employee_personal_details.id')
            ->leftJoin('departments', 'departments.id', 'employee_official_details.department_id')
            ->leftJoin('designations', 'designations.id', 'employee_official_details.designation_id')
            ->leftJoin('client_basic_details', 'client_basic_details.id', 'employee_official_details.client_id')
            ->join('users', 'users.emp_code', 'employee_personal_details.emp_code')
            ->select('departments.department', 'designations.designation', 'employee_official_details.joining_date', 'client_basic_details.company_name', 'employee_personal_details.id')
            ->where('employee_personal_details.is_active', 'Y')
            //->where('employee_personal_details.id',Auth::user()->id)
            ->where('employee_personal_details.emp_code', Auth::user()->emp_code)
            ->first();
        // print_r($details);exit();

        $designation = null;
        $designation = isset($details->designation) ? $details->department . "- " . $details->designation : null;
        //if($details->designation!=null){$designation =$details->department."- ". $details->designation;}
        $joining_date = isset($details->joining_date) ? $details->joining_date : null;

        $emp_id = isset($details->id) ? $details->id : null;



        //$company="Job Store Consulting";
        $company = isset($details->company_name) ? $details->company_name : "Job Store Consulting";
        /*if($details->company_name!=null){
            $company = $details->company_name;
        }*/

        $assessment =  PerformanceAssessment::where('is_active', 1)->get();
        return view('employeeprofile.performanceassessment', compact('assessment', 'financial_year', 'name', 'company', 'designation', 'joining_date', 'emp_id'));
    }

    public function employeescore(Request $request)
    {
        // print_r($request->all());exit();

        $assessment_id = $request->assessment_id;
        try {
            $check = PerformanceAssessmentTotal::where('emp_id', $request->emp_id)
                ->where('financial_year', $request->financial_year)
                ->get();
            if ($check->count() > 0) {
                return Redirect()->back()->with('error', 'You have already submitted the score for the period ' . $request->financial_year);
            }

            $j = 0;
            DB::beginTransaction();
            for ($i = 0; $i < count($assessment_id); $i++) {
                //   echo $i;
                $j =  PerformanceAssessmentEntry::insert([
                    'assessment_id' => $assessment_id[$i],
                    'self_score' => $request->self_score[$i],
                    // 'manager_score'=>$request->manager_score[$i],
                    'financial_year' => $request->financial_year,
                    'emp_id' => $request->emp_id,
                    'created_by' => Auth::user()->id,
                    'created_at' => Carbon::now()
                ]);
            }
            //   exit();

            if ($j) {
                $k = PerformanceAssessmentTotal::insert([
                    'financial_year' => $request->financial_year,
                    'total_self_score' => $request->self_total,
                    'self_comment' => $request->self_comment,
                    'emp_id' => $request->emp_id,
                    'created_by' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
                if ($k) {
                    DB::commit();
                    return Redirect()->back()->with('success', 'Submitted the scores');
                } else {
                    DB::rollback();
                    return Redirect()->back()->with('error', 'Error occured');
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('error', 'Error on entering self score' . $e);
        }
    }

    public function manager_employee($emp_id)
    {
        // $reviews = DB::table('performance_reviews')->get();
        $fn_year = PerformanceAssessmentTotal::groupby('financial_year')->where('emp_id', $emp_id)->get('financial_year');
        // print_r($fn_year);exit();

        $year = date('Y');
        $month = date('m');
        if ($month > 4) {
            $year = $year - 1;
        }
        $start_date = date('d-m-Y', strtotime(($year) . '-04-01'));
        $end_date = date('d-m-Y', strtotime(($year + 1) . '-03-31'));

        $financial_year = $start_date . " to " . $end_date;

        $details = Employee_personal_detail::leftJoin('employee_official_details', 'employee_official_details.emp_id', 'employee_personal_details.id')
            ->leftJoin('departments', 'departments.id', 'employee_official_details.department_id')
            ->leftJoin('designations', 'designations.id', 'employee_official_details.designation_id')
            ->leftJoin('client_basic_details', 'client_basic_details.id', 'employee_official_details.client_id')
            ->join('users', 'users.emp_code', 'employee_personal_details.emp_code')
            ->select('users.name', 'departments.department', 'designations.designation', 'employee_official_details.joining_date', 'client_basic_details.company_name')
            ->where('employee_personal_details.is_active', 'Y')
            ->where('employee_personal_details.id', $emp_id)
            ->first();

        $name = $details->name;
        $designation = null;
        if ($details->designation != null) {
            $designation = $details->department . "- " . $details->designation;
        }
        $joining_date = $details->joining_date;

        $company = "Job Store Consulting";
        if ($details->company_name != null) {
            $company = $details->company_name;
        }
        $assessment =  PerformanceAssessment::leftjoin('performance_assessment_entries', 'performance_assessment_entries.assessment_id', 'performance_assessments.id')
            ->where('performance_assessment_entries.financial_year', $financial_year)
            ->where('performance_assessment_entries.emp_id', $emp_id)
            ->where('performance_assessments.is_active', 1)
            ->get(['performance_assessments.id as id', 'performance_assessments.assessment_parameter', 'performance_assessments.description', 'performance_assessment_entries.self_score', 'performance_assessments.weightage', 'performance_assessment_entries.manager_score']);
        $assessment_total = PerformanceAssessmentTotal::where('performance_assessment_totals.financial_year', $financial_year)
            ->where('performance_assessment_totals.emp_id', $emp_id)
            ->first();
        $reviews =  PerformanceReview::leftjoin('performance_review_entries', 'performance_review_entries.review_id', 'performance_reviews.id')
            ->where('performance_review_entries.emp_id', $emp_id)
            ->where('performance_review_entries.financial_year', $financial_year)
            ->where('performance_reviews.is_active', 1)
            ->get(['performance_reviews.id', 'performance_reviews.question', 'performance_review_entries.answer']);

        // print_r($fn_year);exit();
        return view('employeeprofile.performanceassessmentmanager', compact('assessment', 'financial_year', 'name', 'company', 'designation', 'joining_date', 'emp_id', 'fn_year', 'reviews', 'assessment_total'));
    }
    public function managerscore(Request $request)
    {

        // print_r($request->all());exit();
        $emp_id = $request->emp_id;
        $financial_year = $request->financial_year;
        $assessment_id = $request->assessment_id;

        try {

            $j = 0;
            DB::beginTransaction();

            for ($i = 0; $i < count($assessment_id); $i++) {
                /* if($request->manager_score[$i]=='')
                {
                    $manager_score = 0;
                }else
                {
                    $manager_score = $request->manager_score[$i];
                }
                echo $manager_score;*/

                $update1 = [
                    'manager_score' => intval($request->manager_score[$i]),
                    'updated_by' => Auth::user()->id,
                ];

                $j =  PerformanceAssessmentEntry::where('assessment_id', $assessment_id[$i])
                    ->where('emp_id', $emp_id)
                    ->where('financial_year', $financial_year)
                    ->update($update1);
            }

            if ($j) {

                $k = PerformanceAssessmentTotal::where('emp_id', $emp_id)
                    ->where('financial_year', $financial_year)
                    ->update([
                        'total_manager_score' => $request->manager_total,
                        'manager_comment' => $request->manager_comment,
                        'updated_by' => Auth::user()->id,
                        'updated_at' => Carbon::now()
                    ]);


                if ($k) {
                    DB::commit();
                    return Redirect()->back()->with('success', 'Submitted the scores successfully');
                } else {
                    DB::rollback();
                    return Redirect()->back()->with('error1', 'Error occured');
                }
            } else {
                DB::rollback();
                return Redirect()->back()->with('error 2', 'Error occured');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('error', 'Error on entering self score' . $e);
        }
    }
    public function manager_employee_filter(Request $request, $emp_id)
    {
        // print_r($request->all());exit();
        // $reviews = DB::table('performance_reviews')->get();
        $fn_year = PerformanceAssessmentTotal::groupby('financial_year')->where('emp_id', $emp_id)->get('financial_year');
        $financial_year = $request->financial;
        if ($financial_year == null) {

            $year = date('Y');
            $month = date('m');
            if ($month < 4) {
                $year = $year - 1;
            }
            $start_date = date('d-m-Y', strtotime(($year) . '-04-01'));
            $end_date = date('d-m-Y', strtotime(($year + 1) . '-03-31'));

            $financial_year = $start_date . " to " . $end_date;
        }

        $details = Employee_personal_detail::leftJoin('employee_official_details', 'employee_official_details.emp_id', 'employee_personal_details.id')
            ->leftJoin('departments', 'departments.id', 'employee_official_details.department_id')
            ->leftJoin('designations', 'designations.id', 'employee_official_details.designation_id')
            ->leftJoin('client_basic_details', 'client_basic_details.id', 'employee_official_details.client_id')
            ->join('users', 'users.emp_code', 'employee_personal_details.emp_code')
            ->select('users.name', 'departments.department', 'designations.designation', 'employee_official_details.joining_date', 'client_basic_details.company_name')
            ->where('employee_personal_details.is_active', 'Y')
            ->where('employee_personal_details.id', $emp_id)
            ->first();

        $name = $details->name;
        $designation = null;
        if ($details->designation != null) {
            $designation = $details->department . "- " . $details->designation;
        }
        $joining_date = $details->joining_date;

        $company = "Job Store Consulting";
        if ($details->company_name != null) {
            $company = $details->company_name;
        }
        $assessment =  PerformanceAssessment::leftjoin('performance_assessment_entries', 'performance_assessment_entries.assessment_id', 'performance_assessments.id')
            ->where('performance_assessment_entries.financial_year', $financial_year)
            ->where('performance_assessment_entries.emp_id', $emp_id)
            ->where('performance_assessments.is_active', 1)
            ->get(['performance_assessments.id as id', 'performance_assessments.assessment_parameter', 'performance_assessments.description', 'performance_assessment_entries.self_score', 'performance_assessments.weightage', 'performance_assessment_entries.manager_score']);
        $assessment_total = PerformanceAssessmentTotal::where('performance_assessment_totals.financial_year', $financial_year)
            ->where('performance_assessment_totals.emp_id', $emp_id)
            ->first();
        $reviews =  PerformanceReview::leftjoin('performance_review_entries', 'performance_review_entries.review_id', 'performance_reviews.id')
            ->where('performance_review_entries.emp_id', $emp_id)
            ->where('performance_review_entries.financial_year', $financial_year)
            ->where('performance_reviews.is_active', 1)
            ->get(['performance_reviews.id', 'performance_reviews.question', 'performance_review_entries.answer']);

        // print_r($assessment);exit();
        return view('employeeprofile.performanceassessmentmanager', compact('assessment', 'financial_year', 'name', 'company', 'designation', 'joining_date', 'emp_id', 'fn_year', 'reviews', 'assessment_total'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $updateDetails = [
            'assessment_parameter' => $request->get('assessment_parameter'),
            'description' => $request->get('description'),
            'weightage' => $request->get('weightage'),

        ];

        $i = PerformanceAssessment::where('id', $id)->update($updateDetails);
        return Redirect()->back()->with('success', "Form entry successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
