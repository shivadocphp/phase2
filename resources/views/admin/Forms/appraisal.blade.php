@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/Appraisal Form </h3>

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
                        <span>APPRAISAL FORM ENTRIES</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Appraisal Parameter</th>
                                <th scope="col">Weightage</th>
                                <th scope="col">Is Active</th>
                                <th scope="col" >Edit</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            @foreach($appraisal as $ap)
                                <tr>
                                    <td>{{ $appraisal->firstItem()+$loop->index }}</td>
                                    <td>{{ $ap->appraisal_parameter }}</td>
                                    <td>{{ $ap->weightage }}</td>
                                    <td>{{ $ap->is_active }}</td>
                                    <td><a href="" class="btn-sm btn-success" data-myappraisalparameter="{{ $ap->appraisal_parameter }}" data-myweightage="{{ $ap->weightage }}"
                                            data-myid="{{ $ap->id }}" data-toggle="modal" data-target="#edit" ><i class="fa fa-edit"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $appraisal->links('pagination::bootstrap-4')  }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <span>ADD APPRAISAL PARAMETER</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.appraisal') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div>
                                    <label> Appraisal Parameter</label></div>
                                <div>
                                    <input type="text" name="appraisal_parameter" class="form-control">
                                </div>
                                @error('appraisal_parameter')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div>
                                    <label>Weightage</label></div>
                                <div>
                                    <input type="number" name="weightage" class="form-control">
                                </div>
                                @error('weightage')
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

                            <h4 class="modal-title" id="myModalLabel">Edit Country</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form  action="{{route('update.appraisal')}}"  method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input type="hidden" name="id" value="" id="myid">
                                <div class="form-group">
                                    <label>Appraisal Parameter</label>
                                    <input type="text" name="appraisal_parameter" class="form-control" id="myappraisalparameter" >
                                </div>
                                <div class="form-group">
                                    <label>Weightage</label>
                                    <input type="number" name="weightage" class="form-control" id="myweightage" >
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
            var appraisal_parameter = button.data('myappraisalparameter')
            var weightage = button.data('myweightage')
            var id=button.data('myid')
            var modal = $(this)

            modal.find('.modal-body #myappraisalparameter').val(appraisal_parameter)
            modal.find('.modal-body #myweightage').val(weightage)
            modal.find('.modal-body #myid').val(id)
        })
    </script>
@endsection('admin')
