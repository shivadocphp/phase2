@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Edit Employees </h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('edit.employee_personal',[$id]) }}" class="btn btn-med btn-primary">
                <font colour=white>Personal Details</font>
            </a>
            <a href="{{ route('edit.emp_official',[$id]) }}" class="btn btn-med btn-primary">
                <font colour=white>Employment
                    Details</font>
            </a>
            <a href="{{ route('edit.emp_bank',[$id]) }}" class="btn btn-med btn-primary">
                <font colour=white>Bank
                    Details</font>
            </a>
            <a href="{{ route('edit.emp_pip',[$id]) }}" class="btn btn-med btn-primary">
                <font colour=white>PIP
                    Details</font>
            </a>
            <a href="{{ route('edit.emp_salary',[$id]) }}" class="btn btn-med btn-new-full" disabled=""
                style="color: orangered;">
                <font colour=white>Salary</font>
            </a>
        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.employee') }}" class="btn btn-med btn-primary">View
                Employees</a>
            <a href="{{ route('create.employee') }}" class="btn btn-med btn-primary">Add Employees</a>
        </div>
    </div>
    <div>@if(session('success'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
    </div>
    <div class="col-md-12">
        <form action="{{ route('update.emp_salary') }}" method="POST">
            {{method_field('patch')}}
            @csrf
            <table class="table table-striped">

                <tr>
                    <td>

                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-6'>
                                    Employee Name
                                    <font color="red" size="3">*</font>
                                    <input type="text" class="form-control" name="empname" value="{{ $emp_name }}"
                                        disabled>
                                </div>
                                <div class='col-md-6'>
                                    Employee Code
                                    <font color="red" size="3">*</font>
                                    <input type="text" class="form-control" name="emp_code" value="{{ $emp_code }}"
                                        disabled>
                                    <input type="hidden" class="form-control" name="emp_id" value="{{ $id }}">
                                    <input type="hidden" class="form-control" name="emp_code" value="{{ $emp_code }}">

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-3'>
                                    Fixed Basic Pay
                                    <font color="red" size="3">*</font>
                                    <input type="number" name="fixed_basic" class="form-control" id="fixed_basic"
                                        <?php if($exists == 1){?> value="{{$emp_salary->fixed_basic}}" <?php }?>
                                        onkeyup="sum()">
                                    @error('fixed_basic')
                                        <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-3'>
                                    Fixed HRA
                                    <font color="red" size="3">*</font>
                                    <input type="number" name="fixed_hra" class="form-control" <?php if($exists == 1){?>
                                        value="{{$emp_salary->fixed_hra}}" <?php }?>>
                                    @error('fixed_hra')
                                        <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-3'>
                                    Fixed Conveyance
                                    <font color="red" size="3">*</font>
                                    <input type="number" class="form-control" name="fixed_conveyance"
                                        <?php if($exists == 1){?> value="{{$emp_salary->fixed_conveyance}}" <?php }?>>
                                    @error('fixed_conveyance')
                                        <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-3'>
                                    Casual Leave Available
                                    <input type="number" class="form-control" name="casual_leave_available"
                                        <?php if($exists == 1){?> value="{{$emp_salary->casual_leave_available}}"
                                        <?php }?>>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-3'>
                                    Employer PF
                                    <font color="red" size="3">*</font>
                                    <input type="number" class="form-control" name="employer_pf"
                                        <?php if($exists == 1){?> value="{{$emp_salary->employer_pf}}" <?php }?>>
                                    @error('employer_pf')
                                        <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-3'>
                                    Employer ESI
                                    <font color="red" size="3">*</font>
                                    <input type="number" class="form-control" name="employer_esi"
                                        <?php if($exists == 1){?> value="{{$emp_salary->employer_esi}}" <?php }?>>
                                    @error('employer_esi')
                                        <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-3'>
                                    Employee PF

                                    <input type="number" class="form-control" name="employee_pf"
                                        <?php if($exists == 1){?> value="{{$emp_salary->employee_pf}}" <?php }?>>
                                </div>
                                <div class='col-md-3'>
                                    Employee ESI

                                    <input type="number" class="form-control" name="employee_esi"
                                        <?php if($exists == 1){?> value="{{$emp_salary->employee_esi}}" <?php }?>>
                                </div>


                            </div>
                        </div>

                        <div class="form-group">

                            <div class="row">
                                <div clss="col-md-3">
                                    Monthly target
                                    <input type="number" name="monthly_target" id="monthly_target" class="form-control" readonly
                                        <?php if($exists == 1){?> value="{{$emp_salary->monthly_target}}" <?php }?>>
                                </div>
                                <div clss="col-md-3">
                                    Start Date(Target)
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        <?php if($exists == 1){?> value="{{$emp_salary->start_date}}" <?php }?>>
                                </div>
                                <div class='col-md-6'>
                                    Comments
                                    <textarea class="form-control" name="comments" rows="2"
                                        cols="12"><?php if($exists == 1){?> {{$emp_salary->comments}}<?php }?></textarea>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12" align="center">
                                    <?php if($exists == 1){?>
                                    <button type="submit" class="btn btn-primary" name="edit_salary"
                                        value="edit_salary">UPDATE
                                    </button><?php }else{?>
                                    <button type="submit" class="btn btn-primary" name="edit_salary"
                                        value="add_salary">ADD
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script>
function ShowHideDiv(chkPassport) {
    var same_address_display = document.getElementById("same_address_display");
    same_address_display.style.display = same_address_com.checked ? "none" : "block";
}

function sum() {
    var txtFirstNumberValue = document.getElementById('fixed_basic').value;
    // var txtSecondNumberValue = document.getElementById('txt2').value;
    if (txtFirstNumberValue == "")
        txtFirstNumberValue = 0;


    var result = parseInt(txtFirstNumberValue) * 5;
    if (!isNaN(result)) {
        document.getElementById('monthly_target').value = result;
    }
}
</script>
@endsection