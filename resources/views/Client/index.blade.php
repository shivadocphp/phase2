@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Active Client </h3>
    @can('Manage Report')
    <span>
        <button type="button" class="btn btn-new" id="export-excel-button" title="Export">Export Client</button>
    </span>
    @endcan
    <div class="row">

        <a href="{{ route('prospect.client') }}" class="btn btn-new">Prospect</a>
        <a href="{{ route('inactive.client') }}" class="btn btn-new">Inactive</a>
        <a href="{{ route('blacklisted.client') }}" class="btn btn-new">Blacklisted</a>
        <?php

        use Illuminate\Support\Facades\Auth;
        use App\Models\User;

        $user = User::find(Auth::user()->id);
        if (Auth::user()->id == 1 || $user->hasPermissionTo('Add Client')) {
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
                        <!-- table data added through the ajax -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            deferRender: true,
            ajax: "{{ route('active_clients.list') }}",
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


        // export button
        $('#export-excel-button').click(function() {
            var url = "{{ route('export.client') }}";
            url += "?export=export";
            window.location.href = url;
        });


    });
</script>
@endsection('admin')