@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Attendance </h3>
</div>
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

@if(Auth::user()->id == 1 || auth()->user()->can('Add Attendance'))
<div class="col-md-2">
    <div class="form-group ">
        <a href="" data-toggle="modal" data-target="#ModalAddAttendance" class="btn  btn-primary mt-6">
            Add Employee Attendance
        </a>
    </div>
</div>
@endif
<div class="container">
    @if(Auth::user()->id == 1 || auth()->user()->can('Monthly Attendance Report'))
    <form method="post" action="{{ route('attendances.monthly')}}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control js-example-basic-single" name="year" id="year" required>
                        <option value="">--Select Year</option>
                        <option value="<?php echo date('Y') - 1; ?>"><?php echo date('Y') - 1; ?></option>
                        <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>

                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control js-example-basic-single" name="month" id="month" required>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-new" id="search">Monthly attendance</button>
                </div>
            </div>
        </div>
    </form>
    @endif

    <div class="form-group">
        <div class="row">
            <div class="col-md-4">
                <label>Employee</label>
                @if(Auth::user()->id == 1 || auth()->user()->can('View All Attendance'))
                <select class="form-control js-example-basic-single" name="employee_id" id="employee_id">
                    <option value="all">--Select Employee</option>
                    @foreach($user_emp as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @else
                <select class="form-control js-example-basic-single" name="employee_id" id="employee_id" disabled>
                    @foreach($user_emp as $user)
                    <!-- <option value="{{ $user->id }}">{{ $user->name }}</option> -->
                    <option value="{{ $user->id }}" {{ auth()->user()->id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @endif
            </div>
            <div class="col-md-2">
                <label>From:</label>
                <input type="date" name="from_date" class="form-control" id="from_date" >
            </div>
            <div class="col-md-2">
                <label>To:</label>
                <input type="date" name="to_date" class="form-control" id="to_date" >
            </div>
            <!-- <div class="col-md-4" style='margin-top: auto;'>
                    <button type="submit" class="btn btn-new" id="filter">Filter</button>
                </div> -->
            <div class="col-md-2">
                <button type="button" class="btn btn-new" id="search-filter" title="Search ">Filter</button>
                <button type="button" class="btn btn-new" id="reset" title="Reset Filter">Reset</button>
            </div>
        </div>
    </div>
    <br>
    <div class="form-group">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered yajra-datatable">
                        <thead style="background-color:#ff751a;">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Employee</th>
                                <th scope="col" class="hideextra" style="width:50px">Date</th>
                                <th scope="col">Shift</th>
                                <th scope="col">In time</th>
                                <th scope="col">Break time</th>
                                <th scope="col">Lunch time</th>
                                <th scope="col">Break time</th>
                                <th scope="col">Out time</th>
                                <!-- <th scope="col">Total Working hours</th> -->
                                <th scope="col">Overall working hours</th>
                                <th scope="col">Overall break hours</th>

                            </tr>
                        </thead>
                        <tbody align="center"></tbody>
                    </table>

                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="view_break" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Attendance Details</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Type</th>
                                    <th>In</th>
                                    <th>Out</th>
                                </tr>
                                <tr>
                                    <td>First Break</td>
                                    <td><input type="text" id="mymbi" disabled></td>
                                    <td><input type="text" id="mymbo" disabled></td>
                                </tr>
                                <tr>
                                    <td>Lunch Break</td>
                                    <td><input type="text" id="mylbi" disabled></td>
                                    <td><input type="text" id="mylbo" disabled></td>
                                </tr>
                                <tr>
                                    <td>Second Break</td>
                                    <td><input type="text" id="myebi" disabled></td>
                                    <td><input type="text" id="myebo" disabled></td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal fade" id="edit_break" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Attendance Details</h4>
                        </div>
                        <form method="POST" action="{{route('update.attendance') }}">
                            {{method_field('patch')}}
                            {{csrf_field()}}

                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <input type="hidden" id="emyid" name="emyid">
                                        <td>Login time:<input type="text" id="elogin" name="elogin"></td>
                                        <td>Logout Time:<input type="text" id="elogout" name="elogout"></td>
                                    </tr>
                                    <tr>
                                        <td>Overall Working Time<input type="text" id="eowt" name="eowt"></td>
                                        <td>Overall break time<input type="text" id="eobt" name="eobt"></td>
                                    </tr>

                                </table>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Type</th>
                                        <th>In</th>
                                        <th>Out</th>
                                    </tr>
                                    <tr>
                                        <td>First Break</td>
                                        <td><input type="text" id="emymbi" name="emymbi"></td>
                                        <td><input type="text" id="emymbo" name="emymbo"></td>
                                    </tr>
                                    <tr>
                                        <td>Lunch Break</td>
                                        <td><input type="text" id="emylbi" name="emylbi"></td>
                                        <td><input type="text" id="emylbo" name="emylbo"></td>
                                    </tr>
                                    <tr>
                                        <td>Second Break</td>
                                        <td><input type="text" id="emyebi" name="emyebi"></td>
                                        <td><input type="text" id="emyebo" name="emyebo"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <div class="modal fade" id="ModalAddAttendance" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-body">
                        <div id="Add-Attendance-Content">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Add Employee Attendance</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <form action="{{ route('attendances.add')}}" class="general-form" method="post">
                                    {{csrf_field()}}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Date</label>
                                                    <input type="date" class="form-control datepicker" id="date" data-target="2" placeholder="Date" name="attendance_date" value="<?= date('d-m-Y') ?>" required="required">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Employee</label>
                                                    <select class="form-control selectpicker select2-show-search custom-select" name="employee_id" id="employee_id" required>
                                                        <option value=""> choose</option>
                                                        @foreach($user_emp as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Login Time</label>
                                                    <input type="time" class="form-control" name="login_time" id="login_time" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Logout Time</label>
                                                    <input type="time" class="form-control" name="logout_time" id="login_time" required />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" name="add_attendance" value="add">
                                            Submit</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-remove"></i> Close</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $('#view_break').on('show.bs.modal', function(event) {
        // console.log('Modal Opened');
        var button = $(event.relatedTarget) // Button that triggered the modal
        var mbi = button.data('mymbi')
        var mbo = button.data('mymbo')
        var lbi = button.data('mylbi')
        var lbo = button.data('mylbo')
        var ebi = button.data('myebi')
        var ebo = button.data('myebo')
        var id = button.data('myid')
        var modal = $(this)

        modal.find('.modal-body #mymbi').val(mbi)
        modal.find('.modal-body #mymbo').val(mbo)
        modal.find('.modal-body #mylbi').val(lbi)
        modal.find('.modal-body #mylbo').val(lbo)
        modal.find('.modal-body #myebi').val(ebi)
        modal.find('.modal-body #myebo').val(ebo)
        modal.find('.modal-body #myid').val(id)


    })
    // for update the attendance
    $('#edit_break').on('show.bs.modal', function(event) {
        // console.log('Modal Opened');
        var button = $(event.relatedTarget) // Button that triggered the modal
        var mbi = button.data('mymbi')
        var mbo = button.data('mymbo')
        var lbi = button.data('mylbi')
        var lbo = button.data('mylbo')
        var ebi = button.data('myebi')
        var ebo = button.data('myebo')
        var id = button.data('myid')
        var login = button.data('login')
        var logout = button.data('logout')
        var owt = button.data('owt')
        var obt = button.data('obt')
        var modal = $(this)

        modal.find('.modal-body #emymbi').val(mbi)
        modal.find('.modal-body #emymbo').val(mbo)
        modal.find('.modal-body #emylbi').val(lbi)
        modal.find('.modal-body #emylbo').val(lbo)
        modal.find('.modal-body #emyebi').val(ebi)
        modal.find('.modal-body #emyebo').val(ebo)
        modal.find('.modal-body #emyid').val(id)
        modal.find('.modal-body #elogin').val(login)
        modal.find('.modal-body #elogout').val(logout)
        modal.find('.modal-body #eowt').val(owt)
        modal.find('.modal-body #eobt').val(obt)

    })

    //to load the employee data to the datatable
    $(document).ready(function() {
        fill_datatable();

        function fill_datatable(employee_id = '', from_date = '', to_date = '') {

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                deferRender: true,
                // ajax: "{{ route('attendances.list') }}",
                ajax: {
                    url: "{{ route('attendances.list') }}",
                    data: {
                        employee_id: employee_id,
                        from_date: from_date,
                        to_date: to_date
                    }
                },

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'emp_code',
                        name: 'emp_code'
                    },
                    {
                        data: 'attendance_date',
                        name: 'attendance_date'
                        /*fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('<a  href="/employees/attendance_details/'+ oData.attendance_date + '" class="show_details" data-toggle="modal"  data-target="#myModal" data-id="' + oData.attendance_date + '">' + oData.attendance_date + '</a>');}*/
                    },
                    {
                        data: 'shift_id',
                        name: 'shift_id'
                    },
                    {
                        data: 'login_time',
                        name: 'login_time'
                    },
                    {
                        data: 'morning_break_in',
                        name: 'morning_break_in'
                    },
                    {
                        data: 'lunch_break_in',
                        name: 'lunch_break_in'
                    },
                    {
                        data: 'evening_break_in',
                        name: 'evening_break_in'
                    },
                    {
                        data: 'logout_time',
                        name: 'logout_time'
                    },
                    // {data: 'working_hours', name: 'working_hours'},
                    {
                        data: 'total_working_hours',
                        name: 'total_working_hours'
                    },
                    {
                        data: 'total_break_hours',
                        name: 'total_break_hours'
                    },
                ]
            });
        }

        $('#search-filter').click(function() {
            var employee_id = $('#employee_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (employee_id != '' && from_date != '' && to_date != '') {
                $('.yajra-datatable').DataTable().destroy();
                fill_datatable(employee_id, from_date, to_date);
            } else {
                alert("Select all filter options");
            }
        });
        $('#reset').click(function() {
            $('#employee_id').val('');
            $('#from_date').val('');
            $('#to_date').val('');
            $('.yajra-datatable').DataTable().destroy();
            fill_datatable();
        });
    });

    $('#myModal').on('hidden.bs.modal', function() {
        location.reload();
    });
</script>
@endsection('admin')