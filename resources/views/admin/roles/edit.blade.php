
@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/Roles </h3>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    @if(session('success'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>{{ session('success') }}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span>EDIT ROLES</span>
                    </div>
                    <div class="card-body">
                        <form  action="{{route('update.role')}}"  method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="form-group">
                                <div>
                                    <label> Role</label></div>
                                <div>
                                    <input type="hidden" name="id" value="{{ $roles->id  }}">
                                    <input type="text" name="name" class="form-control" value="{{ $roles->name }}">
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
                                        <input type="checkbox" name="permission[]" value="{{ $value->id }}"
                                        <?php
                                                foreach($rolePermissions as $rp){
                                                    if($rp->permission_id == $value->id){
                                                        echo "checked";
                                                    }else{

                                                    }
                                                }
                                            ?>
                                        > {{ $value->name }}
                                        <br/>
                                    @endforeach
                                </div>
                                @error('permissions')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col" align="center">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>




        </div>


    </div>



@endsection('admin')


