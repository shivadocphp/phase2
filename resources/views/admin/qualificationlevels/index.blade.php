
@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/Qualification Levels </h3>

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
                        <span>QUALIFICATION LEVELS</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Qualification Level</th>
                                <th scope="col">Edit</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            @foreach($qualificationlevels as $level)
                                <tr>
                                    <td>{{ $qualificationlevels->firstItem()+$loop->index }}</td>
                                    <td>{{ $level->qualificationlevel }}</td>
                                    <td><a href="" class="" data-mylevel="{{ $level->qualificationlevel }}" data-myid="{{ $level->id }}" data-toggle="modal" data-target="#edit" ><i class="fa fa-edit green" ></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $qualificationlevels->links('pagination::bootstrap-4')  }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <span>ADD LEAVE TYPE</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.qualificationlevels') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div>
                                    <label>Qualification Level</label></div>
                                <div>
                                    <input type="text" name="qualificationlevel" class="form-control">
                                </div>
                                @error('quali_level')
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

                            <h4 class="modal-title" id="myModalLabel">Edit Qualification Level</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form  action="{{route('update.qualificationlevels')}}"  method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input type="hidden" name="id" value="" id="myid">
                                <div class="form-group">
                                    <label>Leave Type</label>
                                    <input type="text" name="qualificationlevel" class="form-control" id="mylevel" >
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
        $('#edit').on('show.bs.modal', function (event) {
            // console.log('Modal Opened');
            var button = $(event.relatedTarget) // Button that triggered the modal
            var quali_level = button.data('mylevel')
            var id=button.data('myid')
            var modal = $(this)

            modal.find('.modal-body #mylevel').val(quali_level)
            modal.find('.modal-body #myid').val(id)
        })
    </script>







@endsection('admin')



