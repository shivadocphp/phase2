
@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/GST Code </h3>

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
                        <span>GST Code</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">State</th>
                                <th scope="col">GST Code</th>
                                <th scope="col">Edit</th>
                            </tr>
                            </thead>
                            @foreach($gst as $g)
                                <tr>
                                    <td>{{ $gst->firstItem()+$loop->index }}</td>
                                    <td>{{ $g->state }}</td>
                                    <td>{{ $g->gstcode }}</td>
                                    <td><a href="" class="" data-mystate="{{ $g->state }}" data-mysid="{{$g->state_id}}" data-mygst="{{ $g->gstcode }}" data-myid="{{ $g->id }}" data-toggle="modal" data-target="#edit" ><i class="fa fa-edit green" ></i></a></td>
                                </tr>
                                @endforeach
                                </tbody>
                        </table>
                        {{ $gst->links('pagination::bootstrap-4')  }}

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <span>ADD GST CODE</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.gst') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <div>
                                    <label> State</label></div>
                                <div>
                                    <select class="form-control" aria-label="Default select example" name="state" id="state-dd">
                                        <option selected>--Select State--</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('state')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div>
                                    <label>GST Code</label></div>
                                <div>
                                    <input type="text" name="gstcode" class="form-control">
                                </div>
                                @error('gstcode')
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

                            <h4 class="modal-title" id="myModalLabel">Edit Gst Code</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form  action="{{route('update.gst')}}"  method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input type="hidden" name="id" value="" id="myid">
                                <div class="form-group">
                                    <div>
                                        <label> State</label></div>
                                    <div>
                                        <select class="form-control" aria-label="Default select example" name="state" id="mysid">

                                            @foreach($states as $state)
                                                <option  value="{{ $state->id }}"

                                                >{{ $state->state }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('quali_level')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>GST Code</label>
                                    <input type="text" name="gstcode" class="form-control" id="mygst" >
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
            var state= button.data('mystate')
            var sid = button.data('mysid')
            var gst = button.data('mygst')
            var id=button.data('myid')
            var modal = $(this)

            modal.find('.modal-body #mystate').val(state)
            modal.find('.modal-body #mysid').val(sid)
            modal.find('.modal-body #mygst').val(gst)
            modal.find('.modal-body #myid').val(id)
        })
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#country-dd').on('change', function () {
                var idCountry = this.value;
                $("#state-dd").html('');
                $.ajax({
                    url: "{{url('api/fetch-states')}}",
                    type: "POST",
                    data: {
                        country_id: idCountry,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#state-dd').html('<option value="">Select State</option>');
                        $.each(result.states, function (key, value) {
                            $("#state-dd").append('<option value="' + value
                                .id + '">' + value.state + '</option>');
                        });
                        $('#city-dd').html('<option value="">Select </option>');
                    }
                });
            });

        });

    </script>
@endsection('admin')
