@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> {{ $allocates->client_code ." - ". $allocates->company_name}} Allocated to
        <!-- : {{ $allocates->team }}  -->
        :
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
            <a href="{{ route('allocate_task.allocation',$id) }}" class="btn btn-med btn-primary">Allocate
                Requirements</a>
            <a href="{{ route('list_task.allocation',$id) }}" class="btn btn-med btn-new-full" disabled
                style="color: orangered;">List</a>


        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.allocation') }}" class="btn btn-med btn-primary">View
                Alocation</a>
        </div>
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
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="col-md-14">
            <div class="card-body">
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
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody align="center">

                    </tbody>
                </table>


            </div>
        </div>
    </div>
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Edit Allocated No</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('update.taskallocation')}}" method="POST">
                    {{method_field('patch')}}
                    {{csrf_field()}}
                    <div class="modal-body">
                        <input type="hidden" name="task_id" value="" id="id">
                        <input type="hidden" name="allocation_id" value="" id="allocation_id">
                        <input type="hidden" name="total_position" value="" id="total_position">
                        <div class="form-group">
                            <div>
                                <label> Requirements</label>
                            </div>
                            <div>
                                <input type="text" name="requirement" id="requirement" class="form-control" disabled>
                            </div>
                            @error('quali_level')
                            <span class="text-danger">{{ $message  }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Team Member</label>
                            <input type="text" name="employee" id="employee" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Allocated No</label>
                            <input type="number" name="allocated_no" id="allocated_no" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/app.js')}}"></script>
<script type="text/javascript">
$('#edit').on('show.bs.modal', function(event) {
    // console.log('Modal Opened');
    var button = $(event.relatedTarget) // Button that triggered the modal
    var requirement = button.data('requirement')
    var employee = button.data('employee')
    var allocated_no = button.data('allocated_no')
    var allocation_id = button.data('allocation_id')
    var total_position = button.data('total_position')
    var id = button.data('id')
    var modal = $(this)

    modal.find('.modal-body #requirement').val(requirement)
    modal.find('.modal-body #employee').val(employee)
    modal.find('.modal-body #allocated_no').val(allocated_no)
    modal.find('.modal-body #allocation_id').val(allocation_id)
    modal.find('.modal-body #total_position').val(total_position)
    modal.find('.modal-body #id').val(id)
})

$(function() {
    var list_task_id = <?php echo $id; ?>;

    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        deferRender: true,
        ajax: { 
            url: "{{ route('getTask.list','edit') }}",
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