@extends('admin.admin_master')
@section('admin')
<link href="{{ asset('backend/assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css')}}">
<div class="page-header">
    <h3 class="page-title">Employee Payroll Management - Edit -
        {{ $emp_payroll->employee->emp_code }} </h3>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" method="POST" action="{{ route('payroll.update',$emp_payroll->id) }}"
                id="submitForm">
                @csrf
                <div class="card">
                    @if(session('success'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="card-header">
                        <span>Employee Payroll Management - Edit -
                            {{ $emp_payroll->employee->emp_code }}</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class='col-md-6'>
                                <label for="month">Month:</label>
                                <font color="red" size="3">*</font>
                                <input type="text" class="form-control" name="month" id="month"
                                    value="{{ $emp_payroll->month }}" readonly>
                            </div>
                            <div class='col-md-6'>
                                <label for="year">Year:</label>
                                <font color="red" size="3">*</font>
                                <input type="text" class="form-control" name="year" id="year"
                                    value="{{ $emp_payroll->year }}" readonly>
                            </div>

                            <div class='col-md-4'>
                                Employee
                                <font color="red" size="3">*</font>
                                <input type="hidden" class="form-control" name="employee" id="employee"
                                    value="{{ $employees->id }}">
                                <br>
                                <h5>{{ $employees->firstname }} - {{ $employees->emp_code }}</h5>
                            </div>
                            <div class='col-md-4'>
                                Select Category
                                <font color="red" size="3">*</font>
                                <select id="category" name="category" class="form-control" required="">
                                    <option value="">--Select--</option>
                                    @foreach($categories as $categ)
                                    <option value="{{ $categ->category }}" @isset($emp_payroll) @if($emp_payroll->
                                        category==$categ->category) selected="" @endif
                                        @endisset>{{ $categ->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='col-md-4'>
                                Enter Gross Salary
                                <font color="red" size="3">*</font>
                                <div class="input-group">
                                    <input type="text" name="gross_sal" id="gross_sal" class="form-control" required=""
                                        onkeypress="return isNumberKey(event);" @isset($emp_payroll)
                                        value="{{ $emp_payroll->gross_sal }}" @endisset>
                                    @error('client_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                    <div class="input-group-append">
                                        <button class="btn btn-warning" data-toggle="tooltip"
                                            data-original-title="View Calculation" type="button"
                                            onclick="viewCalculation();"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="calcFields">
                            <div class="form-group row">
                                <div class="col-md-4 categ_all" style="display: none;">
                                    Basic
                                    <input type="text" name="basic" id="basic" class="form-control" readonly=""
                                        {{-- placeholder="Gross*80%" --}}>
                                </div>
                                <div class="col-md-4 categ_all" style="display: none;">
                                    HRA
                                    <input type="text" name="hra" id="hra" class="form-control" readonly=""
                                        {{-- placeholder="Gross*20%" --}}>
                                </div>
                                <div class="col-md-4 categ_all" style="display: none;">
                                    Fixed Gross
                                    <input type="text" name="fixed_gross" id="fixed_gross" class="form-control"
                                        readonly="" {{-- placeholder="SUM(BASIC+HRA)" --}}>
                                </div>
                                <div class="col-md-4 categ_23" style="display: none;">
                                    EPFO Employer(D)
                                    <input type="text" name="epfo_employer" id="epfo_employer" class="form-control"
                                        readonly="" {{-- placeholder="Gross*80%" --}}>
                                </div>
                                <div class="col-md-4 categ_2" style="display: none;">
                                    ESIC Employer(E)
                                    <input type="text" name="esci_employer" id="esci_employer" class="form-control"
                                        readonly="" {{-- placeholder="Gross*20%" --}}>
                                </div>
                                <div class="col-md-4 categ_all" style="display: none;">
                                    CTC
                                    <input type="text" name="ctc" id="ctc" class="form-control" readonly=""
                                        {{-- placeholder="Gross+ EPFO Employer+ ESIC Employer" --}}>
                                </div>
                                <div class="col-md-4 categ_23" style="display: none;">
                                    EPFO Employee(G)
                                    <input type="text" name="epfo_employee" id="epfo_employee" class="form-control"
                                        readonly="" {{-- placeholder="Gross*80%" --}}>
                                </div>
                                <div class="col-md-4 categ_2" style="display: none;">
                                    ESIC employee(H)
                                    <input type="text" name="esci_employee" id="esci_employee" class="form-control"
                                        readonly="" {{-- placeholder="Gross*20%" --}}>
                                </div>
                                <div class="col-md-4 categ_all" style="display: none;">
                                    PT
                                    <input type="text" name="pt" id="pt" class="form-control" readonly=""
                                        {{-- placeholder="209" --}}>
                                </div>
                                <div class="col-md-4 categ_all" style="display: none;">
                                    Net Pay
                                    <input type="text" name="net_pay" id="net_pay" class="form-control" readonly=""
                                        {{-- placeholder="CTC-(EPFO Employee+ ESIC Employee+ PT)" --}}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <button type="submit" class="btn btn-primary float-right" id="saveBtn">Update Entry</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('backend/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/toastr/toastr.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
    //console.log( "ready!" );
    changeCategory();
    //$('#category').trigger('change');
});
</script>
<script type="text/javascript">
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57)))//46 '.'
    if (charCode > 31 && ((charCode < 48 || charCode > 57)))
        return false;
    viewCalculation();
    return true;
}
$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#submitForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            data: form.serialize(),
            url: form.attr('action'),
            type: form.attr('method'),
        }).done(function(data) {
            //console.log(data);
            if (data.status == 'success') {
                swal({
                    title: "Success",
                    text: data.message,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    closeOnConfirm: false,
                }, function(isConfirm) {
                    if (isConfirm) {
                        $('#submitForm').trigger('reset');
                        window.location.href = "{{ route('payroll.list')}}";
                    } else {}
                });
            } else if (data.status == 'error') {
                toastr.error(data.message);
            }
        }).fail(function(data) {
            //$('#saveBtn').html('Save Entry');
            var err_resp = JSON.parse(data.responseText);
            //$('#erralert').show();
            //$('#erralert').append('<ul></ul>');
            $.each(err_resp.errors, function(key, value) {
                //$('#erralert ul').append('<li>'+value+'</li>');
                toastr.error(value);
            });
        });
    });

    $(document).on('click', 'a[data-async="true"]', function(e) {
        e.preventDefault();
        var self = $(this),
            url = self.data('endpoint'),
            target = self.data('target'),
            cache = self.data('cache');
        $.ajax({
            url: url,
            cache: cache,
            success: function(result) {
                console.log(result);
                if (target !== 'undefined') {
                    $('#' + target + ' .modal-content').html(result);
                    $('#' + target).modal();
                    $('#erralert').html(" ");
                    $('#erralert').hide();
                }
            },
            error: function(error) {
                console.log(error);
            },
        });
    });

});

