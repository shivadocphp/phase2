@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Inactive Employees </h3>
    @can('Manage Report')
    <span>
        <button type="button" class="btn btn-new" id="export-excel-button" title="Export">Export Employee</button>
    </span>
    @endcan
    <div class="row">
        <a href="{{ route('all.employee') }}" class="btn btn-new" style=" background-color:black;color:orangered ;">Active</a>
        <a href="{{ route('ipj.employee') }}" class="btn btn-new">IPJ </a>
        <?php

        use Illuminate\Support\Facades\Auth;
        use App\Models\User;

        $user = User::find(Auth::user()->id);
        if (Auth::user()->id == 1 || $user->hasPermissionTo('Add Employees')) {
        ?>
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
            <div class="card-body">
                <table class="table table-bordered yajra-datatable">
                    <thead style="background-color: #ff751a;">
                        <tr align="center">
                            <th scope="col">No.</th>
                            <th scope="col">Employee Code</th>
                            <th scope="col">Name</th>
                            <th scope="col">Department-Designation</th>
                            <th scope="col">Profile</th>
                            <th scope="col">Tenure</th>
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
    $(document).ready(function() {
        // $(function() {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            deferRender: true,
            // bLengthChange: false,
            info: false,
            pageLength: 10,
            scroller: {
                loadingIndicator: true
            },
            scrollCollapse: true,

            ajax: "{{ route('inactive.list') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'emp_code',
                    name: 'emp_code'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'department',
                    name: 'department'
                },
                {
                    data: 'profile',
                    name: 'profile'
                },
                {
                    data: 'tenure',
                    name: 'tenure'
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
            initComplete: function() {
                if (table.rows().count() === 0) {
                    $('#export-excel-button').hide(); // Hide the export button
                }
            }
        });

        $('#export-excel-button').click(function() {
            var url = "{{ route('export.employee') }}";
            url += "?export=inactive";
            window.location.href = url;
        });

    });
</script>
@endsection('admin')