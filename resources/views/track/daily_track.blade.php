@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Recruiter Track Reports</h3>
    <div class="row">

        <a href="{{ route('track.client') }}" class="btn btn-new">Client track</a>

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
                            <div class="col-md-4">
                                Recruiter<select class="form-control js-example-basic-single" name="emp_id"
                                    id="emp_id">
                                    <option value="">--Select Recruiter</option>
                                    <option value="all">All</option>
                                    @foreach($employee as $value)
                                    <option value="{{$value->id}}">
                                        {{$value->emp_code." - ". $value->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                From Date <input type="date" name="from_date" class="form-control" id="from_date">
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
                                <th scope="col">Recruiter</th>
                                <th scope="col">Date</th>
                                <th scope="col">Source</th>
                                <!-- <th scope="col">Company Name</th> -->
                                <!-- <th scope="col">Position/ Requirement</th> -->
                                <th scope="col">Candidate Name</th>
                                <th scope="col">Phone No</th>
                                <th scope="col">Req. Status</th>
                                <th scope="col">Current salary</th>
                                <th scope="col">Excepted salary</th>
                                <th scope="col">Notice Period</th>
                                <th scope="col">Comments</th>
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

    function fill_datatable(emp_id = '', from_date = '', to_date = '') {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            deferRender: true,
            ajax: {
                url: "{{ route('getDailyTrack.list') }}",
                data: {
                    emp_id: emp_id,
                    from_date: from_date,
                    to_date: to_date
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'recruiter',
                    name: 'recruiter'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'source',
                    name: 'source'
                },
                // {
                //     data: 'company_name',
                //     name: 'company_name'
                // },
                // {
                //     data: 'position',
                //     name: 'position'
                // },
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
                {
                    data: 'current_salary',
                    name: 'current_salary'
                },
                {
                    data: 'expected_salary',
                    name: 'expected_salary'
                },
                {
                    data: 'notice_period',
                    name: 'notice_period'
                },
                {
                    data: 'comments',
                    name: 'comments'
                },

            ]
        });
    }
    $('#search').click(function() {
        var emp_id = $('#emp_id').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if (emp_id != '' && from_date != '' && to_date != '') {
            $('.yajra-datatable').DataTable().destroy();
            fill_datatable(emp_id, from_date, to_date);
        } else {
            alert("Select all filter options");
        }

    });
    $('#reset').click(function() {
        $('#emp_id').val('');
        $('#from_date').val('');
        $('#to_date').val('');
        $('.yajra-datatable').DataTable().destroy();
        fill_datatable();
    });
});
/* $(function () {

     var table = $('.yajra-datatable').DataTable({
         processing: true,
         serverSide: true,
         ajax: "{{ route('getInvoices.list') }}",
         columns: [
             {data: 'DT_RowIndex', name: 'DT_RowIndex'},
             {data: 'invoice_no', name: 'invoice_no'},
             {data: 'invoice_date', name: 'invoice_date'},
             {data: 'company_name', name: 'company_name'},
             {data: 'gst_no', name: 'gst_no'},
            /* {data: 'cgst_amount', name: 'cgst_amount'},
             {data: 'sgst_amount', name: 'sgst_amount'},
             {data: 'igst_amount', name: 'igst_amount'},*/
/* {data: 'total_amount', name: 'total_amount'},
                    {data: 'status', name: 'status'},
                    {data: 'payment_date', name: 'payment_date'},
                    {data: 'paid_amount', name: 'paid_amount'},
                    {data: 'balance_amount', name: 'balance_amount'},


                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });

        });*/
</script>

@endsection('admin')