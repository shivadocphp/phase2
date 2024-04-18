@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title"> Edit Employees </h3>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <a href="{{ route('edit.employee_personal',[$id]) }}" class="btn btn-med btn-primary"><font
                        colour=white>Personal Details</font></a>
                <a href="{{ route('edit.emp_official',[$id]) }}" class="btn btn-med btn-primary"><font colour=white>Employment
                        Details</font></a>
                <a href="{{ route('edit.emp_bank',[$id]) }}" class="btn btn-med btn-new-full" disabled><font
                        colour=white>Bank Details</font></a>
                <a href="{{ route('edit.emp_pip',[$id]) }}" class="btn btn-med btn-primary"><font colour=white>PIP
                        Details</font></a>

                <a href="{{ route('edit.emp_salary',[$id]) }}" class="btn btn-med btn-primary"><font
                        colour=white>Salary</font></a>
            </div>
            <div class="col-md-4" align="right"><a href="{{ route('all.employee') }}"
                                                   class="btn btn-med btn-primary">View Employees</a>
                <a href="{{ route('create.employee') }}"
                   class="btn btn-med btn-primary">Add Employees</a>
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
            @endif</div>
        <div class="col-md-12">
            <form action="{{ route('update.emp_bank') }}" method="POST">
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
                                        <input type="hidden" class="form-control" name="emp_code"
                                               value="{{ $emp_code }}">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">

                                    <div class='col-md-6'>
                                        Bank Name
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="bank_name"
                                             <?php if($exists==1){?>  value="{{$emp_bank->bank_name}}"<?php } ?>>
                                        @error('bank_name')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror

                                    </div>

                                    <div class='col-md-6'>
                                        Account No
                                        <font color="red" size="3">*</font>
                                        <input type="number" class="form-control" name="account_no" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "15"
                                               <?php if($exists==1){?>   value="{{$emp_bank->account_no}}"<?php } ?>>
                                        @error('account_no')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-6'>
                                        Branch Name
                                        <font color="red" size="3">*</font>
                                        <input type="number" class="form-control" name="branch_code" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "11"
                                               <?php if($exists==1){?>   value="{{$emp_bank->branch_code}}"<?php } ?>>
                                        @error('branch_code')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror

                                    </div>
                                    <div class='col-md-6'>
                                        IFSC Code
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="ifsc_code" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "11"
                                               <?php if($exists==1){?>       value="{{$emp_bank->ifsc_code}}"<?php }?>>
                                        @error('ifsc_code')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12" align="center">
                                        <?php if($exists == 1){?>
                                        <button type="submit" class="btn btn-primary" name="edit_bank"
                                                value="edit_bank">UPDATE
                                        </button>
                                        <?php }else{?>
                                        <button type="submit" class="btn btn-primary" name="edit_bank"
                                                value="add_bank">SAVE
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
    </script>
@endsection

