@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Client Allocation </h3>
    @can('Manage Report')
    <span>
        <button type="button" class="btn btn-new" id="export-excel-button" title="Export">Export Allocation</button>
    </span>
    @endcan
    <div class="row">

        <?php
        use Illuminate\Support\Facades\Auth;
        use App\Models\User;

        $user = User::find(Auth::user()->id);
        if (Auth::user()->id == 1 || $user->hasPermissionTo('Add Client Allocation')) {
        ?>
            <a href="{{ route('create.allocation') }}" class="btn btn-new">Allocate Team</a>
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
                <div class="col-md-14">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <select class="form-control js-example-basic-single" name="client_id" id="client_id">
                                    <option value="">--Select Company</option>
                                    @foreach($clients as $client)
                                    <option value="{{$client->id}}">
                                        {{$client->client_code." - ". $client->company_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-new" id="search" title="Search Invoice">Filter</button>
                                <button type="button" class="btn btn-new" id="reset" title="Reset Filter">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered yajra-datatable">
                    <thead style="background-color: #ff751a">
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Company</th>
                            <th scope="col">Requirements</th>
                            <th scope="col">Total No.</th>
                            <th scope="col">Teams</th>
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
        fill_datatable();

        function fill_datatable(client_id = '') {

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                deferRender: true,
                // ajax: "{{ route('getAllocation.list') }}",
                ajax: {
                    url: "{{ route('getAllocation.list') }}",
                    data: {
                        client_id: client_id,
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'client_code',
                        name: 'client_code'
                    },
                    {
                        data: 'requirements',
                        name: 'requirements'
                    },
                    {
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'team',
                        name: 'team'
                    },

                    //   { data:'added_by',name:'added_by'},


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
        }


        $('#search').click(function() {
            var client_id = $('#client_id').val();
            if (client_id != '') {
                $('.yajra-datatable').DataTable().destroy();
                fill_datatable(client_id);
            } else {
                alert("Select all filter options");
            }
        });
        $('#reset').click(function() {
            $('#client_id').val('');
            $('.yajra-datatable').DataTable().destroy();
            fill_datatable();
        });

         // export button
         $('#export-excel-button').click(function() {
            var client_id = $('#client_id').val();
            var url = "{{ route('getAllocation.list') }}"; // if to add flters
            url += "?export=export";
            if (client_id !== '') {
                url += "&client_id=" + client_id;
            }
            window.location.href = url;
        });

    });
</script>
@endsection('admin')