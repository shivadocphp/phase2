@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Client Track Reports</h3>
    <div class="row">

        <a href="{{ route('track.all') }}" class="btn btn-new">Recruiter track</a>

    </div>
</div>
<div class="container">
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
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <div class="card-body">
                <div class="col-md-14">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                Client <select class="form-control js-example-basic-single" name="client_id"
                                    id="client_id">
                                    <option value="">--Select Client</option>
                                    @foreach($clients as $client)
                                    <option value="{{$client->id}}">
                                        {{$client->client_code." - ". $client->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">Requirement
                                <select class="form-control" name="requirement_id" id="requirement_id">
                                    <option value="">--Select Requirement--</option>

                                </select>
                            </div>
                            <div class="col-md-2">
                                From Date<input type="date" name="from_date" class="form-control" id="from_date">
                            </div>
                            <div class="col-md-2">
                                To Date<input type="date" name="to_date" class="form-control" id="to_date">
                            </div>
                            <div class="col-md-2"><br>
                                <button type="button" class="btn btn-new" id="search" title="Track">Filter</button>
                                <button type="button" class="btn btn-new" id="reset" title="Reset Filter">Reset</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered yajra-datatable">
                        <thead style="background-color: #ff751a">
                            <tr align="center">
                            <th scope="col">Sl No.</th>
                            <th scope="col">Company Name</th>
                            <th scope="col">Position/ Requirement</th>
                            <th scope="col">Source</th>
                                <!-- <th scope="col">Recruiter</th> -->
                                <th scope="col">Date</th>
                                <th scope="col">Candidate Name</th>
                                <th scope="col">Phone No</th>
                                <th scope="col">Req. Status</th>
                                <!-- <th scope="col">Notice Period</th>
                                <th scope="col">Comments</th> -->
                            </tr>
                        </thead>
                        <tbody style="text-align: center">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    fill_datatable();

    function fill_datatable(client_id = '', requirement_id = '', from_date = '', to_date = '') {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            deferRender: true,
            ajax: {
                url: "{{ route('getDailyTrackClient.list') }}",
                data: {
                    client_id: client_id,
                    requirement_id: requirement_id,
                    from_date: from_date,
                    to_date: to_date
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'company_name',
                    name: 'company_name'
                },
                {
                    data: 'position',
                    name: 'position'
                },
                {
                    data: 'source',
                    name: 'source'
                },
                // {
                //     data: 'recruiter',
                //     name: 'recruiter'
                // },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'candidate_name',
                    name: 'candidate_name'
                },
                {
                    data: 'phone_no',
                    name: 'phone_no'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                // {
                //     data: 'notice_period',
                //     name: 'notice_period'
                // },
                // {
                //     data: 'comments',
                //     name: 'comments'
                // },

            ]
        });
    }
    $('#search').click(function() {
        var client_id = $('#client_id').val();
        var requirement_id = $('#requirement_id').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if (client_id != '' && requirement_id != '' && from_date != '' && to_date != '') {
            $('.yajra-datatable').DataTable().destroy();
            fill_datatable(client_id, requirement_id, from_date, to_date);
        } else {
            alert("Select all filter options");
        }

    });
    $('#reset').click(function() {
        $('#client_id').val('');
        $('#requirement_id').val('');
        $('#from_date').val('');
        $('#to_date').val('');
        $('.yajra-datatable').DataTable().destroy();
        fill_datatable();
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



});
</script>

@endsection('admin')