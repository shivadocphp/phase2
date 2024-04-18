@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Requirements Closed by JSC</h3>
    @can('Manage Report')
    <span>
        <button type="button" class="btn btn-new" id="export-excel-button" title="Export">Export Requirements</button>
    </span>
    @endcan
    <div class="row">

        <a href="{{ route('all.requirement') }}" class="btn btn-new" style="color: orangered">Active</a>
        <a href="{{ route('prospect.client') }}" class="btn btn-new">Prospect</a>
        <a href="{{ route('inactive.client') }}" class="btn btn-new">Hold</a>
        <div class="btn-group dropright">
            <button type="button" class="btn btn-new dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Closed
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('closedJSC.requirement') }}">By Company</a>
                <a class="dropdown-item" href="{{ route('closedClient.requirement') }}">By Client</a>

            </div>
        </div>
        <?php

        use Illuminate\Support\Facades\Auth;
        use App\Models\User;

        $user = User::find(Auth::user()->id);
        if (Auth::user()->id == 1 || $user->hasPermissionTo('Add Requirement')) {
        ?>
            <a href="{{ route('create.requirement') }}" class="btn btn-new">Add Requirement</a>
        <?php } ?>

    </div>
</div>
<div class="container">
    <div class="row">
        <div>

        </div>
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
                            <th scope="col">Company</th>
                            <th scope="col">Position</th>
                            <th scope="col">Vacancy</th>
                            <th scope="col">Location</th>
                            <th scope="col">Skills</th>
                            <th scope="col">Details</th>
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
            ajax: "{{ route('closedJSC_requirements.list') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'client_code',
                    name: 'client_code'
                },
                {
                    data: 'position',
                    name: 'position'
                },
                {
                    data: 'vacancy',
                    name: 'vacancy'
                },
                {
                    data: 'location',
                    name: 'location'
                },
                {
                    data: 'skills',
                    name: 'skills'
                },
                {
                    data: 'details',
                    name: 'details'
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
            var url = "{{ route('closedJSC_requirements.list') }}"; // if to add flters
            url += "?export=closed_by_company";
            window.location.href = url;
        });
    });
</script>
@endsection('admin')