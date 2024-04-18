@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/Districts </h3>

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
                        <span>DISTRICTS</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">State</th>
                                <th scope="col">District</th>
                                <th scope="col">Edit</th>
                            </tr>
                            </thead>
                            @foreach($districts as $district)
                                <tr>
                                    <td>{{ $districts->firstItem()+$loop->index }}</td>
                                    <td>{{ $district->state }}</td>
                                    <td>{{ $district->district }}</td>
                                    <td><a href="" class="btn-sm btn-success" data-mystate="{{ $district->state }}" data-mysid="{{$district->state_id}}" data-mydistrict="{{ $district->district }}" data-myid="{{ $district->id }}" data-toggle="modal" data-target="#edit" >Edit</a></td>
                                </tr>
                                @endforeach
                                </tbody>
                        </table>
                        {{ $districts->links('pagination::bootstrap-4')  }}

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <span>ADD DISTRICT</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.district') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div>
                                    <label> Country</label></div>
                                <div>
                                    <select class="form-control" aria-label="Default select example" name="country" id="country-dd">
                                        <option selected>--Select Country--</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('country')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div>
                                    <label> State</label></div>
                                <div>
                                    <select class="form-control" aria-label="Default select example" name="state" id="state-dd">
                                        <option selected>--Select State--</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('country')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div>
                                    <label>District</label></div>
                                <div>
                                    <input type="text" name="district" class="form-control">
                                </div>
                                @error('district')
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

                            <h4 class="modal-title" id="myModalLabel">Edit District</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form  action="{{route('update.district')}}"  method="POST">
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
                                                    {{ $state->id == $district->state_id ? 'selected':'' }}

                                                >{{ $state->state }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('quali_level')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>District</label>
                                    <input type="text" name="district" class="form-control" id="mydistrict" >
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
            var state= button.data('mystate')
            var sid = button.data('mysid')
            var district = button.data('mydistrict')
            var id=button.data('myid')
            var modal = $(this)

            modal.find('.modal-body #mystate').val(state)
            modal.find('.modal-body #mysid').val(sid)
            modal.find('.modal-body #mydistrict').val(district)
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
                        $('#city-dd').html('<option value="">Select City</option>');
                    }
                });
            });

        });

    </script>
@endsection('admin')
