@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Edit Client {{ $client->client_code }}: Requirements</h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('edit.client',$client->id) }}" class="btn btn-med btn-primary" disabled>Basic Details</a>
            <a href="{{ route('edit.clientaddress',$client->id) }}" class="btn btn-med btn-primary">Address
                Details</a>
            <a href="{{ route('edit.clientofficial',$client->id) }}" class="btn btn-med btn-primary">Official
                Details</a>
            <a href="{{ route('edit.clientagreement',$client->id) }}" class="btn btn-med btn-primary">Agreement</a>
            <div class="btn-group"><a href="{{ route('edit.clientrequirement',$client->id) }}"
                    class="btn btn-med btn-new-full" style="color: orangered;">Requirements</a>
                <a href="{{ route('create.requirement',$client->id) }}" class="btn btn-med btn-primary" title="Add New"
                    style="color: orangered;">
                    <i class="fa fa-plus"></i></a>
            </div>
        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.client') }}" class="btn btn-med btn-primary">View
                Clients</a>
        </div>
    </div>
    <div class="col-md-14">
        <div class="card">
            <div>
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
            </div>
            <div class="card-body">
                <table class="table table-bordered yajra-datatable">
                    <thead style="background-color: #ff751a">
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Position</th>
                            <th scope="col">Vacancy</th>
                            <th scope="col">Location</th>
                            <th scope="col">Skills</th>
                            <th scope="col">Added On</th>
                            <th scope="col">Status</th>
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

        ajax: "{{ route('requirements.list',[$client_id]) }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
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
                data: 'added_on',
                name: 'added_on'
            },
            {
                data: 'status',
                name: 'status'
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
@endsection