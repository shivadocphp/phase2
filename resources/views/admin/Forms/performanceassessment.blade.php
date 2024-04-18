@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/Performance Assessment Form </h3>

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
                        <span>ASSESSMENT FORM ENTRIES</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Parameter</th>
                                 <th scope="col">Description</th>
                                <th scope="col">Weightage</th>
                                <th scope="col">Is Active</th>
                                <th scope="col" >Edit</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            @foreach($assessment as $ap)
                                <tr>
                                    <td>{{ $assessment->firstItem()+$loop->index }}</td>
                                    <td>{{ $ap->assessment_parameter }}</td>
                                    <td>{{ $ap->description }}</td>
                                    <td>{{ $ap->weightage }}</td>
                                    <td>{{ $ap->is_active }}</td>
                                    <td><a href="" class="" data-myassessment_parameter="{{ $ap->assessment_parameter }}" data-myweightage="{{ $ap->weightage }}"  data-mydescription="{{ $ap->description }}"
                                            data-myid="{{ $ap->id }}" data-toggle="modal" data-target="#edit" ><i class="fa fa-edit green"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $assessment->links('pagination::bootstrap-4')  }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <span>ADD APPRAISAL PARAMETER</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.performanceassessment') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div>
                                    <label> Assessment Parameter</label></div>
                                <div>
                                    <input type="text" name="assessment_parameter" class="form-control">
                                </div>
                                @error('assessment_parameter')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>
                             <div class="form-group">
                                <div>
                                    <label> Description</label></div>
                                <div>
                                    <textarea name="description" class="form-control" rows="3" cols="15"></textarea>
                                </div>
                                @error('description')
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

                            <h4 class="modal-title" id="myModalLabel">Edit Assessment</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form  action="{{route('update.performanceassessment')}}"  method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input type="hidden" name="id" value="" id="myid">
                                <div class="form-group">
                                    <label>Assessment Parameter</label>
                                    <input type="text" name="assessment_parameter" class="form-control" id="myassessment_parameter" >
                                </div>
                                 <div class="form-group">
                                <div>
                                    <label> Description</label></div>
                                <div>
                                    <textarea name="description" class="form-control" rows="3" cols="15" id="mydescription"></textarea>
                                </div>
                                @error('description')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>
                            
                                <div class="form-group">
                                    <label>Weightage</label>
                                    <input type="number" name="weightage" class="form-control" id="myweightage" >
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
            var appraisal_parameter = button.data('myassessment_parameter')
            var weightage = button.data('myweightage')
            var description = button.data('mydescription')
            var id=button.data('myid')
            var modal = $(this)

            modal.find('.modal-body #myassessment_parameter').val(appraisal_parameter)
            modal.find('.modal-body #mydescription').val(description)
            modal.find('.modal-body #myweightage').val(weightage)
            modal.find('.modal-body #myid').val(id)
        })
    </script>
@endsection('admin')
