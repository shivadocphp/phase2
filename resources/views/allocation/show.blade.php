@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> {{ $allocates->client_code ." - ". $allocates->company_name}} Allocated to
        : 
        <!-- {{ $allocates->team }}  -->
        @foreach($team_name as $key => $name)
            {{ $name->team }}
            @if ($key < count($team_name) - 1)
                & 
            @endif
        @endforeach
    </h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="" class="btn btn-med btn-primary" disabled style="color: orangered;">Allocated Requirements</a>
        </div>
        <div class="col-md-4" align="right"><a href="{{ route('allocate_task.allocation',$id) }}"
                class="btn btn-new">Edit Allocation</a>
        </div>
        <br>
    </div>
    <div>
        @if(session('success'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

    </div>
    <div class="col-md-12">
        <br>
        <div class="form-group">

            <table class="table table-bordered yajra-datatable">
                <thead style="background-color: #ff751a">
                    <tr style="text-align: center">
                        <th>No</th>
                        <th>Position</th>
                        <th>Vacancy</th>
                        <th>Location</th>
                        <th>Allocated to</th>
                        <th>Allocated No</th>
                        <th>Status</th>


                    </tr>
                </thead>
                <tbody align="center">

                </tbody>
            </table>

        </div>


    </div>
</div>
<script type="text/javascript">
$(function() {
    var list_task_id = <?php echo $id; ?>;

    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        deferRender: true,
        ajax: { 
            url: "{{ route('getTask.list','show') }}",
            data: {    // added
                list_task_id: list_task_id
               }
        },
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
                data: 'allocated_to',
                name: 'allocated_to'
            },
            {
                data: 'allocated_no',
                name: 'allocated_no'
            },

            {
                data: 'status',
                name: 'status',
                orderable: true,
                searchable: true
            },
        ]
    });

});
</script>
@endsection