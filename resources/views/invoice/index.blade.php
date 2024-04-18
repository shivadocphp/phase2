@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Invoices</h3>
    <div class="row">
        <a href="{{ route('create.invoice') }}" class="btn btn-new">Add</a>
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
                                <select class="form-control js-example-basic-single" name="client_id" id="client_id">
                                    <option value="">--Select Company</option>
                                    <option value="all">All</option>
                                    @foreach($clients as $client)
                                    <option value="{{$client->id}}">
                                        {{$client->client_code." - ". $client->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="status" id="status">
                                    <option value="">--Select Status--</option>
                                    <option value="all">All</option>
                                    <option value="paid">Paid</option>
                                    <option value="unpaid">Unpaid</option>
                                    <option value="advance">Advance paid</option>
                                    <option value="cancelled">Cancelled Invoice</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="from_date" class="form-control" id="from_date">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="to_date" class="form-control" id="to_date">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-new" id="search" title="Search Invoice">Filter</button>
                                <button type="button" class="btn btn-new" id="reset" title="Reset Filter">Reset</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered yajra-datatable">
                        <thead style="background-color: #ff751a">
                            <tr align="center">
                                <th scope="col">Sl No.</th>
                                <th scope="col">Invoice No.</th>
                                <th scope="col">Invoice Date</th>
                                <th scope="col">Company Name</th>
                                <th scope="col">GST</th>
                                <!--<th scope="col">Description</th>-->
                                <th scope="col">Total</th>
                                <th scope="col">Status</th>
                                <th scope="col">Payment Date</th>
                                <th scope="col">Paid</th>
                                <th scope="col">Balance</th>
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
</div>
<script type="text/javascript">
$(document).ready(function() {
    fill_datatable();

    function fill_datatable(client_id = '', status = '', from_date = '', to_date = '') {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            deferRender: true,
            ajax: {
                url: "{{ route('getInvoices.list') }}",
                data: {
                    client_id: client_id,
                    status: status,
                    from_date: from_date,
                    to_date: to_date
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'invoice_no',
                    name: 'invoice_no'
                },
                {
                    data: 'invoice_date',
                    name: 'invoice_date'
                },
                {
                    data: 'company_name',
                    name: 'company_name'
                },
                {
                    data: 'gst_no',
                    name: 'gst_no'
                },
                /* {data: 'cgst_amount', name: 'cgst_amount'},
                    {data: 'sgst_amount', name: 'sgst_amount'},
                    {data: 'igst_amount', name: 'igst_amount'},*/
                {
                    data: 'total_amount',
                    name: 'total_amount'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'payment_date',
                    name: 'payment_date'
                },
                {
                    data: 'paid_amount',
                    name: 'paid_amount'
                },
                {
                    data: 'balance_amount',
                    name: 'balance_amount'
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
    $('#search').click(function() {
        var client_id = $('#client_id').val();
        var status = $('#status').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if (client_id != '' && status != '' && from_date != '' && to_date != '') {
            $('.yajra-datatable').DataTable().destroy();
            fill_datatable(client_id, status, from_date, to_date);
        } else {
            alert("Select all filter options");
        }
    });
    $('#reset').click(function() {
        $('#client_id').val('');
        $('#status').val('');
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