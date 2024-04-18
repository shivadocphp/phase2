@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/States </h3>

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
                        <span>STATES</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Country</th>
                                <th scope="col">State</th>
                                <th scope="col">Edit</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            @foreach($states as $state)
                                <tr>
                                    <td>{{ $states->firstItem()+$loop->index }}</td>
                                    <td>{{ $state->country }}</td>
                                    <td>{{ $state->state }}</td>
                                    <td><a href="" class="" data-mycountry="{{ $state->country }}" data-mycid="{{$state->country_id}}" data-mystate="{{ $state->state }}" data-myid="{{ $state->id }}" data-toggle="modal" data-target="#edit" ><i class="fa fa-edit green" ></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $states->links('pagination::bootstrap-4')  }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <span>ADD STATE</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.state') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div>
                                    <label> Country</label></div>
                                <div>
                                    <select class="form-control" aria-label="Default select example" name="country">
                                        <option selected>--Select--</option>
                                        @foreach($countries as $country)
                                        <option  value="{{ $country->id }}">{{ $country->country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('country')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div>
                                    <label>State</label></div>
                                <div>
                                    <input type="text" name="state" class="form-control">
                                </div>
                                @error('state')
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

                            <h4 class="modal-title" id="myModalLabel">Edit State</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form  action="{{route('update.state')}}"  method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input type="hidden" name="id" value="" id="myid">
                                <div class="form-group">
                                    <div>
                                        <label> Country</label></div>
                                    <div>
                                        <select class="form-control" aria-label="Default select example" name="country" id="mycid">

                                            @foreach($countries as $country)
                                                <option  value="{{ $country->id }}"


                                                >{{ $country->country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('quali_level')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" name="state" class="form-control" id="mystate" >
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
            var country= button.data('mycountry')
            var cid = button.data('mycid')
            var state = button.data('mystate')
            var id=button.data('myid')
            var modal = $(this)

            modal.find('.modal-body #mycountry').val(country)
            modal.find('.modal-body #mycid').val(cid)
            modal.find('.modal-body #mystate').val(state)
            modal.find('.modal-body #myid').val(id)
        })
    </script>


@endsection('admin')
