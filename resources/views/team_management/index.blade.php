@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Team Management </h3>
        <a href="{{ route('create.team_emp') }}" class="btn btn-success">Allocate Employee to team</a>
    </div>
    <div class="container">
        <div class="row">
            <div class="col"></div>
        </div>
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
                    <div class="card-header">
                        <span>Team - Employee</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Team</th>
                                <th scope="col">Team Leader</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1;?>
                            @foreach($emp_teams as $et)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $et->team }}</td>
                                    <td>{{$et->team_leader}}</td>
                                    <td>{{$et->is_active}}</td>
                                    <td><a href="" class="btn btn-sm btn-warning">Change Team</a>
                                        <a href="" class="btn btn-sm btn-success">Edit</a>
                                        <a href="" class="btn  btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


        </div>


    </div>



@endsection('admin')



