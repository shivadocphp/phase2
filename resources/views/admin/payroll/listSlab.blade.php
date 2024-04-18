@extends('admin.admin_master')
@section('admin')
	
    <link href="{{ asset('backend/assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css')}}">
    <div class="page-header">
        <h3 class="page-title">Payroll Slab</h3>
        <div class="row">
            <a id="createPayroll" class="btn  btn-primary" href="{{ route('payroll.slab.create') }}" data-endpoint="{{ route('payroll.slab.create') }}" data-target="modal-default" {{-- data-cache="false" data-toggle='modal' data-async="true" --}}>Add New <i class="fa fa-plus"></i></a>
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
                    
	                    <!-- /.card-header -->
	                <div class="card-body ">
	                    <div id="showresult" class="table-responsive">
	                    	@include('admin.payroll.listSlabPagin')
	                    </div>
	                </div>
	                <!-- /.card-body -->
                </div>
           
        </div>
    </div>

    <script src="{{ asset('backend/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/toastr/toastr.min.js')}}"></script>
    <script type="text/javascript">
        
    </script>
	<script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', 'a[data-async="true"]', function (e) {
                e.preventDefault();
                var self    = $(this),
                    url     = self.data('endpoint'),
                    target  = self.data('target'),
                    cache   = self.data('cache'),
                    act_type = self.data('act_type');
                if(act_type=='deletePayroll'){
                    swal({
                        title: "Are you sure want to delete?",
                        text: "You won't be able to revert this!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'Yes , delete it',
                        cancelButtonText: 'No ',
                        closeOnConfirm: false,
                    },function (isConfirm) {
                        if (isConfirm) {
                           $.ajax({
                                url  : url,
                                type : "POST",
                                cache : cache,
                            }).done(function(data) {
                                console.log(data);
                                if (data.status == 'success') {
                                    swal({
                                        type: 'success',
                                        title: data.message,
                                        showConfirmButton: true,
                                        timer: 3000,
                                    });
                                    location.reload();
                                }
                            }).fail(function(data) {
                                console.log(error);
                            }); 
                        } 

                    });   
                }
            });
        });
        
    </script>
    <script type="text/javascript">
        $(window).on('hashchange', function() {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                }else{
                    // getData(page);
                }
            }
        });
        
        $(document).ready(function()
        {
            $(document).on('click', '.pagination a',function(event)
            {
                event.preventDefault();
      
                $('li').removeClass('active');
                $(this).parent('li').addClass('active');
      
                var myurl = $(this).attr('href');
                var page=$(this).attr('href').split('page=')[1];
      
                getData(page);
            });
      
        });
      
        function getData(page){
            $.ajax(
            {
                url: '?page=' + page ,
                type: "get",
                datatype: "html"
            }).done(function(data){
                $("#showresult").empty().html(data);
                location.hash = page;
            }).fail(function(jqXHR, ajaxOptions, thrownError){
                toastr.error('An error occured. Please try again.');
                setTimeout(function() {
                    location.reload();
                }, 3000);
            });
        }
    </script>
@endsection