@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title"> Edit Employees </h3>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <a href="{{ route('edit.employee_personal',[$id]) }}" class="btn btn-med btn-primary" ><font colour=white>Basic Details</font></a>
                <a href="{{ route('edit.emp_official',[$id]) }}" class="btn btn-med btn-primary"><font colour=white>Employment Details</font></a>
                <a href="{{ route('edit.emp_bank',[$id]) }}" class="btn btn-med btn-primary"><font colour=white>Bank Details</font></a>
                <a href="{{ route('edit.emp_pip',[$id]) }}" class="btn btn-med btn-primary"><font colour=white>PIP Details</font></a>

                <a href="{{ route('edit.emp_salary',[$id]) }}" class="btn btn-med btn-primary" ><font colour=white>Salary</font></a>
            </div>
            <div class="col-md-4" align="right"><a href="{{ route('create.employee') }}"
                                                   class="btn btn-med btn-primary">View Employees</a>
                <a href="{{ route('create.employee') }}"
                   class="btn btn-med btn-primary">Add Employees</a>
            </div>


        </div>
        <div></div>
        <div class="col-md-12">
            <form action="{{ route('store.employee') }}" method="POST">
                @csrf
                <table class="table table-striped">
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-6'>
                                        Employee Name
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="empname" disabled>
                                    </div>
                                    <div class='col-md-6'>
                                        Employee Code
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="emp_code" disabled>
                                    </div>

                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-3'>
                                        Employee Image
                                        <font color="red" size="3">*</font>
                                        <input type="file" name="emp_image" class="form-control">
                                    </div>

                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr><td><div class="form-group">
                                <div class="row">
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="btn btn-primary">EDIT IMAGE</button>
                                    </div>
                                </div>
                            </div></td></tr>
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