function viewCalculation() {
    var gross_sal = $('#gross_sal').val();
    var category = $('#category').val();

    if (gross_sal == undefined || category == undefined) {
        $('#basic').val(0);
        $('#hra').val(0);
        $('#fixed_gross').val(0);
        $('#epfo_employer').val(0);
        $('#esci_employer').val(0);
        $('#epfo_employee').val(0);
        $('#esci_employee').val(0);
        $('#ctc').val(0);
        $('#pt').val(0);
        $('#net_pay').val(0);
    } else if ((gross_sal == '' || gross_sal == Number.NaN) && (category == '')) {
        $('#basic').val(0);
        $('#hra').val(0);
        $('#fixed_gross').val(0);
        $('#epfo_employer').val(0);
        $('#esci_employer').val(0);
        $('#epfo_employee').val(0);
        $('#esci_employee').val(0);
        $('#ctc').val(0);
        $('#pt').val(0);
        $('#net_pay').val(0);

        $('.categ_all').hide();
        $('.categ_2').hide();
        $('.categ_23').hide();
    } else {
        $.ajax({
            url: "{{ route('payroll.employee.calculate') }}",
            cache: false,
            data: {
                'gross_sal': gross_sal,
                'category': category
            },
        }).done(function(result) {
            //console.log(result.data['followup_time']);
            if (result.status == 'success') {
                $('#basic').val(result.data['basic']);
                $('#hra').val(result.data['hra']);
                $('#fixed_gross').val(result.data['fixed_gross']);
                $('#epfo_employer').val(result.data['epfo_employer']);
                $('#esci_employer').val(result.data['esci_employer']);
                $('#epfo_employee').val(result.data['epfo_employee']);
                $('#esci_employee').val(result.data['esci_employee']);
                $('#ctc').val(result.data['ctc']);
                $('#pt').val(result.data['pt']);
                $('#net_pay').val(result.data['net_pay']);

            } else {
                $('#basic').val(0);
                $('#hra').val(0);
                $('#fixed_gross').val(0);
                $('#epfo_employer').val(0);
                $('#esci_employer').val(0);
                $('#epfo_employee').val(0);
                $('#esci_employee').val(0);
                $('#ctc').val(0);
                $('#pt').val(0);
                $('#net_pay').val(0);
            }
        }).fail(function(error) {
            console.log(error);
        });
    }
}

$(document).on('change', '#category', function(e) {
    var selected_category = $(this).val();
    changeCategory(selected_category);
});

function changeCategory(selected_category) {
    /*if(selected_category==undefined)
    {
        $('.categ_all').hide();
        $('.categ_2').hide();
        $('.categ_23').hide();
    }else{*/
    if (selected_category == "Category 1") {
        $('.categ_all').show();
        $('.categ_2').hide();
        $('.categ_23').hide();
    } else if (selected_category == "Category 2") {
        $('.categ_all').show();
        $('.categ_2').show();
        $('.categ_23').show();
    } else if (selected_category == "Category 3") {
        $('.categ_all').show();
        $('.categ_2').hide();
        $('.categ_23').show();
    } else if (selected_category != '') {
        $('.categ_all').show();
        $('.categ_2').show();
        $('.categ_23').show();
    } else {
        $('.categ_all').hide();
        $('.categ_2').hide();
        $('.categ_23').hide();
    }
    //}
    viewCalculation();
}
</script>
@endsection