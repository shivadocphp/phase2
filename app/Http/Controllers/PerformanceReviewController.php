<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PerformanceReview;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PerformanceReviewEntry;
use App\Models\Employee_personal_detail;

class PerformanceReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = PerformanceReview::paginate(10);
        return view('admin.Forms.performancereview', compact('reviews'));
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
        PerformanceReview::insert([
            'question' => $request->question,
            'is_active' => 1,
        ]);
        return Redirect()->back()->with('success', "Question inserted successfully");
    }

    /****************employee review**************/
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
        //DB::enableQueryLog();

        $details = Employee_personal_detail::leftJoin('employee_official_details', 'employee_official_details.emp_id', 'employee_personal_details.id')
            ->leftJoin('departments', 'departments.id', 'employee_official_details.department_id')
            ->leftJoin('designations', 'designations.id', 'employee_official_details.designation_id')
            ->leftJoin('client_basic_details', 'client_basic_details.id', 'employee_official_details.client_id')
            ->join('users', 'users.emp_code', 'employee_personal_details.emp_code')
            ->select('departments.department', 'designations.designation', 'employee_official_details.joining_date', 'client_basic_details.company_name', 'employee_personal_details.id')
            ->where('employee_personal_details.is_active', 'Y')
            ->where('employee_personal_details.emp_code', Auth::user()->emp_code)
            ->first();
        //dd(DB::getQueryLog());
        $designation = null;
        $designation = isset($details->designation) ? $details->department . "- " . $details->designation : null;
        $joining_date = isset($details->joining_date) ? $details->joining_date : null;
        $company = isset($details->company_name) ? $details->company_name : "Job Store Consulting";
        $emp_id = isset($details->id) ? $details->id : null;

        $reviews = PerformanceReview::get();
        // print_r($details);exit();
        return view('employeeprofile.performancereview', compact('reviews', 'designation', 'company', 'joining_date', 'financial_year', 'emp_id'));
    }
    public function employeereview(Request $request)
    {

        // added
        $check = PerformanceReviewEntry::where('emp_id', $request->emp_id)
            ->where('financial_year', $request->financial_year)
            ->get();
        // print_r($check);exit();
        if ($check->count() > 0) {
            return Redirect()->back()->with('error', 'You have already submitted the review for the period ' . $request->financial_year);
        }

        $emp_id = $request->emp_id;
        $financial_year = $request->financial_year;
        $review_id = $request->review_id;
        $j = 0;
        try {
            DB::beginTransaction();
            for ($i = 0; $i < count($review_id); $i++) {

                $review = array();
                $review['emp_id'] = $emp_id;
                $review['financial_year'] = $financial_year;
                $review['review_id'] = $request->review_id[$i];
                $review['answer'] = $request->answer[$i];
                $review['created_by'] = Auth::user()->id;
                $review['created_at'] = Carbon::now();
                $j =  PerformanceReviewEntry::insert($review);
            }
            if ($j) {
                DB::commit();
                return Redirect()->back()->with('success', "Review submitted successfully");
            } else {
                DB::rollback();
                return Redirect()->back()->with('error', "Error on submitting review");
            }
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect()->back()->with('error', "Error on submitting the review");
        }
    }

    public function employee_filter(Request $request, $emp_id)
    {
        $financial_year = $request->financial;
        $reviews = PerformanceReviewEntry::where('financial_year', $financial_year)->where('emp_id', $emp_id)->get();
        return view('employeeprofile.performancereview', compact('reviews'));
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
        //
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
            'question' => $request->get('question')


        ];

        $i = PerformanceReview::where('id', $id)->update($updateDetails);
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
