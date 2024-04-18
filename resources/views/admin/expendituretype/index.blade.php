@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Backend/Expenditure types </h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                @if(session('success'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="card-header">
                    <span>EXPENDITURE TYPES</span>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Date</th>
                                <th scope="col">Expenditure Type</th>
                                <th scope="col">Note</th>
                                <th scope="col">Edit</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $i = 1; ?>
                            @foreach($e_types as $e_type)
                            <tr>
                                <td>{{ $e_types->firstItem()+$loop->index }}</td>
                                <td>{{ $e_type->date }}</td>
                                <td>{{ $e_type->expendituretype }}</td>
                                <td>{{ $e_type->note }}</td>
                                <td><a href="" class="" data-myexpenditure_mode="{{ $e_type->expendituretype }}"
                                        data-myid="{{ $e_type->id }}" data-date="{{ $e_type->date }}" data-note="{{ $e_type->note }}"
                                        data-toggle="modal" data-target="#edit"><i class="fa fa-edit green"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $e_types->links('pagination::bootstrap-4')  }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <span>ADD EXPENDITURE TYPE</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('store.expendituretype') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div>For date
                                <input type="date" name="date" class="form-control">
                                <input type="text" name="expendituretype" class="form-control" placeholder="Expenditure Type">
                                <input type="text" name="note" class="form-control" placeholder="note">
                            </div>
                            @error('expendituretype')
                            <span class="text-danger">{{ $message  }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col" align="center">
                                <button type="submit" class="btn btn-primary">Enter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title" id="myModalLabel">Edit Expenditure Type</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('update.expendituretype')}}" method="POST">
                        {{method_field('patch')}}
                        {{csrf_field()}}
                        <div class="modal-body">
                            <input type="hidden" name="id" value="" id="myid">
                            <div class="form-group">
                                <label>Expenditure Type</label>
                                <input type="text" name="expendituretype" class="form-control" id="myexpenditure_mode">
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" id="date">
                            </div>
                            <div class="form-group">
                                <label>Note</label>
                                <input type="text" name="note" class="form-control" id="note">
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
</div>

<script src="{{asset('js/app.js')}}"></script>
<script>
$('#edit').on('show.bs.modal', function(event) {
    // console.log('Modal Opened');
    var button = $(event.relatedTarget) // Button that triggered the modal
    var expenditure_mode = button.data('myexpenditure_mode')
    var date = button.data('date')
    var note = button.data('note')
    var id = button.data('myid')
    var modal = $(this)

    modal.find('.modal-body #myexpenditure_mode').val(expenditure_mode)
    modal.find('.modal-body #date').val(date)
    modal.find('.modal-body #note').val(note)
    modal.find('.modal-body #myid').val(id)
})
</script>
@endsection('admin')