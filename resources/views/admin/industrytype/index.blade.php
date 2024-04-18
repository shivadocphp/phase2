@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/Industry Type </h3>

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
                        <span>INDUSTRY TYPES</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Industry</th>
                                <th scope="col">Edit</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            @foreach($in_types as $in_type)
                                <tr>
                                    <td>{{ $in_types->firstItem()+$loop->index }}</td>
                                    <td>{{ $in_type->industrytype}}</td>
                                    <td><a href="" class="" data-myindustry="{{ $in_type->industrytype }}" data-myid="{{ $in_type->id }}" data-toggle="modal" data-target="#edit" ><i class="fa fa-edit green" ></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $in_types->links('pagination::bootstrap-4')  }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <span>ADD INDUSTRY TYPE</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.industrytype') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div>
                                    <label> Industry</label></div>
                                <div>
                                    <input type="text" name="industrytype" class="form-control">
                                </div>
                                @error('indusrytype')
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

                            <h4 class="modal-title" id="myModalLabel">Edit Industry</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form  action="{{route('update.industrytype')}}"  method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input type="hidden" name="id" value="" id="myid">
                                <div class="form-group">
                                    <label>Industry Type</label>
                                    <input type="text" name="industrytype" class="form-control" id="myindustry" >
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
            var industry_type = button.data('myindustry')
            var id=button.data('myid')
            var modal = $(this)

            modal.find('.modal-body #myindustry').val(industry_type)
            modal.find('.modal-body #myid').val(id)
        })
    </script>






@endsection('admin')



