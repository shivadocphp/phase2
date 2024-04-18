
@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/Roles </h3>

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
                        <span>ROLES & PERMISSIONS</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Role</th>
                                <th scope="col">Permission</th>
                                <th scope="col">Edit</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            @foreach($roles as $r)
                                <tr>
                                    <td>{{ $roles->firstItem()+$loop->index }}</td>
                                    <td>{{ $r->name }}</td>
                                    <td>
                                      <?php  $rolePermissions = \Spatie\Permission\Models\Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                                        ->where("role_has_permissions.role_id",$r->id)
                                        ->get();?>
                                          @if(!empty($rolePermissions))
                                              @foreach($rolePermissions as $v)
                                                  <label class="label label-success">{{ $v->name }},</label>
                                              @endforeach
                                          @endif
                                    </td>
                                    <td><a href="{{route('edit.role',$r->id)}}" ><i class="fa fa-edit green"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $roles->links('pagination::bootstrap-4')  }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <span>ADD ROLES</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.role') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div>
                                    <label> Role</label></div>
                                <div>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                @error('name')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div>
                                    <label>Permissions</label></div>
                                <div>

                                    @foreach($permission as $value)
                                        <input type="checkbox" name="permission[]" value="{{ $value->id }}" > {{ $value->name }}
                                        <br/>
                                    @endforeach
                                </div>
                                @error('permissions')
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

                            <h4 class="modal-title" id="myModalLabel">Edit Roles</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form  action="{{route('update.role')}}"  method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input type="hidden" name="id" value="" id="myid">
                                <div class="form-group">
                                    <label>Role</label>
                                    <input type="text" name="name" class="form-control" id="myrole" >
                                </div>
                                <div class="form-group">
                                    <label>Permissions</label>
                                    <div id="permission">

                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
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
            var role = button.data('myrole')
       //     var reason = button.data('myreason')
            var id=button.data('myid')
            var modal = $(this)

            modal.find('.modal-body #myrole').val(role)
         //   modal.find('.modal-body #myholiday_reason').val(reason)
            modal.find('.modal-body #myid').val(id)
        })
    </script>







@endsection('admin')


