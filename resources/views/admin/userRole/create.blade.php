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

                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span>ASSIGN USER ROLE</span>
                    </div>
                    <div class="card-body">
                        <form action="{{route('store.user_role')}}" method="POST">
                            {{csrf_field()}}
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        User
                                        <select name="user" class="form-control">
                                            <option value="">--Select User--</option>
                                            @foreach($user as $u)
                                                <option value="{{ $u->id }}">{{$u->name}}</option>
                                            @endforeach
                                        </select>

                                        @error('name')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4"><br>
                                <table class="table table striped">
                                    <thead><tr><th colspan="3">Permission</th></tr></thead>
                                    <thead style="background-color: black;color: darkorange"><tr><th>No</th><th>Privilege</th><th>Select</th></tr></thead>
                               <tbody>
                                <?php $i =1;?>
                                @foreach($permission as $p)
                                    <tr><td>{{ $i++ }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td><input type="checkbox" name="permission[]" value="{{$p->id}}"></td></tr>

                                @endforeach
                               </tbody></table>
                            </div>
                            @error('roles')
                            <span class="text-danger">{{ $message  }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">

                        <div class="row">
                            <div class="col" align="center">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>

            </div>
        </div>


    </div>


    </div>


@endsection('admin')


