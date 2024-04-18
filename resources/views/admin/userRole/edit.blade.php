@extends('admin.admin_master')
@section('admin')
<style>
    table {
        width: 100%;
    }

    table,
    td {
        border-collapse: collapse;
    }

    thead {
        display: table;
        /* to take the same width as tr */
        width: calc(100% - 17px);
        /* - 17px because of the scrollbar width */
    }

    tbody {
        display: block;
        /* to enable vertical scrolling */
        max-height: 200px;
        /* e.g. */
        overflow-y: scroll;
        /* keeps the scrollbar even if it doesn't need it; display purpose */
    }

    th,
    td {
        width: 33.33%;
        /* to enable "word-break: break-all" */
        padding: 5px;
        word-break: break-all;
        /* 4. */
    }

    tr {
        display: table;
        /* display purpose; th's border */
        width: 100%;
        box-sizing: border-box;
        /* because of the border (Chrome needs this line, but not FF) */
    }

    td {
        text-align: center;
        border-bottom: none;
        border-left: none;
    }
</style>
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
                    <span>ASSIGN USER ROLE</span>
                </div>
                <div class="card-body">
                    <form action="{{route('update.user_role',$user->id)}}" method="POST">
                        {{method_field('patch')}}
                        {{csrf_field()}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4"> User
                                    <input type="hidden" name="user" value="{{ $id }}">
                                    <input type="text" name="user" value="{{ $user->name}}" disabled class="form-control">
                                </div>
                                <div class="col-md-4"> Role <br>
                                    @foreach($allRoles as $role)
                                    <input type="radio" name="roles" value="{{ $role->id }}" {{ $userRoles->contains($role) ? 'checked' : '' }}>
                                    <label>{{ $role->name }}</label><br>
                                    @endforeach
                                </div>
                            </div>
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