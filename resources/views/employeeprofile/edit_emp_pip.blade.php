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
            <a href="{{ route('edit.emp_pip',[$id]) }}" class="btn btn-med btn-new-full" disabled=""
                style="color: orangered;">
                <font colour=white>PIP Details</font>
            </a>
            <a href="{{ route('edit.emp_salary',[$id]) }}" class="btn btn-med btn-primary">
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
        <form action="{{ route('update.emp_pip') }}" method="POST">
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
                        <?php if($exists == 0){?>
                        <div class="form-group">
                            <div class="row">

                                <div class='col-md-4'>
                                    1st Review Date
                                    <font color="red" size="3">*</font>
                                    <input type="date" class="form-control" name="first_review" value="{{ old('first_review') }}">
                                    @error('first_review')
                                        <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-4'>
                                    2nd Review Date
                                    <font color="red" size="3">*</font>
                                    <input type="date" class="form-control" name="second_review" value="{{ old('second_review') }}">
                                    @error('second_review')
                                        <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-4'>
                                    3rd Review Date
                                    <font color="red" size="3">*</font>
                                    <input type="date" class="form-control" name="third_review" value="{{ old('third_review') }}">
                                    @error('third_review')
                                        <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-12'>
                                    Comments
                                    <font color="red" size="3">*</font>
                                    <textarea class="form-control" name="review_comment" cols="20" rows="3" > {{ old('review_comment') }}</textarea>
                                </div>
                            </div>
                        </div>
                        @error('review_comment')
                            <span class="text-danger">{{ $message  }}</span>
                        @enderror
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12" align="center">
                                    <button type="submit" class="btn btn-primary" name="edit_pip"
                                        value="create_pip">SAVE
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php }else {?>
                        <div class="form-group">
                            <div class="row">

                                <div class='col-md-4'>
                                    1st Review Date
                                    <font color="red" size="3">*</font>
                                    <input type="date" class="form-control" name="first_review"
                                        value="{{ $emp_pip->first_review }}">
                                </div>
                                <div class='col-md-4'>
                                    2nd Review Date
                                    <font color="red" size="3">*</font>
                                    <input type="date" class="form-control" name="second_review"
                                        value="{{ $emp_pip->second_review }}">
                                </div>
                                <div class='col-md-4'>
                                    3rd Review Date
                                    <font color="red" size="3">*</font>
                                    <input type="date" class="form-control" name="third_review"
                                        value="{{ $emp_pip->third_review }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-12'>
                                    Comments
                                    <font color="red" size="3">*</font>
                                    <textarea class="form-control" name="review_comment" cols="20"
                                        rows="3">{{ $emp_pip->review_comment }}</textarea>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12" align="center">
                                    <button type="submit" class="btn btn-primary" name="edit_pip"
                                        value="update_pip">UPDATE </button>
                                </div>
                            </div>
                        </div>
                        <?php }?>
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
</script>
@endsection