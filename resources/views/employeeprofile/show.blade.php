@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Employees Details </h3>
    <a data-toggle="collapse" href="#full_profile" role="button" aria-expanded="false"
    aria-controls="collapseExample" class="btn  btn-med btn-primary" style="color: orangered;align-content: left">
    View Full Profile
</a>

</div>
<div class="container">
    <div class="row">
        <div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{-- <div class="collapse" id="full_profile" style="display: inline;">
                        <div class="card card-body">
                            <table class="table table-striped">
                                <tr align="center">
                                    <th colspan="3" style="background-color: black;color: orangered">PERSONAL DETAILS</th>
                                </tr>
                                <tr><th class="col-md-1">Employee Code</th><th class="col-md-1">:</th><th class="col-md-1">{{ $personal->emp_code }}</th></tr>
                                <tr><th class="col-md-1">Name</th><th class="col-md-1">:</th><th class="col-md-1">{{ $personal->subtitle." ".$personal->firstname." ".$personal->middlename." ".$personal->lastname }}</th></tr>
                                <tr><th class="col-md-1">Gender</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->gender }}</td></tr>
                                <tr><th class="col-md-1">Date of Birth</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->dob }}</td></tr>
                                <tr><th class="col-md-1">Contact Details</th><th class="col-md-1">:</th><td class="col-md-1">Landline: {{ $personal->landline}},Mobile:{{ $personal->mobile1 }},Alternate Mobile:{{ $personal->mobile2 }}</td></tr>
                                <tr><th class="col-md-1">Differently Abled</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->diff_abled }}</td></tr>
                                <tr><th class="col-md-1">Permanant Address</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->p_address1 }},{{ $personal->city }},{{ $personal->state }},{{ $personal->country }},Pincode-{{ $personal->p_address_pincode }}</td></tr>
                                <tr><th class="col-md-1">Communication Address</th><th class="col-md-1">:</th><td class="col-md-1"></td></tr>
                                <tr><th class="col-md-1">Aadhaar Card No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->aadhaar_no }}</td></tr>
                                <tr><th class="col-md-1">Pan No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->pan_no }}</td></tr>
                                <tr><th class="col-md-1">Driving Licence No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->dl_no }} expires on {{ $personal->dl_expiry_date }} </td></tr>
                                <tr><th class="col-md-1">Qualification</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->qualificationlevel}} - {{$personal->qualification}}</td></tr>
                                <tr><th class="col-md-1">Guardian Details</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->guardian_name }},{{ $personal->guardian_type }},{{ $personal->guardian_mobile }}</td></tr>

                                <tr align="center"><th colspan="3" style="background-color: black;color: orangered">EMPLOYEMENT DETAILS</th></tr>
                                <?php if($o_exists==1){?>
                                <tr><th class="col-md-1">Employement Mode</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->employementmode }}</td></tr>
                                <?php if($official->employementmode!="JSC"){?>
                                <tr><th class="col-md-1">Client </th><th class="col-md-1">:</th><td class="col-md-1"></td></tr>
                                <tr><th class="col-md-1">Deployed Location</th><th class="col-md-1">:</th><td class="col-md-1"></td></tr><?php }?>
                                <tr><th class="col-md-1">Department</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->department }}</td></tr>
                                <tr><th class="col-md-1">Designation</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->designation }}</td></tr>
                                <tr><th class="col-md-1">Official Email ID</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->official_emailid }}</td></tr>
                                <tr><th class="col-md-1">ESIC No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->esic_no }}</td></tr>
                                <tr><th class="col-md-1">PF No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->pf_no }} </td></tr>
                                <tr><th class="col-md-1">UAN No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->uan_no }}</td></tr>
                                <tr><th class="col-md-1">BGV</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->bgv }}</td></tr>
                                <tr><th class="col-md-1">Date of Joining</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official-> joining_date}}</td></tr>
                                <tr><th class="col-md-1">Date of Relieving</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->relieving_date }}</td></tr>
                                <?php }else { echo "<tr><td colspan='3'><b>No EMPLOYEMENT  Details to display</b></td></tr>";}?>
                                <tr align="center"><th colspan="3" style="background-color: black;color: orangered">BANK DETAILS</th></tr>
                                <?php if($b_exists!=0){?>
                                <tr><th class="col-md-1">Bank Name</th><th class="col-md-1">:</th><th class="col-md-1">{{ $bank->bank_name }}</th></tr>
                                <tr><th class="col-md-1">Branch Name</th><th class="col-md-1">:</th><th class="col-md-1">{{ $bank->branch_code }}</th></tr>
                                <tr><th class="col-md-1">Account No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $bank->account_no }}</td></tr>
                                <tr><th class="col-md-1">IFSC Code</th><th class="col-md-1">:</th><td class="col-md-1">{{ $bank->ifsc_code }}</td></tr>
                                <?php }else { echo "<tr><td colspan='3'><b>No BANK  Details to display</b></td></tr>";}?>
                                <tr align="center"><th colspan="3" style="background-color: black;color: orangered">PIP DETAILS</th></tr>
                                <?php if($exists==1){?>

                                <tr><th class="col-md-1">First Review</th><th class="col-md-1">:</th><td class="col-md-1">{{ $pip->first_review }}</td></tr>
                                <tr><th class="col-md-1">Second Review</th><th class="col-md-1">:</th><td class="col-md-1">{{ $pip->second_review}}</td></tr>
                                <tr><th class="col-md-1">Third Review</th><th class="col-md-1">:</th><td>Landline: {{ $pip->third_review}}</td></tr>
                                <tr><th class="col-md-1">Comments</th><th class="col-md-1">:</th><td class="col-md-1">{{ $pip->review_comment }}</td></tr>
                                <?php }else { echo "<tr><td colspan='3'><b>No PIP  Details to display</b></td></tr>";}?>
                                <tr align="center"><th colspan="3" style="background-color: black;color: orangered">SALARY DETAILS</th></tr>
                                <?php if($s_exists==1){?>

                                <tr><th class="col-md-1">Fixed Basic Pay</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->fixed_basic }}</td></tr>
                                <tr><th class="col-md-1">Fixed HRA</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->fixed_hra }}</td></tr>
                                <tr><th class="col-md-1">Fixed Conveyance</th><th class="col-md-1">:</th><td class="col-md-1">Landline: {{ $salary->fixed_conveyance}}</td></tr>
                                <tr><th class="col-md-1">Employer PF</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->employer_pf }}</td></tr>
                                <tr><th class="col-md-1">Employer ESI</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->employer_esi }}</td></tr>
                                <tr><th class="col-md-1">Employee PF</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->employee_pf }}</td></tr>
                                <tr><th class="col-md-1">Employee ESI</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->employee_esi }}</td></tr>
                                <tr><th class="col-md-1">Casual Leave Available</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->casual_leave_available }}</td></tr>


                                <?php }else { echo "<tr><td colspan='3'><b>No  Salary Details to display</b></td></tr>";}?>
                            </table>
                        </div>
                    </div> --}}
                    <div class="row" style="align-content: center;">
                        <div >  
                            <a class="btn btn-med btn-warning checkTab" {{-- data-toggle="collapse" --}} {{-- href="#personal" --}} role="button" aria-expanded="false" aria-controls="collapseExample" data-link-val="fullProfileDetLink">Full Profile
                            </a>
                        </div>
                        <div>  
                            <a class="btn btn-med btn-warning checkTab" {{-- data-toggle="collapse" --}} {{-- href="#personal" --}} role="button" aria-expanded="false" aria-controls="collapseExample" data-link-val="personalDetLink">Personal Details
                            </a>
                        </div>
                        <div>
                            <a {{-- data-toggle="collapse" --}} {{-- href="#official" --}} role="button" aria-expanded="false" aria-controls="collapseExample" class="btn btn-med btn-warning checkTab"  data-link-val="employmentDetLink">
                            Employement Details
                            </a>
                        </div>
                        <div> 
                            <a {{-- data-toggle="collapse" --}} {{-- href="#bank" --}} role="button" aria-expanded="false" aria-controls="collapseExample" class="btn  btn-med btn-warning checkTab"  data-link-val="bankDetLink">Bank Details
                            </a>
                        </div>
                        <div> 
                            <a {{-- data-toggle="collapse" --}} {{-- href="#pip" --}} role="button" aria-expanded="false" aria-controls="collapseExample" class="btn btn-med btn-warning checkTab"  data-link-val="pipDetLink">PIP Details
                            </a>
                        </div>
                        <div> 
                            <a {{-- data-toggle="collapse" --}} {{-- href="#salary" --}} role="button" aria-expanded="false" aria-controls="collapseExample" class="btn  btn-med btn-warning checkTab"  data-link-val="salDetLink">Salary Details
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" {{-- class="collapse" --}} id="full_profile" style="display: inline;">
                            <div class="card card-body">
                                <table class="table table-striped">
                                    <tr align="center">
                                        <th colspan="3" style="background-color: black;color: orangered">PERSONAL DETAILS</th>
                                    </tr>
                                    <tr><th class="col-md-1">Employee Code</th><th class="col-md-1">:</th><th class="col-md-1">{{ $personal->emp_code }}</th></tr>
                                    <tr><th class="col-md-1">Name</th><th class="col-md-1">:</th><th class="col-md-1">{{ $personal->subtitle." ".$personal->firstname." ".$personal->middlename." ".$personal->lastname }}</th></tr>
                                    <tr><th class="col-md-1">Email</th><th class="col-md-1">:</th><th class="col-md-1">{{ $user->email }}</th></tr>
                                    <tr><th class="col-md-1">Password</th><th class="col-md-1">:</th><th class="col-md-1">{{ $user->original_password}}</th></tr>
                                    <tr><th class="col-md-1">Gender</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->gender }}</td></tr>
                                    <tr><th class="col-md-1">Date of Birth</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->dob }}</td></tr>
                                    <tr><th class="col-md-1">Contact Details</th><th class="col-md-1">:</th><td class="col-md-1">Landline: {{ $personal->landline}},Mobile:{{ $personal->mobile1 }},Alternate Mobile:{{ $personal->mobile2 }}</td></tr>
                                    <tr><th class="col-md-1">Differently Abled</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->diff_abled }}</td></tr>
                                    <tr><th class="col-md-1">Permanant Address</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->p_address1 }},{{ $personal->city }},{{ $personal->state }},{{ $personal->country }},Pincode-{{ $personal->p_address_pincode }}</td></tr>
                                    <tr><th class="col-md-1">Communication Address</th><th class="col-md-1">:</th><td class="col-md-1"></td></tr>
                                    <tr><th class="col-md-1">Aadhaar Card No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->aadhaar_no }}</td></tr>
                                    <tr><th class="col-md-1">Pan No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->pan_no }}</td></tr>
                                    <tr><th class="col-md-1">Driving Licence No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->dl_no }} expires on {{ $personal->dl_expiry_date }} </td></tr>
                                    <tr><th class="col-md-1">Qualification</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->qualificationlevel}} - {{$personal->qualification}}</td></tr>
                                    <tr><th class="col-md-1">Guardian Details</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->guardian_name }},{{ $personal->guardian_type }},{{ $personal->guardian_mobile }}</td></tr>

                                    <tr align="center"><th colspan="3" style="background-color: black;color: orangered">EMPLOYEMENT DETAILS</th></tr>
                                    <?php if($o_exists==1){?>
                                    <tr><th class="col-md-1">Employement Mode</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->employementmode }}</td></tr>
                                    <?php if($official->employementmode!="JSC"){?>
                                    <tr><th class="col-md-1">Client </th><th class="col-md-1">:</th><td class="col-md-1"></td></tr>
                                    <tr><th class="col-md-1">Deployed Location</th><th class="col-md-1">:</th><td class="col-md-1"></td></tr><?php }?>
                                    <tr><th class="col-md-1">Department</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->department }}</td></tr>
                                    <tr><th class="col-md-1">Designation</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->designation }}</td></tr>
                                    <tr><th class="col-md-1">Official Email ID</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->official_emailid }}</td></tr>
                                    <tr><th class="col-md-1">ESIC No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->esic_no }}</td></tr>
                                    <tr><th class="col-md-1">PF No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->pf_no }} </td></tr>
                                    <tr><th class="col-md-1">UAN No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->uan_no }}</td></tr>
                                    <tr><th class="col-md-1">BGV</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->bgv }}</td></tr>
                                    <tr><th class="col-md-1">Date of Joining</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official-> joining_date}}</td></tr>
                                    <tr><th class="col-md-1">Date of Relieving</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->relieving_date }}</td></tr>
                                    <?php }else { echo "<tr><td colspan='3'><b>No EMPLOYEMENT  Details to display</b></td></tr>";}?>
                                    <tr align="center"><th colspan="3" style="background-color: black;color: orangered">BANK DETAILS</th></tr>
                                    <?php if($b_exists!=0){?>
                                    <tr><th class="col-md-1">Bank Name</th><th class="col-md-1">:</th><th class="col-md-1">{{ $bank->bank_name }}</th></tr>
                                    <tr><th class="col-md-1">Branch Name</th><th class="col-md-1">:</th><th class="col-md-1">{{ $bank->branch_code }}</th></tr>
                                    <tr><th class="col-md-1">Account No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $bank->account_no }}</td></tr>
                                    <tr><th class="col-md-1">IFSC Code</th><th class="col-md-1">:</th><td class="col-md-1">{{ $bank->ifsc_code }}</td></tr>
                                    <?php }else { echo "<tr><td colspan='3'><b>No BANK  Details to display</b></td></tr>";}?>
                                    <tr align="center"><th colspan="3" style="background-color: black;color: orangered">PIP DETAILS</th></tr>
                                    <?php if($exists==1){?>

                                    <tr><th class="col-md-1">First Review</th><th class="col-md-1">:</th><td class="col-md-1">{{ $pip->first_review }}</td></tr>
                                    <tr><th class="col-md-1">Second Review</th><th class="col-md-1">:</th><td class="col-md-1">{{ $pip->second_review}}</td></tr>
                                    <tr><th class="col-md-1">Third Review</th><th class="col-md-1">:</th><td>Landline: {{ $pip->third_review}}</td></tr>
                                    <tr><th class="col-md-1">Comments</th><th class="col-md-1">:</th><td class="col-md-1">{{ $pip->review_comment }}</td></tr>
                                    <?php }else { echo "<tr><td colspan='3'><b>No PIP  Details to display</b></td></tr>";}?>
                                    <tr align="center"><th colspan="3" style="background-color: black;color: orangered">SALARY DETAILS</th></tr>
                                    <?php if($s_exists==1){?>

                                    <tr><th class="col-md-1">Fixed Basic Pay</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->fixed_basic }}</td></tr>
                                    <tr><th class="col-md-1">Fixed HRA</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->fixed_hra }}</td></tr>
                                    <tr><th class="col-md-1">Fixed Conveyance</th><th class="col-md-1">:</th><td class="col-md-1">Landline: {{ $salary->fixed_conveyance}}</td></tr>
                                    <tr><th class="col-md-1">Employer PF</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->employer_pf }}</td></tr>
                                    <tr><th class="col-md-1">Employer ESI</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->employer_esi }}</td></tr>
                                    <tr><th class="col-md-1">Employee PF</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->employee_pf }}</td></tr>
                                    <tr><th class="col-md-1">Employee ESI</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->employee_esi }}</td></tr>
                                    <tr><th class="col-md-1">Casual Leave Available</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->casual_leave_available }}</td></tr>


                                    <?php }else { echo "<tr><td colspan='3'><b>No  Salary Details to display</b></td></tr>";}?>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12" {{-- class="collapse" --}} id="personal" style="display: none;">
                            <div class="card card-body">
                                <table>
                                    <tr><th class="col-md-1">Employee Code</th><th class="col-md-1">:</th><th class="col-md-1">{{ $personal->emp_code }}</th></tr>
                                    <tr><th class="col-md-1">Name</th><th class="col-md-1">:</th><th class="col-md-1">{{ $personal->subtitle." ".$personal->firstname." ".$personal->middlename." ".$personal->lastname }}</th></tr>
                                    <tr><th class="col-md-1">Gender</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->gender }}</td></tr>
                                    <tr><th class="col-md-1">Date of Birth</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->dob }}</td></tr>
                                    <tr><th class="col-md-1">Contact Details</th><th class="col-md-1">:</th><td class="col-md-1">Landline: {{ $personal->landline}},Mobile:{{ $personal->mobile1 }},Alternate Mobile:{{ $personal->mobile2 }}</td></tr>
                                    <tr><th class="col-md-1">Differently Abled</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->diff_abled }}</td></tr>
                                    <tr><th class="col-md-1">Permanant Address</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->p_address1 }},{{ $personal->city }},{{ $personal->state }},{{ $personal->country }},Pincode-{{ $personal->p_address_pincode }}</td></tr>
                                    <tr><th class="col-md-1">Communication Address</th><th class="col-md-1">:</th><td class="col-md-1"></td></tr>
                                    <tr><th class="col-md-1">Aadhaar Card No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->aadhaar_no }}</td></tr>
                                    <tr><th class="col-md-1">Pan No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->pan_no }}</td></tr>
                                    <tr><th class="col-md-1">Driving Licence No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->dl_no }} expires on {{ $personal->dl_expiry_date }} </td></tr>
                                    <tr><th class="col-md-1">Qualification</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->qualificationlevel}} - {{$personal->qualification}}</td></tr>
                                    <tr><th class="col-md-1">Guardian Details</th><th class="col-md-1">:</th><td class="col-md-1">{{ $personal->guardian_name }},{{ $personal->guardian_type }},{{ $personal->guardian_mobile }}</td></tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12" {{-- class="collapse" --}} id="official" style="display: none;">
                            <div class="card card-body">
                                <?php if($o_exists!=0){?>
                                <table>
                                    <tr><th class="col-md-1">Employement Mode</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->employementmode }}</td></tr>
                                    <?php if($official->employementmode!="JSC"){?>
                                    <tr><th class="col-md-1">Client </th><th class="col-md-1">:</th><td class="col-md-1"></td></tr>
                                    <tr><th class="col-md-1">Deployed Location</th><th class="col-md-1">:</th><td class="col-md-1"></td></tr><?php }?>
                                    <tr><th class="col-md-1">Department</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->department }}</td></tr>
                                    <tr><th class="col-md-1">Designation</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->designation }}</td></tr>
                                    <tr><th class="col-md-1">Official Email ID</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->official_emailid }}</td></tr>
                                    <tr><th class="col-md-1">ESIC No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->esic_no }}</td></tr>
                                    <tr><th class="col-md-1">PF No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->pf_no }} </td></tr>
                                    <tr><th class="col-md-1">UAN No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->uan_no }}</td></tr>
                                    <tr><th class="col-md-1">BGV</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->bgv }}</td></tr>
                                    <tr><th class="col-md-1">Date of Joining</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official-> joining_date}}</td></tr>
                                    <tr><th class="col-md-1">Date of Relieving</th><th class="col-md-1">:</th><td class="col-md-1">{{ $official->relieving_date }}</td></tr>

                                </table>
                                <?php }else { echo "<tr><td colspan='3'><b>No Employment  Details to display</b></td></tr>";}?>
                            </div>
                        </div>

                        <div class="col-md-12" {{-- class="collapse" --}} id="bank" style="display: none;">
                            <div class="card card-body">
                                <?php if($b_exists!=0){?>
                                <table>
                                    <tr><th class="col-md-1">Bank Name</th><th class="col-md-1">:</th><td class="col-md-1">{{ $bank->bank_name }}</td></tr>
                                    <tr><th class="col-md-1">Branch Name</th><th class="col-md-1">:</th><td class="col-md-1">{{ $bank->branch_code }}</td></tr>
                                    <tr><th class="col-md-1">Account No</th><th class="col-md-1">:</th><td class="col-md-1">{{ $bank->account_no }}</td></tr>
                                    <tr><th class="col-md-1">IFSC Code</th><th class="col-md-1">:</th><td class="col-md-1">{{ $bank->ifsc_code }}</td></tr>
                                </table>
                                <?php }else { echo "<tr><td colspan='3'><b>No BANK  Details to display</b></td></tr>";}?>

                            </div>
                        </div>

                        <div class="col-md-12" {{-- class="collapse" --}} id="pip" style="display: none;">
                            <div class="card card-body">
                                <?php if($exists==1){?>
                                <table>
                                    <tr><th class="col-md-1">First Review</th><th class="col-md-1">:</th><td class="col-md-1">{{ $pip->first_review }}</td></tr>
                                    <tr><th class="col-md-1">Second Review</th><th class="col-md-1">:</th><td class="col-md-1">{{ $pip->second_review}}</td></tr>
                                    <tr><th class="col-md-1">Third Review</th><th class="col-md-1">:</th><td>Landline: {{ $pip->third_review}}</td></tr>
                                    <tr><th class="col-md-1">Comments</th><th class="col-md-1">:</th><td class="col-md-1">{{ $pip->review_comment }}</td></tr>
                                </table><?php }else { echo "No PIP  Details to display";}?>
                            </div>
                        </div>

                        <div class="col-md-12" {{-- class="collapse" --}} id="salary" style="display: none;">
                            <div class="card card-body">
                                <?php if($s_exists==1){?>
                                <table>
                                    <tr><th class="col-md-1">Fixed Basic Pay</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->fixed_basic }}</td></tr>
                                    <tr><th class="col-md-1">Fixed HRA</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->fixed_hra }}</td></tr>
                                    <tr><th class="col-md-1">Fixed Conveyance</th><th class="col-md-1">:</th><td class="col-md-1">Landline: {{ $salary->fixed_conveyance}}</td></tr>
                                    <tr><th class="col-md-1">Employer PF</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->employer_pf }}</td></tr>
                                    <tr><th class="col-md-1">Employer ESI</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->employer_esi }}</td></tr>
                                    <tr><th class="col-md-1">Employee PF</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->employee_pf }}</td></tr>
                                    <tr><th class="col-md-1">Employee ESI</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->employee_esi }}</td></tr>
                                    <tr><th class="col-md-1">Casual Leave Available</th><th class="col-md-1">:</th><td class="col-md-1">{{ $salary->casual_leave_available }}</td></tr>


                                </table><?php }else { echo "No  Salary Details to display";}?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click', '.checkTab', function (e) {
        var self    = $(this),
            check_link  = self.data('link-val');
        if(check_link=='personalDetLink'){
            $('#personal').show();
            $('#official').hide();
            $('#bank').hide();
            $('#pip').hide();
            $('#salary').hide();

            $('#full_profile').hide();
        }else if(check_link=='employmentDetLink'){
            $('#personal').hide();
            $('#official').show();
            $('#bank').hide();
            $('#pip').hide();
            $('#salary').hide();

            $('#full_profile').hide();
        }else if(check_link=='bankDetLink'){
            $('#personal').hide();
            $('#official').hide();
            $('#bank').show();
            $('#pip').hide();
            $('#salary').hide();

            $('#full_profile').hide();
        }else if(check_link=='pipDetLink'){
            $('#personal').hide();
            $('#official').hide();
            $('#bank').hide();
            $('#pip').show();
            $('#salary').hide();

            $('#full_profile').hide();
        }else if(check_link=='salDetLink'){
            $('#personal').hide();
            $('#official').hide();
            $('#bank').hide();
            $('#pip').hide();
            $('#salary').show();
            $('#full_profile').hide();
            
        }else{
            $('#full_profile').show();
            $('#personal').hide();
            $('#official').hide();
            $('#bank').hide();
            $('#pip').hide();
            $('#salary').hide();
        }
        /*$('#enquiry_type').attr('required',false);
        if(selected_val=="New Enquiry"){
            $('.call_type_enq').show();
            $('#enquiry_type').attr('required',true);
        }
        else{
            $('.call_type_enq').hide();
            $('#sub_type').prop('required',false);
            $('#brand').prop('required',false);
            $('.call_type_enq_sub').hide();
            $('.call_type_enq_brand').hide();
            $('.call_type_enq_model').hide();
            $('#specification').html('');
            $('#mrp').val('');
            $('#mop').val('');
            $('.enq_model_det').hide();
        }
        $('#enquiry_type').val('');
        $('#enquiry_type').trigger('change');*/
    });
</script>
@endsection

