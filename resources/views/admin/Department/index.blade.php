@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/Departments </h3>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
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
                        <span>DEPARTMENTS</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Department</th>
                                <th scope="col">Edit</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            @foreach($depts as $dept)
                                <tr>
                                    <td>{{ $depts->firstItem()+$loop->index }}</td>
                                    <td>{{ $dept->department }}</td>
                                    <td>
                                        <a href="" class="" data-mydept="{{ $dept->department }}" data-myid="{{ $dept->id }}" data-toggle="modal" data-target="#edit" ><i class="fa fa-edit green" ></i></a>
                                        <a data-endpoint="{{ route('department.history',$dept->id)}}" data-async="true" data-toggle="tooltip"  data-original-title="View Edit History" data-target="modal-xl" data-cache="false" data-check-link="view" class=""><i class="fa fa-eye black"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $depts->links('pagination::bootstrap-4')  }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <span>ADD DEPARTMENT</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.department') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div>
                                    <label> Department</label></div>
                                <div>
                                    <input type="text" name="department" class="form-control">
                                </div>
                                @error('department')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col" align="center">
                                    <button type="submit" class="btn btn-primary">Enter</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h4 class="modal-title" id="myModalLabel">Edit Department</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form  action="{{route('update.department')}}"  method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input type="hidden" name="id" value="" id="myid">
                                <div class="form-group">
                                    <label>Department</label>
                                    <input type="text" name="dept" class="form-control" id="mydept" >
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-xl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                    </div>
                </div>
            </div>

        </div>


    </div>

    <script src="{{asset('js/app.js')}}"></script>
    <script>
        $('#edit').on('show.bs.modal', function (event) {
            // console.log('Modal Opened');
            var button = $(event.relatedTarget) // Button that triggered the modal
            var dept = button.data('mydept')
            var id=button.data('myid')
            var modal = $(this)

            modal.find('.modal-body #mydept').val(dept)
            modal.find('.modal-body #myid').val(id)
        })
    </script>

    <script type="text/javascript">
        $(function () {
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
                    check_link  = self.data('check-link');
                if(check_link=='view'){
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
                            }
                        },
                        error : function(error){
                            console.log(error);
                        },
                    });
                }
            });
        });
    </script>
@endsection

