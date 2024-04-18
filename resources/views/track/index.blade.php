@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Employee Performance</h3>

</div>
<div class="container">
    <div class="row">

        <div class="col-md-14">
            <div class="tabset">
                <!-- Tab 1 -->
                <input type="radio" name="tabset" id="tab1" aria-controls="daily" checked>
                <label for="tab1">Daily Track</label>
                <!-- Tab 2 -->
                <input type="radio" name="tabset" id="tab2" aria-controls="monthly">
                <label for="tab2">Monthly Track</label>
                <input type="radio" name="tabset" id="tab3" aria-controls="client">
                <label for="tab3">Client Track</label>
                <div class="tab-panels">
                    <section id="daily" class="tab-panel">
                        <form method="post">
                            <table class="table table-responsive" width="100%">
                                <tr>
                                    <td>Recruiter<select class="form-control" name="emp_id">
                                            <option value="">--Select Recruiter--</option>
                                            @foreach($employee as $value)
                                            <option value="{{$value->id}}">{{$value->emp_code." - ". $value->subtitle . "" . $value->firstname . " " . $value->middlename . " " . $value->lastname }}</option>
                                            @endforeach
                                        </select></td>
                                    <td> From<input type="date" name="daily" id="daily" class="form-control"></td>
                                    <td><br>
                                        <input type="submit" name="daily_report" id="daily_report" value="Get Daily Report" class="btn btn-primary">
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <div class="row">
                            <div class="card">
                                @if(session('success'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>{{ session('success') }}</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif

                                <div class="card-body">

                                    <table class="table table-bordered yajra-datatable-daily nowrap">
                                        <thead style="border-color: #ff751a">
                                            <tr align="center">
                                                <th scope="col">No.</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Source</th>
                                                <th scope="col">Company Name</th>
                                                <th scope="col">Candidate Name</th>
                                                <th scope="col">Phone No</th>
                                                <th scope="col">Position</th>
                                                <th scope="col">Feedback</th>
                                                <th scope="col">Notice Period</th>
                                                <!-- <th scope="col">Added On</th>-->
                                                <th scope="col">Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody style="text-align: center">

                                        </tbody>
                                    </table>



                                </div>
                            </div>
                        </div>



                    </section>
                    <section id="monthly" class="tab-panel">
                        <form method="post">
                            <table class="table table-responsive" width="100%">
                                <tr>
                                    <td>Recruiter<select class="form-control" name="emp_id">
                                            <option value="">--Select Recruiter--</option>
                                            @foreach($employee as $value)
                                            <option value="{{$value->id}}">{{$value->emp_code." - ". $value->subtitle . "" . $value->firstname . " " . $value->middlename . " " . $value->lastname }}</option>
                                            @endforeach
                                        </select></td>
                                    <td> From<input type="date" name="from" id="from" class="form-control"></td>
                                    <td> From<input type="date" name="to" id="to" class="form-control"></td>
                                    <td><br><input type="submit" name="monthly_report" id="monthly_report" value="Get Monthly Report" class="btn btn-primary"></td>
                                </tr>
                            </table>
                        </form>
                        <div class="row">
                            <div class="card">
                                @if(session('success'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>{{ session('success') }}</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif

                                <div class="card-body">

                                    <table class="table table-bordered yajra-datatable-monthly nowrap">
                                        <thead style="border-color: #ff751a">
                                            <tr align="center">
                                                <th scope="col">No.</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Source</th>
                                                <th scope="col">Company Name</th>
                                                <th scope="col">Candidate Name</th>
                                                <th scope="col">Phone No</th>
                                                <th scope="col">Position</th>
                                                <th scope="col">Feedback</th>
                                                <th scope="col">Notice Period</th>
                                                <!-- <th scope="col">Added On</th>-->
                                                <th scope="col">Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody style="text-align: center">

                                        </tbody>
                                    </table>



                                </div>
                            </div>
                        </div>





                    </section>
                    <section id="client" class="tab-panel">
                        <form method="post">
                            <table class="table table-responsive" width="100%">
                                <tr>
                                    <td>Client<select class="form-control" name="client_id">
                                            <option value="">--Select Client--</option>
                                            @foreach($clients as $client)
                                            <option value="{{$value->id}}">{{$client->client_code." - ". $client->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>Requirement<select class="form-control" name="requirement_id">
                                            <option value="">--Select Requirement--</option>

                                        </select>
                                    </td>
                                    <td> From<input type="date" name="from" id="from" class="form-control"></td>
                                    <td> From<input type="date" name="to" id="to" class="form-control"></td>
                                    <td><br><input type="submit" name="client_report" id="client_report" value="Get Report" class="btn btn-primary"></td>
                                </tr>
                            </table>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function() {
    fill_datatable();

    function fill_datatable(emp_id = '', on_date = '') {
        var table = $('.yajra-datatable-daily').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('getDailyTrack.list') }}",
                data: {
                    emp_id: emp_id,
                    on_date: on_date
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'source',
                    name: 'source'
                },
                {
                    data: 'company_name',
                    name: 'company_name'
                },
                {
                    data: 'candidate_name',
                    name: 'candidate_name'
                },
                /* {data: 'cgst_amount', name: 'cgst_amount'},
                    {data: 'sgst_amount', name: 'sgst_amount'},
                    {data: 'igst_amount', name: 'igst_amount'},*/
                {
                    data: 'phone_no',
                    name: 'phone_no'
                },
                {
                    data: 'position',
                    name: 'position'
                },
                {
                    data: 'feedback',
                    name: 'feedback'
                },
                {
                    data: 'notice_period',
                    name: 'notice_period'
                },
                {
                    data: 'comments',
                    name: 'commens'
                },


                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ]
        });
    }
    $('#daily_track').click(function() {
        var emp_id = $('#emp_id').val();

        var on_date = $('#on_date').val();

        if (emp_id != '' && on_date != '') {
            $('.yajra-datatable-daily').DataTable().destroy();
            fill_datatable(emp_id, on_date);
        } else {
            alert("Select all filter options");
        }

    });
    $('#reset').click(function() {
        $('#emp_id').val();
        $('#on_date').val();
        $('.yajra-datatable-daily').DataTable().destroy();
        fill_datatable();


    });

    });
    });
</script>

@endsection('admin')