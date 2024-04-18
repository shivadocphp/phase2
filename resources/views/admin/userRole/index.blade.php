@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Backend/Assign User Roles </h3>

</div>
<div class="container">
    <div class="row">

        @if(session('success'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="col-md-12">
                    <table class="table table-bordered yajra-datatable">
                        <thead style="background-color:#ff751a;font-family: 'Times New Roman';">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Emp Code</th>
                                <th scope="col">Employee Name</th>
                                <th scope="col">Role</th>
                                <!-- <th scope="col">Privileges</th> -->
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

</div>
</div>
<script type="text/javascript">
    $(function() {

        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('userRole.list') }}",

            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'emp_code',
                    name: 'emp_code'
                },
                {
                    data: 'emp_name',
                    name: 'emp_name'
                },
                {
                    data: 'roles',
                    name: 'roles'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });

    });
</script>
@endsection('admin')