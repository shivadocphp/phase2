@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Candidates</h3>
    @can('Manage Report')
    <span>
        <button type="button" class="btn btn-new" id="export-excel-button" title="Export">Export Candidate</button>
    </span>
    @endcan
    <div class="row">
        <a href="{{ route('track.all') }}" class="btn btn-new">Track Reports</a>
        <a href="{{ route('bulk_upload.candidate') }}" class="btn btn-new">Bulk Upload</a>
        <a href="{{ route('create.candidate') }}" class="btn btn-new">Add</a>

    </div>
</div>
<div class="container">
    <div class="row">
        <div class="card">
            <?php if (false) //{{route('recruiter.candidate')}} 
            ?>
            <!-- <form method="post" action="#"> -->
            <!-- @csrf -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        From<input type="date" name="from_date" id="from_date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        Till<input type="date" name="to_date" id="to_date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        Recruiter<select class="form-control" name="emp_id" id="emp_id">
                            <option value="">--Select Recruiter--</option>
                            <option value="all">All</option>
                            @foreach($employee as $value)
                            <option value="{{$value->id}}">
                                {{$value->emp_code." - ". $value->subtitle . "" . $value->firstname . " " . $value->middlename . " " . $value->lastname }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        Call Status<select class="form-control" name="call_status" id="call_status">
                            <option value="">--select--</option>
                            <option value="Connected / Waiting for response">Connected /
                                Waiting for
                                response
                            </option>
                            <option value="RNR / Not connected">RNR / Not connected</option>
                            <option value="Not Interested">Not Interested</option>
                            <option value="Interested / Processed">Interested / Processed</option>
                            <option value="Processed / Not Responding">Processed / Not Responding</option>
                            </option>
                            <option value="Interview Scheduled / Shortlisted">InterviewScheduled / Shortlisted
                            </option>

                        </select>
                    </div>
                    <div class="col-md-2">
                        Candidate Status<select class="form-control" name="requirement_status" id="requirement_status">
                            <option value="">--Select--</option>
                            <!-- <option value="RNR">RNR</option> -->
                            <option value="Interested">Interested</option>
                            <option value="Not Interested">Not Interested</option>
                            <option value="Fake profile">Fake profile</option>
                            <option value="Not looking for change">Not looking for change</option>
                            <!-- <option value="Rejected/Offer Rejected">Rejected/Offer Rejected</option> -->
                            <!-- <option value="Offered/Joined">Offered/Joined</option> -->
                            <!-- <option value="Processed">Processed</option> -->
                            <!-- <option value="Shortlisted">Shortlisted</option> -->
                        </select>
                    </div>
                    <div class="col-md-2"><br>
                        <!-- <input type="submit" name="search" id="search" class="btn btn-new" value="Search"> -->
                        <button type="button" class="btn btn-new" value="search" id="search_filter" title="search">Search</button>
                    </div>
                </div>
            </div>
            <!-- </form> -->

            <!-- <form method="post" action="#"> -->
            <!-- @csrf -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        From<input type="date" name="from_date" id="from_date2" class="form-control">
                    </div>
                    <div class="col-md-2">
                        Till<input type="date" name="to_date" id="to_date2" class="form-control">
                    </div>
                    <div class="col-md-2">
                        Client<select class="form-control" name="client_id" id="client_id">
                            <option value="">--Select Client--</option>
                            <!-- <option value="all">All</option> -->
                            @foreach($clients as $client)
                            <option value="{{$client->id}}">{{$client->client_code." - ". $client->company_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        Requirement<select class="form-control" name="requirement_id" id="requirement_id">
                            <option value="">--select requirement--</option>

                        </select>
                    </div>
                    <div class="col-md-2">
                        Requirement Status<select class="form-control" name="requirement_status" id="requirement_status2">
                            <option value="">--Select--</option>
                            <option value="RNR">RNR</option>
                            <option value="Not Interested">Not Interested</option>
                            <option value="Rejected/Offer Rejected">Rejected/Offer Rejected</option>
                            <option value="Offered/Joined">Offered/Joined</option>
                            <option value="Processed">Processed</option>
                            <option value="Shortlisted">Shortlisted</option>
                        </select>
                    </div>
                    <div class="col-md-2"><br>
                        <!-- <input type="submit" name="search" class="btn btn-new" value="Search"> -->
                        <button type="button" class="btn btn-new" value="search2" id="search_filter2" title="search">Search</button>

                    </div>
                </div>
            </div>
            <!-- </form> -->
        </div> <button type="button" class="btn btn-new" id="reset" title="Reset Filter">Reset</button>
    </div>
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
            <span id="display"></span>
            <div class="card-body">
                <table class="table table-bordered yajra-datatable nowrap">
                    <thead style="border-color: #ff751a">
                        <tr align="center">
                            <th scope="col">No.</th>
                            <th scope="col">Name</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">Whatsapp</th>
                            <th scope="col">Email</th>
                            <th scope="col">Skills</th>
                            <th scope="col">Candidate Status</th>
                            <th scope="col">Processed</th>
                            <th scope="col">Added By</th>
                            <!-- <th scope="col">Added On</th>-->
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // $(function() {
    $(document).ready(function() {
        fill_datatable();

        function fill_datatable(emp_id = '', call_status = '', req_status = '', from_date = '', to_date = '', search_filter = '') {
            $('#display').html('');
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                deferRender: true,
                // ajax: "{{ route('getCandidates.list') }}",
                ajax: {
                    url: "{{ route('getCandidates.list') }}",
                    data: {
                        emp_id: emp_id,
                        call_status: call_status,
                        req_status: req_status,
                        from_date: from_date,
                        to_date: to_date,
                        search_filter: search_filter,
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'candidate_name',
                        name: 'candidate_name'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'whatsapp',
                        name: 'whatsapp'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'skills',
                        name: 'skills'
                    },
                    {
                        data: 'candidate_status',
                        name: 'candidate_status'
                    },
                    {
                        data: 'profile',
                        name: 'profile'
                    },
                    {
                        data: 'added_by',
                        name: 'added_by'
                    },
                    //{data:'added_on',name:'added_on'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true,
                        responsive: true
                    },
                ],
                language: {
                    emptyTable: "No data available in the table"
                },
                initComplete: function() {
                    if (table.rows().count() === 0) {
                        $('#export-excel-button').hide(); // Hide the export button
                    } else {
                        $('#export-excel-button').show();
                    }
                }

            });
            // new $.fn.dataTable.FixedHeader(table);
        }

        $('#search_filter').click(function() {
            console.log("cliked");

            var emp_id = $('#emp_id').val();
            var call_status = $('#call_status').val();
            var req_status = $('#requirement_status').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var search_filter = $('#search_filter').val();
            if (emp_id != '' && call_status != '' && req_status != '' && from_date != '' && to_date != '' && search_filter != '') {
                $('#client_id').val('');
                $('#requirement_id').val('');
                $('#requirement_status2').val('');
                $('#from_date2').val('');
                $('#to_date2').val('');
                $('.yajra-datatable').DataTable().destroy();
                fill_datatable(emp_id, call_status, req_status, from_date, to_date, search_filter);
                $('#display').html('recruiter search results');
                $('#display').css('color', 'blue');
            } else {
                alert("Select all filter options");
            }

        });
        $('#search_filter2').click(function() {
            console.log("cliked2");

            var emp_id = $('#client_id').val(); //client
            var call_status = $('#requirement_id').val(); //client req
            var req_status = $('#requirement_status2').val(); //req status
            var from_date = $('#from_date2').val();
            var to_date = $('#to_date2').val();
            var search_filter = $('#search_filter2').val();
            if (emp_id != '' && call_status != '' && req_status != '' && from_date != '' && to_date != '' && search_filter != '') {
                $('#emp_id').val('');
                $('#call_status').val('');
                $('#requirement_status').val('');
                $('#from_date').val('');
                $('#to_date').val('');
                $('.yajra-datatable').DataTable().destroy();
                fill_datatable(emp_id, call_status, req_status, from_date, to_date, search_filter);
                $('#display').html('client search results');
                $('#display').css('color', 'green');
            } else {
                alert("Select all filter options");
            }
        });

        $('#reset').click(function() {
            $('#emp_id').val('');
            $('#call_status').val('');
            $('#requirement_status').val('');
            $('#from_date').val('');
            $('#to_date').val('');

            $('#client_id').val('');
            $('#requirement_id').val('');
            $('#requirement_status2').val('');
            $('#from_date2').val('');
            $('#to_date2').val('');

            $('.yajra-datatable').DataTable().destroy();
            fill_datatable();
            $('#display').html('');
        });

        // to fetch client requirement
        $('#client_id').on('change', function() {
            var client_id = this.value;
            $("#requirement_id").html('');
            $.ajax({
                url: "{{url('api/fetch-clientRequirement')}}",
                type: "POST",
                data: {
                    client_id: client_id,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#requirement_id').html('<option value="">Select Requirement</option>');
                    $.each(result.requirement, function(key, value) {
                        $("#requirement_id").append('<option value="' + value
                            .id + '">' + value.designation + '(' + value
                            .total_position + ') -' + value.address + ', ' + value
                            .city + ', ' + value.state + '</option>');
                    });
                }
            });
        });


        // export button
        $('#export-excel-button').click(function() {
            var url = "{{ route('getCandidates.list') }}"; // if to add flters
            url += "?export=export";

            if ($('#emp_id').val() !== '') {
                var emp_id = $('#emp_id').val();
                var call_status = $('#call_status').val();
                var req_status = $('#requirement_status').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var search_filter = $('#search_filter').val();
                url += "&search_filter=" + search_filter+ "&emp_id=" + emp_id + "&call_status=" + call_status
                + "&req_status=" + req_status + "&from_date=" + from_date + "&to_date=" + to_date;              

            }else if($('#client_id').val() !== ''){

                var emp_id = $('#client_id').val(); //client
                var call_status = $('#requirement_id').val(); //client req
                var req_status = $('#requirement_status2').val(); //req status
                var from_date = $('#from_date2').val();
                var to_date = $('#to_date2').val();
                var search_filter = $('#search_filter2').val();
                url += "&search_filter=" + search_filter+ "&emp_id=" + emp_id + "&call_status=" + call_status
                + "&req_status=" + req_status + "&from_date=" + from_date + "&to_date=" + to_date;

            }else{

            }
            window.location.href = url;
       
        });

    });
</script>

@endsection('admin')