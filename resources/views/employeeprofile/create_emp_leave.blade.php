@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Leave Management </h3>
    @can('Manage Report')
    <span>
        <button type="button" class="btn btn-new" id="export-excel-button" title="Export">Export Leave</button>
    </span>
    @endcan
    <span>
        <button type="button" class="btn btn-new" style="color: orangered;" data-toggle="modal" data-target="#leaveModal">
            Mark Leave
        </button>
        <button type="button" class="btn btn-new" style="color: orangered;" data-toggle="modal" data-target="#leaveModal2">
            Apply Leave
        </button>
    </span>
</div>
<div class="container">

    @if(Auth::user()->id == 1 || auth()->user()->can('View All Leave'))
    <div class="form-group">
        <div class="row">
            <div class="col-md-4">
                <label>Employee</label>
                <select class="form-control js-example-basic-single" name="employee_id" id="employee_id">
                    <option value="">--Select Employee</option>
                    <option value="all">All</option>    
                    @foreach($user_emp as $user)
                    <option value="{{ $user->emp_code }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>From:</label>
                <input type="date" name="from_date" class="form-control" id="from_date">
            </div>
            <div class="col-md-2">
                <label>To:</label>
                <input type="date" name="to_date" class="form-control" id="to_date">
            </div>
            <!-- <span> -->
            <!-- <div class="col-md-2"> -->
            <button type="button" class="btn btn-new" id="search-filter" title="Search ">Filter</button>
            <button type="button" class="btn btn-new" id="reset" title="Reset Filter">Reset</button>
            <!-- </div> -->
            <!-- </span> -->
        </div>
    </div>
    <br>
    @endif

    <div class="row">
        @if(session('success'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered yajra-datatable">
                    <thead style="background-color: #ff751a">
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Employee Code</th>
                            <th scope="col">Leave Type</th>
                            <th scope="col">Days</th>
                            <th scope="col">Date</th>
                            <th scope="col">Reason</th>
                            <!-- <th scope="col">Comments</th>-->
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="col-md-12">

        <div class="modal fade" id="details" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Leave Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">Leave Type : <input type="text" id="myleavetype" class="form-control" disabled></div>

                            <div class="col">Requested Days<input type="text" id="myrequestdays" class="form-control" disabled></div>
                        </div>
                        <div class="row">
                            <div class="col">Leave Status <input type="text" id="myleave_status" class="form-control" disabled></div>
                            <div class="col">Requested Hours<input type="text" id="myrequested_hours" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">Requested from<input type="text" id="myfrom" class="form-control" disabled>
                            </div>
                            <div class="col">Requested to <input type="text" id="myto" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">Time From<input type="text" id="mytimefrom" class="form-control" disabled>
                            </div>
                            <div class="col"> Time to<input type="text" id="mytimeto" class="form-control" disabled>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            Reason<textarea cols="50" rows="2" id="myreason" class="form-control" disabled></textarea>
                        </div>
                        <div class="row">
                            <div class="col">Approved Days<input type="text" class="form-control" id="myapprovedays" disabled></div>
                            <div class="col">Approved Hours<input type="text" class="form-control" id="myapproved_hours" disabled></div>
                        </div>
                        <div class="row">
                            <div class="col">Approved From<input type="text" class="form-control" id="myapprovefrom" disabled></div>
                            <div class="col">Approved To<input type="text" class="form-control" id="myapproveto" disabled></div>
                        </div>
                        <br>
                        <div class="row">
                            Comments<textarea cols="50" rows="2" id="mycomments" class="form-control" disabled></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="attendance" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Attendance Details</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tr>
                                <td>Login time:<input type="text" id="login" class="form-control" disabled></td>
                                <td>Logout Time:<input type="text" id="logout" class="form-control" disabled></td>
                            </tr>
                            <tr>
                                <td>Overall Working Time<input type="text" id="owt" class="form-control" disabled></td>
                                <td>Overall break time<input type="text" id="obt" class="form-control" disabled></td>
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
                                <td><input type="text" id="mymbi" class="form-control" disabled></td>
                                <td><input type="text" id="mymbo" class="form-control" disabled></td>
                            </tr>
                            <tr>
                                <td>Lunch Break</td>
                                <td><input type="text" id="mylbi" class="form-control" disabled></td>
                                <td><input type="text" id="mylbo" class="form-control" disabled></td>
                            </tr>
                            <tr>
                                <td>Second Break</td>
                                <td><input type="text" id="myebi" class="form-control" disabled></td>
                                <td><input type="text" id="myebo" class="form-control" disabled></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="approve" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Leave Approval</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('approve.employee_leave') }}" method="POST">
                        {{method_field('patch')}}
                        {{csrf_field()}}
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <input type="hidden" name="id" id="myid">
                                    <div class="col">
                                        From
                                        <input type="date" name="approve_from" id="myapprovefrom" class="form-control" required>
                                    </div>
                                    <div class="col">
                                        To
                                        <input type="date" name="approve_to" id="myapproveto" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col">Comments<textarea name="comments" class="form-control" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary">Approve</button>
                        </div>
                </div>
                </form>

            </div>
        </div>

        <div class="modal fade" id="disapprove" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Leave Disapproval</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('disapprove.employee_leave') }}" method="POST">
                        {{method_field('patch')}}
                        {{csrf_field()}}

                        <div class="modal-body">
                            <input type="hidden" name="id" id="myid">
                            <div class="col">Comments<textarea name="comments" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary">Disapprove</button>
                        </div>
                </div>
                </form>

            </div>
        </div>

        <div class="modal fade" id="cancell" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Leave Cancellation</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('cancel.employee_leave') }}" method="POST">
                        {{method_field('patch')}}
                        {{csrf_field()}}

                        <div class="modal-body">
                            <input type="hidden" name="id" id="myid">
                            <div class="col">Comments<textarea name="comments" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary">Cancel</button>
                        </div>
                </div>
                </form>

            </div>
        </div>


        <div class="modal fade" id="leaveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Mark Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @if(auth()->user()->id == 1 || auth()->user()->can('Mark Leave'))
                    <form action="{{ route('store.employee_leave') }}" method="POST">
                        {{method_field('patch')}}
                        {{csrf_field()}}
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <input type="hidden" name="casual_leave" value="0">
                                    <input type="hidden" name="sick_leave" id="sick_leave" value="1">
                                    <div class="col">
                                        <label>For Employee</label>
                                        <select class="form-control js-example-basic-single" name="employee_id" id="employee_id" required>
                                            <option value="">--Select Employee</option>
                                            @foreach($user_emp as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label>Leave Type</label>
                                        <select class="form-control" name="leavetype_id" id="leavetype" required>
                                            <option value="">--Select--</option>
                                            @foreach($leaves as $leave)
                                            <option value="{{$leave->id}}">{{ $leave->leavetype }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="casual" style="display: none"></div>
                            <div class="row" id="casual_duration_single" style="display: none"></div>
                            <div class="row" id="casual_duration_multiple" style="display: none"></div>
                            <div class="row" id="sick" style="display: none"></div>
                            <div class="row" id="permission" style="display: none"></div>
                            <div class="row" id="halfdaycasual" style="display: none"></div>
                            <div class="row" id="halfdaysick" style="display: none"></div>
                            <div class="row" id="halfdaysick" style="display: none"></div>
                            <div class="row" id="compensation" style="display: none"></div>
                            <div class="row" id="comp_req" style="display: none"></div>
                            <div class="row" id="medical" style="display: none"></div>
                            <div class="row" id="others" style="display: none">
                                <div class="form-group">

                                    <div class="row">
                                        <div class="col ">
                                            <label>Duration of leave</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="leave_duration" id="single" checked="checked" value="single">
                                                <label class="form-check-label" for="single">Single Day</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="leave_duration" id="multiple" value="multiple">
                                                <label class="form-check-label" for="multiple">Multiple Days</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="leave_duration" id="hours" value="hours">
                                                <label class="form-check-label" for="hours">Hours</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row" id="single1">
                                        <div class="col">
                                            <label>Required On</label>
                                            <input type="date" class="form-control" name="required_on" id="required_on">
                                        </div>

                                    </div>
                                    <div class="row" id="multiple1" style="display: none;">
                                        <div class="col">
                                            <label>Required From</label>
                                            <input type="date" class="form-control" name="required_from" id="required_from" onkeyup="message()">
                                        </div>
                                        <div class="col">
                                            <label>Required to</label>
                                            <input type="date" class="form-control" name="required_to" id="required_to">
                                        </div>
                                    </div>
                                    <div class="row" id="hours1" style="display: none;">
                                        <div class="col">
                                            <div class="col">
                                                <label>Required On</label>
                                                <input type="date" class="form-control" name="required_day" id="required_day">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label>Time from</label>
                                            <select name="total_hours_leave_from" class="form-control" id="total_hours_leave_from">
                                                <option value="">--Select--</option>
                                                @for($i=9;$i<=18;$i++) <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                            </select>
                                        </div>
                                        <div class="col"><label>Time To</label>
                                            <select name="total_hours_leave_to" class="form-control" id="total_hours_leave_to">
                                                <option value="">--Select--</option>
                                                @for($i=9;$i<=18;$i++) <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Reason</label>
                                        <textarea class="form-control" cols="10" rows="2" name="reason" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="modal fade" id="leaveModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Apply Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @if(auth()->user()->id != 1 && auth()->user()->can('Add Leave'))
                    <form action="{{ route('store.employee_leave') }}" method="POST">
                        {{method_field('patch')}}
                        {{csrf_field()}}
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label><b style="color: orangered;">
                                                <?php if ($casual_leaves_available > 0) { ?>
                                                    Casual Leaves Available: {{ $casual_leaves_available }} Days
                                                    <input type="hidden" name="casual_leave" id="casual_leave" value="{{ $casual_leaves_available }}">
                                                    <br>
                                                <?php } else { ?>
                                                    <input type="hidden" name="casual_leave" value="0">
                                                <?php }
                                                if ($sick_leave_pending <= 1) { ?>
                                                    <input type="hidden" name="sick_leave" id="sick_leave" value="{{ $sick_leave_pending }}">
                                                    Available Sick Leave : {{ $sick_leave_pending }}
                                                <?php } else { ?>
                                                    No Sick Leave Available
                                                    <input type="hidden" name="sick_leave" value="0">
                                                <?php } ?>
                                            </b> </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label>Leave Type</label>
                                        <select class="form-control" name="leavetype_id" id="leavetype" required>
                                            <option value="">--Select--</option>
                                            @foreach($leaves as $leave)
                                            <option value="{{$leave->id}}">{{ $leave->leavetype }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="casual" style="display: none"></div>
                            <div class="row" id="casual_duration_single" style="display: none"></div>
                            <div class="row" id="casual_duration_multiple" style="display: none"></div>
                            <div class="row" id="sick" style="display: none"></div>
                            <div class="row" id="permission" style="display: none"></div>
                            <div class="row" id="halfdaycasual" style="display: none"></div>
                            <div class="row" id="halfdaysick" style="display: none"></div>
                            <div class="row" id="halfdaysick" style="display: none"></div>
                            <div class="row" id="compensation" style="display: none"></div>
                            <div class="row" id="comp_req" style="display: none"></div>
                            <div class="row" id="medical" style="display: none"></div>
                            <div class="row" id="others" style="display: none">
                                <div class="form-group">

                                    <div class="row">
                                        <div class="col ">
                                            <label>Duration of leave</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="leave_duration" id="single" checked="checked" value="single">
                                                <label class="form-check-label" for="single">Single Day</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="leave_duration" id="multiple" value="multiple">
                                                <label class="form-check-label" for="multiple">Multiple Days</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="leave_duration" id="hours" value="hours">
                                                <label class="form-check-label" for="hours">Hours</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row" id="single1">
                                        <div class="col">
                                            <label>Required On</label>
                                            <input type="date" class="form-control" name="required_on" id="required_on">
                                        </div>

                                    </div>
                                    <div class="row" id="multiple1" style="display: none;">
                                        <div class="col">
                                            <label>Required From</label>
                                            <input type="date" class="form-control" name="required_from" id="required_from" onkeyup="message()">
                                        </div>
                                        <div class="col">
                                            <label>Required to</label>
                                            <input type="date" class="form-control" name="required_to" id="required_to">
                                        </div>
                                    </div>
                                    <div class="row" id="hours1" style="display: none;">
                                        <div class="col">
                                            <div class="col">
                                                <label>Required On</label>
                                                <input type="date" class="form-control" name="required_day" id="required_day">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label>Time from</label>
                                            <select name="total_hours_leave_from" class="form-control" id="total_hours_leave_from">
                                                <option value="">--Select--</option>
                                                @for($i=9;$i<=18;$i++) <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                            </select>
                                        </div>
                                        <div class="col"><label>Time To</label>
                                            <select name="total_hours_leave_to" class="form-control" id="total_hours_leave_to">
                                                <option value="">--Select--</option>
                                                @for($i=9;$i<=18;$i++) <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Reason</label>
                                        <textarea class="form-control" cols="10" rows="2" name="reason" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>

<script type="text/javascript">
    $('#approve').on('show.bs.modal', function(event) {
        // console.log('Modal Opened');
        var button = $(event.relatedTarget) // Button that triggered the modal
        var myfrom = button.data('myfrom')
        var myto = button.data('myto')
        var id = button.data('myid')
        var modal = $(this)

        modal.find('.modal-body #myapprovefrom').val(myfrom)
        modal.find('.modal-body #myapproveto').val(myto)
        modal.find('.modal-body #myid').val(id)
    })
    $('#disapprove').on('show.bs.modal', function(event) {
        // console.log('Modal Opened');
        var button = $(event.relatedTarget) // Button that triggered the modal
        var id = button.data('myid')
        var modal = $(this)

        modal.find('.modal-body #myid').val(id)

    })
    $('#cancell').on('show.bs.modal', function(event) {
        // console.log('Modal Opened');
        var button = $(event.relatedTarget) // Button that triggered the modal
        var id = button.data('myid')
        var modal = $(this)

        modal.find('.modal-body #myid').val(id)

    })
    $('#details').on('show.bs.modal', function(event) {
        // console.log('Modal Opened');
        var button = $(event.relatedTarget) // Button that triggered the modal
        var id = button.data('myid')
        var leavetype = button.data('leavetype')
        var requested_days = button.data('myrequestdays')
        var requested_from = button.data('myfrom')
        var requested_to = button.data('myto')
        var requested_hours = button.data('myrequested_hours')
        var time_from = button.data('mytimefrom')
        var time_to = button.data('mytimeto')
        var approved_days = button.data('myapprovedays')
        var approved_from = button.data('myapprovefrom')
        var approved_to = button.data('myapproveto')
        var approved_hours = button.data('myapproved_hours')
        var reason = button.data('myreason')
        var comments = button.data('mycomments')
        var worked_on = button.data('myworked_on')
        var leave_status = button.data('myleave_status')
        var modal = $(this)

        modal.find('.modal-body #myid').val(id)
        modal.find('.modal-body #myleavetype').val(leavetype)
        modal.find('.modal-body #myrequestdays').val(requested_days)
        modal.find('.modal-body #myfrom').val(requested_from)
        modal.find('.modal-body #myto').val(requested_to)
        modal.find('.modal-body #myrequested_hours').val(requested_hours)
        modal.find('.modal-body #mytimefrom').val(time_from)
        modal.find('.modal-body #mytimeto').val(time_to)
        modal.find('.modal-body #myapprovedays').val(approved_days)
        modal.find('.modal-body #myapprovefrom').val(approved_from)
        modal.find('.modal-body #myapproveto').val(approved_to)
        modal.find('.modal-body #myapproved_hours').val(approved_hours)
        modal.find('.modal-body #myreason').val(reason)
        modal.find('.modal-body #mycomments').val(comments)
        modal.find('.modal-body #myworked_on').val(worked_on)
        modal.find('.modal-body #myleave_status').val(leave_status)


    })

    // no need
    $('#attendance').on('show.bs.modal', function(event) {
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

        modal.find('.modal-body #mymbi').val(mbi)
        modal.find('.modal-body #mymbo').val(mbo)
        modal.find('.modal-body #mylbi').val(lbi)
        modal.find('.modal-body #mylbo').val(lbo)
        modal.find('.modal-body #myebi').val(ebi)
        modal.find('.modal-body #myebo').val(ebo)
        modal.find('.modal-body #myid').val(id)
        modal.find('.modal-body #login').val(login)
        modal.find('.modal-body #logout').val(logout)
        modal.find('.modal-body #owt').val(owt)
        modal.find('.modal-body #obt').val(obt)


    })

    // to fetch all the leaves in table
    // $(function() {
    $(document).ready(function() {
        fill_datatable();

        function fill_datatable(employee_id = '', from_date = '', to_date = '') {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                deferRender: true,
                // ajax: "{{ route('leaves.list') }}",
                ajax: {
                    url: "{{ route('leaves.list') }}",
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
                        data: 'leave_type',
                        name: 'leave_type'
                    },
                    {
                        data: 'days',
                        name: 'days'
                    },
                    {
                        data: 'required',
                        name: 'required'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    //     {data: 'comments', name: 'comments'},
                    {
                        data: 'status',
                        name: 'status'
                    },
                    //   {data: 'comments', name: 'comments'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
                language: {
                    emptyTable: "No data available in the table"
                },
                initComplete: function() {
                    if (table.rows().count() === 0) {
                        $('#export-excel-button').hide(); // Hide the export button
                    }
                }
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

        // export button
        $('#export-excel-button').click(function() {
            // var export = $('#export-excel-button').val();
            var employee_id = $('#employee_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var url = "{{ route('leaves.list') }}"; // if to add flters
            url += "?export=export";
            if (employee_id !== '') {
                url += "&employee_id=" + employee_id + "&from_date=" + from_date + "&to_date=" + to_date;
            }
            window.location.href = url;
        });


    });

    $(document).ready(function() {
        $('#leavetype').on('change', function() {
            var idlevel = this.value;
            if (idlevel == 1) {
                $("#casual").show();
                $("#comp_req").hide();
                $("#permission").hide();
                $("#halfdaycasual").hide();
                $("#halfdaysick").hide();
                $("#compensation").hide();
                $("#medical").hide();
                $("#sick").hide();
                $("#others").hide();
                var casual_leave = $("#casual_leave").val();
                // if (casual_leave > 0) {
                $("#casual").html("<div class='col'><label>Duration of leave</label>" +
                    "<select name='leave_durationc' id='leave_durationc' class='form-control' required>" +
                    "<option value=''>--Select--</option>" +
                    "<option value='single'>Single Day</option>" +
                    "<option value='multiple'>Multiple Days</option></select></div>");
                // } else {
                //     alert('No casual leaves pending');
                // }
                $("#required_on").removeAttr('required');
            } else if (idlevel == 2) {
                // console.log("hi test");
                $("#casual").hide();
                $("#casual_duration_single").hide();
                $("#casual_duration_multiple").hide();
                $("#permission").hide();
                $("#halfdaycasual").hide();
                $("#halfdaysick").hide();
                $("#compensation").hide();
                $("#comp_req").hide();
                $("#medical").hide();
                $("#sick").show();
                $("#others").hide();

                $("#required_on").removeAttr('required');
                $("#leave_durationc").removeAttr('required');
                $("#required_day_p").removeAttr('required');
                $("#total_hours_leave_from").removeAttr('required');
                $("#total_hours_leave_to").removeAttr('required');
                $("#required_day").removeAttr('required');

                var sick_leave = $("#sick_leave").val();
                if (sick_leave > 0) {
                    $("#sick").html("<div class='col'><label>Required On</label>" +
                        "<input type='date' class='form-control' name='sick_required_on' required></div>");
                } else {
                    alert("No sick leave available for this month");
                }
            } else if (idlevel == 3) {
                $("#casual").hide();
                $("#casual_duration_single").hide();
                $("#casual_duration_multiple").hide();
                $("#permission").show();
                $("#halfdaycasual").hide();
                $("#halfdaysick").hide();
                $("#compensation").hide();
                $("#comp_req").hide();
                $("#medical").hide();
                $("#sick").hide();
                $("#others").hide();
                $("#permission").html('<div class="row"><div class="col"><label>Required On</label>' +
                    '<input type="date" class="form-control" name="required_day_p" required></div>');

                $("#permission").append(
                    '<div class="col" >From<select name="total_hours_leave_from_p" class="form-control" required >' +
                    '<option value="">From Time</option><option value="9">9</option><option value="10">10</option><option value="11">11</option>' +
                    '<option value="12">12</option><option value="13">13</option><option value="14">14</option>' +
                    '<option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option></select></div>' +
                    '<div class="col" >To<select name="total_hours_leave_to_p" class="form-control" required>' +
                    '<option value="">To Time</option><option value="9">9</option><option value="10">10</option><option value="11">11</option>' +
                    '<option value="12">12</option><option value="13">13</option><option value="14">14</option>' +
                    '<option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option></select></div></div>'
                );
                $("#required_on").removeAttr('required');
                $("#sick_required_on").removeAttr('required');

            } else if (idlevel == 5) {
                $("#casual").hide();
                $("#casual_duration_single").hide();
                $("#casual_duration_multiple").hide();
                $("#permission").hide();
                $("#halfdaycasual").hide();
                $("#halfdaysick").hide();
                $("#compensation").show();
                // $("#comp_req").hide();
                $("#medical").hide();
                $("#sick").hide();
                $("#others").hide();

                $("#compensation").html("<div class='col'><label>Worked on</label> <span style='color:green;'>choose date on which you worked<span>" +
                    "<input type='date' class='form-control' name='worked_on' id='worked_on' oninput='work_check()' required></div>"
                );
                $("#required_on").removeAttr('required');

            } else if (idlevel == 6) {
                $("#casual").hide();
                $("#casual_duration_single").hide();
                $("#casual_duration_multiple").hide();
                $("#permission").hide();
                $("#halfdaycasual").show();
                $("#halfdaysick").hide();
                $("#compensation").hide();
                $("#comp_req").hide();
                $("#medical").hide();
                $("#sick").hide();
                $("#others").hide();
                $("#halfdaycasual").html('<div class="row"><div class="col"><label>Required On</label>' +
                    '<input type="date" class="form-control" name="required_day_cas" required></div>');

                $("#halfdaycasual").append(
                    '<div class="col" >From<select name="total_hours_leave_from_cas" class="form-control" required>' +
                    '<option value="">Select</option><option value="morning">Morning</option>' +
                    '<option value="afternoon">Afternoon</option>' +
                    '</select></div></div>');
                $("#required_on").removeAttr('required');

            } else if (idlevel == 7) {
                $("#casual").hide();
                $("#casual_duration_single").hide();
                $("#casual_duration_multiple").hide();
                $("#permission").hide();
                $("#halfdaycasual").hide();
                $("#halfdaysick").show();
                $("#compensation").hide();
                $("#comp_req").hide();
                $("#medical").hide();
                $("#sick").hide();
                $("#others").hide();
                $("#halfdaysick").html('<div class="row"><div class="col"><label>Required On</label>' +
                    '<input type="date" class="form-control" name="required_day_sick" required></div>');

                $("#halfdaysick").append(
                    '<div class="col" >From<select name="total_hours_leave_from_sick" class="form-control" required>' +
                    '<option value="">Select</option><option value="morning">Morning</option>' +
                    '<option value="afternoon">Afternoon</option>' +
                    '</select></div></div>');
                $("#required_on").removeAttr('required');

            } else {
                $("#casual").hide();
                $("#casual_duration_single").hide();
                $("#casual_duration_multiple").hide();
                $("#permission").hide();
                $("#halfdaycasual").hide();
                $("#halfdaysick").hide();
                $("#compensation").hide();
                $("#medical").hide();
                $("#sick").hide();
                $("#others").show();

            }
        });

    });
    $(document).on('change', 'select', function() {

        $('#leave_durationc').on('change', function() {
            var idlevel = this.value;

            if (idlevel == "single") {
                $("#required_from_c").val("");
                $("#casual_duration_single").show();
                $("#casual_duration_multiple").hide();
                $("#casual_duration_single").html(
                    "<div class=row><div class='col'><label>Required On</label>" +
                    "<input type='date' name='required_on_c' id='required_on_c' class='form-control' required></div></div>");
            } else if (idlevel == "multiple") {
                $("#required_on_c").val("");
                $("#casual_duration_single").hide();
                $("#casual_duration_multiple").show();
                $("#casual_duration_multiple").html("<div class=row><div class='col'>Required From" +
                    "<input type='date' name='required_from_c' id='required_from_c' class='form-control' required>" +
                    "Required To" +
                    "<input type='date' name='required_to_c'  class='form-control' required>" +
                    "</div></div>");
            }
        });
    });

    $(function() {
        $("#required_on").prop('required', true);

        $("input[name='leave_duration']").click(function() {
            if ($("#multiple").is(":checked")) {
                $("#multiple1").show();
                $("#single1").hide();
                $("#hours1").hide();
                $("#required_from").prop('required', true);
                $("#required_to").prop('required', true);
                $("#required_on").removeAttr('required');
                $("#required_day").removeAttr('required');
                $("#total_hours_leave_from").removeAttr('required');
                $("#total_hours_leave_to").removeAttr('required');

            } else if ($("#single").is(":checked")) {
                $("#multiple1").hide();
                $("#single1").show();
                $("#hours1").hide();
                $("#required_on").prop('required', true);
                $("#required_from").removeAttr('required');
                $("#required_to").removeAttr('required');
            } else if ($("#hours").is(":checked")) {
                $("#multiple1").hide();
                $("#single1").hide();
                $("#hours1").show();
                $("#required_day").prop('required', true);
                $("#total_hours_leave_from").prop('required', true);
                $("#total_hours_leave_to").prop('required', true);
                $("#required_on").removeAttr('required');
                $("#required_from").removeAttr('required');
                $("#required_to").removeAttr('required');
            }
        });


    });

    // to check the worked date
    function work_check() {
        var idlevel = $("#worked_on").val();
        var getday = new Date(idlevel).getDay();
        $.ajax({
            url: "{{url('api/comp-work')}}",
            type: "POST",
            data: {
                id: idlevel,
                _token: '{{csrf_token()}}'
            },
            success: function(result) {
                // alert(result);
                if (result == 1) {
                    $("#comp_req").show();
                    $("#comp_req").html("<div class='col'><label >Required On</label>" +
                        "<input type='date' name='required_comp' class='form-control' required></div>");
                } else if (!result) {
                    alert("You are not eligible. Please check the entered date!!!!");
                    $("#comp_req").hide();
                    $("#worked_on").val('');
                    return;
                }
            }
        });
    }
</script>

@endsection