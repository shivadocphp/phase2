
@extends('admin.admin_master')
@section('admin')
<link href="{{ asset('backend/assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css')}}">
<div class="page-header">
    @isset($payroll)    
        <h3 class="page-title">Payroll Slab Management - Edit @isset($payroll->category) - {{ $payroll->category }} @endisset</h3>
    @else
        <h3 class="page-title">Payroll Slab Management - Create</h3>
    @endisset
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @isset($payroll)
                <form class="form-horizontal" method="POST" action="{{ route('payroll.slab.update', $payroll->id) }}" id="submitForm">
            @else
                <form class="form-horizontal" method="POST" action="{{ route('payroll.slab.store') }}" id="submitForm">
            @endisset
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
                        @isset($payroll)    
                            <span>Edit @isset($payroll->category) - {{ $payroll->category }} @endisset</span>
                        @else
                            <span>Create New</span>
                        @endisset
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class='col-md-4'>
                                Enter Category
                                <font color="red" size="3">*</font>
                                <input type="text" name="category" id="category" class="form-control" required="" @isset($payroll->category) value="{{ $payroll->category }}" @endisset>
                            </div>
                            <div class='col-md-4'>
                                Enter Gross Salary Limit
                                <font color="red" size="3">*</font>
                                <div class="input-group">
                                    <input type="text" name="gross_sal_limit" id="gross_sal_limit" class="form-control" required="" onkeypress="return isNumberKey(event);" @isset($payroll->gross_sal_limit) value="{{ $payroll->gross_sal_limit }}" @endisset>
                                </div>
                            </div>

                            <div class="col-md-4 categ_all">
                               Basic Percentage
                               <input type="text" name="basic_perc" id="basic_perc" class="form-control" placeholder="Basic Percentage from gross" required="" onkeypress="return isNumberKey(event);" @isset($payroll->basic_perc) value="{{ $payroll->basic_perc }}" @endisset>
                           </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4 categ_all" >
                                HRA Percentage
                                <input type="text" name="hra_perc" id="hra_perc" class="form-control" placeholder="HRA Percentage from gross" onkeypress="return isNumberKey(event);" @isset($payroll->hra_perc) value="{{ $payroll->hra_perc }}" @endisset>
                            </div>
                            <div class="col-md-4 categ_23" >
                                EPFO Employer Percentage
                                <input type="text" name="epfo_employer_perc" id="epfo_employer_perc" class="form-control" onkeypress="return isNumberKeyDec(event);" @isset($payroll->epfo_employer_perc) value="{{ $payroll->epfo_employer_perc }}" @endisset>
                            </div>
                            <div class="col-md-4 categ_2" >
                                ESIC Employer Percentage
                                <input type="text" name="esic_employer_perc" id="esic_employer_perc" class="form-control" onkeypress="return isNumberKeyDec(event);" @isset($payroll->esic_employer_perc) value="{{ $payroll->esic_employer_perc }}" @endisset>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4 categ_23" >
                                EPFO Employee Percentage
                                <input type="text" name="epfo_employee_perc" id="epfo_employee_perc" class="form-control" onkeypress="return isNumberKeyDec(event);" @isset($payroll->epfo_employee_perc) value="{{ $payroll->epfo_employee_perc }}" @endisset>
                            </div>
                            <div class="col-md-4 categ_2" >
                                ESIC employee Percentage
                                <input type="text" name="esic_employer_perc" id="esic_employer_perc" class="form-control"  onkeypress="return isNumberKeyDec(event);" @isset($payroll->esic_employer_perc) value="{{ $payroll->esic_employer_perc }}" @endisset>
                            </div>
                            <div class="col-md-4 categ_all" >
                                PT
                                <input type="text" name="pt" id="pt" class="form-control" onkeypress="return isNumberKey(event);" @isset($payroll->pt) value="{{ $payroll->pt }}" @endisset>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <button type="submit" class="btn btn-primary float-right" id="saveBtn">Save Entry</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('backend/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/toastr/toastr.min.js')}}"></script>
<script type="text/javascript">
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode;
            //if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57)))//46 '.'
            if (charCode > 31 && ((charCode < 48 || charCode > 57)))
                return false;
            return true;
        }
        function isNumberKeyDec(evt){
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57)))//46 '.'
            //if (charCode > 31 && ((charCode < 48 || charCode > 57)))
        return false;
        return true;
    }
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#submitForm').submit(function(e){
            e.preventDefault();
            var form    = $(this);


            $.ajax({
                data : form.serialize(),
                url  : form.attr('action'),
                type : form.attr('method'),
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
                        },function (isConfirm) {
                          if (isConfirm) {
                            $('#submitForm').trigger('reset');
                            window.location.href = "{{ route('payroll.slab.list')}}";
                        } else {
                        }
                    });
                    }else if (data.status == 'error') {
                        toastr.error(data.message);
                    }
                }).fail(function(data) {
                    //$('#saveBtn').html('Save Entry');
                    var err_resp = JSON.parse(data.responseText);
                    //$('#erralert').show();
                    //$('#erralert').append('<ul></ul>');
                    $.each( err_resp.errors, function( key, value) {
                        //$('#erralert ul').append('<li>'+value+'</li>');
                        toastr.error(value);
                    });
                });
            });

        $(document).on('click', 'a[data-async="true"]', function (e) {
            e.preventDefault();
            var self    = $(this),
            url     = self.data('endpoint'),
            target  = self.data('target'),
            cache   = self.data('cache');
            $.ajax({
                url     : url,
                cache   : cache,
                success : function(result){
                    console.log(result);
                    if (target !== 'undefined'){
                        $('#'+target+' .modal-content').html(result);
                        $('#'+target).modal();
                        $('#erralert').html(" ");
                        $('#erralert').hide();
                    }
                },
                error : function(error){
                    console.log(error);
                },
            });
        });

    });
    
</script>
@endsection