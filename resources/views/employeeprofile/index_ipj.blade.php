@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> IPJ Candidates </h3>
    <div class="row">
        <a href="{{ route('all.employee') }}" class="btn btn-new" style=" background-color:black;color:orangered ;">Active</a>
        <a href="{{ route('inactive.employee') }}" class="btn btn-new">Inactive</a>
        <?php

        use Illuminate\Support\Facades\Auth;
        use App\Models\User;

        $user = User::find(Auth::user()->id);
        if (Auth::user()->id == 1 || $user->hasPermissionTo('Add Employees')) { ?>
            <a href="{{ route('create.employee') }}" class="btn btn-new">Add</a>
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
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
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
                            <th scope="col">Name</th>
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
            info: false,
            pageLength: 10,
            scroller: {
                loadingIndicator: true
            },
            scrollCollapse: true,
            ajax: "{{ route('ipj.list') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ],
            language: {
                emptyTable: "No data available in the table"
            },
        });
    });
</script>
@endsection('admin')