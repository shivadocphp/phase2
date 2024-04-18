@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title"> Add Employees </h3>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <a href="" class="btn btn-med btn-primary">Personal Details</a>
                <a href="" class="btn btn-med btn-primary"><font colour=white>Employment Details</font></a>
                <a href="{{ route('create.emp_bank') }}" class="btn btn-med btn-new-full" disabled
                   style="color: orangered;">Bank Details</a>

            </div>
            <div class="col-md-4" align="right"><a href="{{ route('all.employee') }}"
                                                   class="btn btn-med btn-primary">View Employees</a>
            </div>
        </div>
        <div>
            @if(session('success'))
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
            <form action="{{ route('store.employee_bank') }}" method="POST">
                @csrf
                <table class="table table-striped">
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="row">

                                    <div class='col-md-6'>
                                        Employee Name
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="empname1" value="{{ $emp_name }}"
                                               disabled>
                                        <input type="hidden" class="form-control" name="emp_name"
                                               value="{{ $emp_name }}">
                                    </div>
                                    <div class='col-md-6'>

                                        <input type="hidden" name="emp_id" value="{{$emp_id}}">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">

                                    <div class='col-md-6'>
                                        Bank Name
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="bank_name">
                                        @error('bank_name')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>

                                    <div class='col-md-6'>
                                        Account No
                                        <font color="red" size="3">*</font>
                                        <input type="number" class="form-control" name="account_no" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "15">
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
                                        <input type="text" class="form-control" name="branch_code" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "11">
                                        @error('branch_code')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-6'>
                                        IFSC Code
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="ifsc_code" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "11">
                                        @error('ifsc_code')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="btn btn-primary" name="save_bank"
                                                value="save_bank" title="Save">SAVE AS DRAFT
                                        </button>
                                        <button type="submit" class="btn btn-warning" name="generate"
                                                value="generate_emp_id" title="Save and generate employee code">ADD EMPLOYEE
                                        </button>
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
