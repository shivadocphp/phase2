@extends('admin.admin_master')
@section('admin')

<link href="{{ asset('backend/assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css')}}">
<div class="page-header">
    <h3 class="page-title">Payroll Management</h3>
    @can('Manage Report')
    <span>
        <button type="button" class="btn btn-new" id="export-excel-button" title="Export">Export Payroll</button>
    </span>
    @endcan
    <div class="row">
        <a id="createPayroll" class="btn  btn-primary" href="{{ route('payroll.create') }}" data-endpoint="{{ route('payroll.create') }}" data-target="modal-default" {{-- data-cache="false" data-toggle='modal' data-async="true" --}}>Add New <i class="fa fa-plus"></i></a>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @if(session('success'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <!-- @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ session('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            Employee<select class="form-control" name="emp_id" id="emp_id">
                                <option value="">--Select Employee--</option>
                                <option value="all">All</option>
                                @foreach($employee as $value)
                                <option value="{{$value->id}}">
                                    {{$value->emp_code." - ". $value->subtitle . "" . $value->firstname . " " . $value->middlename . " " . $value->lastname }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            Month
                            <select name="month" id="month" class="form-control" required>
                                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}">
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                    @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            For Year
                            <select name="year" id="year" class="form-control" required>
                                <option value="{{ date('Y') - 2}}">{{ date('Y') - 2 }}</option>
                                <option value="{{ date('Y') - 1}}">{{ date('Y') - 1 }}</option>
                                <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                <option value="{{ date('Y') + 1 }}">{{ date('Y') + 1 }}</option>
                            </select>
                        </div>
                        <div class="col-md-2"><br>
                            <!-- <input type="submit" name="search" id="search" class="btn btn-new" value="Search"> -->
                            <button type="button" class="btn btn-new" value="search" id="search_filter" title="search">Search</button>
                            <button type="button" class="btn btn-new" id="reset" title="Reset Filter">Reset</button>
                        </div>
                    </div>
                </div>

                <!-- /.card-header -->
                <div class="card-body ">
                    <div id="showresult" class="table-responsive">
                        <table id="userTable" class="table table-bordered table-striped yajra-datatable" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Month/Year</th>
                                    <th>Category</th>
                                    <!-- <th>Year</th> -->
                                    <th>Basic</th>
                                    <th>HRA</th>
                                    <th>Fixed Gross</th>
                                    <th>EPFO Employer</th>
                                    <th>ESIC Employer</th>
                                    <th>EPFO Employee</th>
                                    <th>ESCIC Employee</th>
                                    <th>CTC</th>
                                    <th>PT</th>
                                    <th>Net Pay</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('backend/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/toastr/toastr.min.js')}}"></script>
<script type="text/javascript">
    // $(function() {
    $(document).ready(function() {
        fill_datatable();

        function fill_datatable(emp_id = '', month = '', year = '') {


            var table = $('.yajra-datatable').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                deferRender: true,

                // ajax: "{{ route('payroll.list') }}",
                ajax: {
                    url: "{{ route('payroll.list') }}",
                    data: {
                        emp_id: emp_id,
                        month: month,
                        year: year,
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'employee.emp_code',
                        name: 'emp_code'
                    },
                    {
                        // data: 'month',
                        // name: 'month',
                        // render: function(data, type, row, meta) {
                        //     // Assuming 'data' is the month number (1 to 12)
                        //     var monthNames = ["January", "February", "March", "April", "May", "June",
                        //         "July", "August", "September", "October", "November", "December"
                        //     ];
                        //     return monthNames[data - 1]; // Adjust for zero-based index
                        // }

                        data: null,
                        name: 'month_year',
                        render: function(data, type, row, meta) {
                            // Array of month names
                            var monthNames = ["January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"
                            ];
                            // Get the month name based on the month number (subtract 1 since month index starts from 0)
                            var monthName = monthNames[row.month - 1];
                            // Combine month name and year
                            return monthName + ' ' + row.year;
                        }

                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'basic',
                        name: 'basic'
                    },
                    {
                        data: 'hra',
                        name: 'hra'
                    },
                    {
                        data: 'fixed_gross',
                        name: 'fixed_gross'
                    },
                    {
                        data: 'epfo_employer',
                        name: 'epfo_employer'
                    },
                    {
                        data: 'esic_employer',
                        name: 'esic_employer'
                    },
                    {
                        data: 'epfo_employee',
                        name: 'epfo_employee'
                    },
                    {
                        data: 'esic_employee',
                        name: 'esic_employee'
                    },
                    {
                        data: 'ctc',
                        name: 'ctc'
                    },
                    {
                        data: 'pt',
                        name: 'pt'
                    },
                    {
                        data: 'net_pay',
                        name: 'net_pay'
                    },
                    {
                        data: 'manage',
                        name: 'manage',
                        orderable: false,
                        searchable: false
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
                // Enable searching for the combined "month_year" column
                // "search": {
                //     "smart": true
                // }

                // Custom search function for the 'month_year' column
                // "initComplete": function() {
                //     this.api().columns('month_year').every(function() {
                //         var column = this;
                //         $('<input type="text">')
                //             .appendTo($(column.header()).empty())
                //             .on('keyup', function() {
                //                 column.search($(this).val()).draw();
                //             });
                //     });
                // }

            });
            // new $.fn.dataTable.FixedHeader(table);
        }


        $('#search_filter').click(function() {
            console.log("cliked");
            var emp_id = $('#emp_id').val();
            var month = $('#month').val();
            var year = $('#year').val();
            if (emp_id != '' && month != '' && year != '') {
                $('.yajra-datatable').DataTable().destroy();
                fill_datatable(emp_id, month, year);
            } else {
                alert("Select all filter options");
            }
        });

        $('#reset').click(function() {
            $('#emp_id').val('');
            $('#month').val('');
            $('#year').val('');

            $('.yajra-datatable').DataTable().destroy();
            fill_datatable();
            $('#display').html('');
        });


        // export button
        $('#export-excel-button').click(function() {
            var emp_id = $('#emp_id').val();
            var month = $('#month').val();
            var year = $('#year').val();
            var url = "{{ route('payroll.list') }}"; // if to add flters
            url += "?export=export";
            if (emp_id !== '') {
                url += "&emp_id=" + emp_id + "&month=" + month + "&year=" + year;
            }
            window.location.href = url;
        });


    });
</script>
<script type="text/javascript">
    /*$(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
      
            //check Lock
            $(document).on('click', 'a[data-async="true"]', function (e) {
                e.preventDefault();
                var self    = $(this),
                    url     = self.data('endpoint'),
                    target  = self.data('target'),
                    cache   = self.data('cache'),
                    edit_id = self.data('id'),
                    customer_number  = self.data('customer_number'),
                    check_link  = self.data('check-link');
                if(check_link=='update_stat'){
                    $.ajax({
                        url     : url,
                        cache   : cache,
                        success : function(result){
                            if (target !== 'undefined'){
                                $('#'+target+' .modal-content').html(result);
                                $('#'+target).modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                $('.datetimepicker-input').daterangepicker({
                                    singleDatePicker: true,
                                    locale:{
                                        format:'DD/MM/YYYY'
                                    },
                                    drops: 'up',
                                    autoApply:true,
                                });
                                $('#erralert').html(" ");
                                $('#erralert').hide();
                                //$('#start_time').val(start_time);
                            }
                        },
                        error : function(error){
                            console.log(error);
                        },
                    });
                }
                else if(check_link=='view'){
                    $.ajax({
                        url     : url,
                        cache   : cache,
                        data    : {customer_number : customer_number},
                        success : function(result){
                            if (target !== 'undefined'){
                                $('#'+target+' .modal-content').html(result);
                                $('#'+target).modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                $('#erralert').html(" ");
                                $('#erralert').hide();
                            }
                        },
                        error : function(error){
                            console.log(error);
                        },
                    });
                }
                else if(check_link=='list'){
                    $.ajax({
                        url: url,
                        cache : cache,
                        data  : {'search_priority' : 1}
                    }).done(function(data){
                        $("#showresult").empty().html(data);
                        //location.hash = page;
                    }).fail(function(jqXHR, ajaxOptions, thrownError){
                        toastr.error('An error occured. Please try again.');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    });
                }
            });
        });*/
</script>
@endsection