@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/Country </h3>

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
                        <span>COUNTRIES</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Country</th>
                                <th scope="col">Code</th>
                                <th scope="col">Phone Code</th>
                                <th scope="col" >Edit</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            @foreach($countries as $country)
                                <tr>
                                    <td>{{ $countries->firstItem()+$loop->index }}</td>
                                    <td>{{ $country->country }}</td>
                                    <td>{{ $country->code }}</td>
                                    <td>{{ $country->phonecode }}</td>
                                    <td><a href="" class="" data-mycode="{{ $country->code }}" data-mycountry="{{$country->country}}"
                                           data-myphonecode="{{$country->phonecode}}" data-myid="{{ $country->id }}" data-toggle="modal" data-target="#edit" ><i class="fa fa-edit green" ></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $countries->links('pagination::bootstrap-4')  }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <span>ADD COUNTRY</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.country') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div>
                                    <label> Country Code</label></div>
                                <div>
                                    <input type="text" name="code" class="form-control">
                                </div>
                                @error('code')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div>
                                    <label>Country Name</label></div>
                                <div>
                                    <input type="text" name="country" class="form-control">
                                </div>
                                @error('country')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div>
                                    <label>Phone Code</label></div>
                                <div>
                                    <input type="text" name="phonecode" class="form-control">
                                </div>
                                @error('phonecode')
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
                        <form  action="{{route('update.country')}}"  method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input type="hidden" name="country_id" value="" id="myid">
                                <div class="form-group">
                                    <label>Country code</label>
                                    <input type="text" name="code" class="form-control" id="mycode" >
                                </div>
                                <div class="form-group">
                                    <label>Country name</label>
                                    <input type="text" name="country" class="form-control" id="mycountry" >
                                </div>
                                <div class="form-group">
                                    <label>Phone code</label>
                                    <input type="text" name="phonecode" class="form-control" id="myphonecode">
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
            var code = button.data('mycode')
            var country = button.data('mycountry')
            var phonecode = button.data('myphonecode')
            var id=button.data('myid')
            var modal = $(this)

            modal.find('.modal-body #mycode').val(code)
            modal.find('.modal-body #mycountry').val(country)
            modal.find('.modal-body #myphonecode').val(phonecode)
            modal.find('.modal-body #myid').val(id)
        })
    </script>
@endsection('admin')
