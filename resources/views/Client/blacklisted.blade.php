@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Blacklisted Client </h3>
    <div class="row">
        <a href="{{ route('all.client') }}" class="btn btn-new" style="color: orangered">Active</a>
        <a href="{{ route('prospect.client') }}" class="btn btn-new">Prospect</a>
        <a href="{{ route('inactive.client') }}" class="btn btn-new">Inactive</a>
        <?php
            use Illuminate\Support\Facades\Auth;
            use App\Models\User;
            $user = User::find(Auth::user()->id);
            if (Auth::user()->id == 1 || $user->hasPermissionTo('Add Client')){
            ?>
        <a href="{{ route('create.client') }}" class="btn btn-new">Add</a>
        <?php } ?>
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
            <div class="card-body">
                <table class="table table-bordered yajra-datatable">
                    <thead style="background-color: #ff751a">
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Client Code</th>
                            <th scope="col">Company</th>
                            <th scope="col">Profile</th>
                            <th scope="col">Added By</th>
                            <th scope="col">Added On</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function() {
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        deferRender: true,
        ajax: "{{ route('blacklisted_clients.list') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'client_code',
                name: 'client_code'
            },
            {
                data: 'client_name',
                name: 'client_name'
            },
            {
                data: 'profile',
                name: 'profile'
            },
            {
                data: 'added_by',
                name: 'added_by'
            },
            {
                data: 'added_on',
                name: 'added_on'
            },
            {
                data: 'action',
                name: 'action',
                orderable: true,
                searchable: true
            },
        ]
    });
});
</script>
@endsection('admin')