@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Team Management </h3>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
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
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span>EDIT TEAMS</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.team_emp') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div>
                                            <label> Team : {{ $team->team }}</label></div>
                                        <div>
                                            <input type="hidden" name="team_id" value="{{ $id }}">
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="form-group">
                                <div>
                                    <label> Employees</label></div>
                                <div>

                                    @foreach($employees as $emp)
                                        <div class="form-check">
                                            <input type="checkbox" name="emp_id[]"
                                                   value="{{ $emp->id."-".$emp->emp_code }}" <?php
                                                   if(in_array($emp->emp_code,$empteams)){?>
                                                      'checked'
                                                       <?php }
                                                   ?>
                                                   class="form-check-input" id="defaultCheck1">

                                            <label class="form-check-label" for="defaultCheck1">
                                                {{ $emp->emp_code. " - ".$emp->name }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>

                                <div class="row">
                                    <div class="col" align="center">
                                        <button type="submit" class="btn btn-primary">Edit</button>
                                    </div>
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

                            <h4 class="modal-title" id="myModalLabel">Edit Designation</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('update.designation')}}" method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input type="hidden" name="id" value="" id="myid">
                                <div class="form-group">
                                    <label>Designation</label>
                                    <input type="text" name="designation" class="form-control" id="mydesig">
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
            var designation = button.data('mydesig')
            var id = button.data('myid')
            var modal = $(this)

            modal.find('.modal-body #mydesig').val(designation)
            modal.find('.modal-body #myid').val(id)
        })
    </script>






@endsection('admin')



