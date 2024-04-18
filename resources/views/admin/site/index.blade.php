@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Backend/Site </h3>

</div>
<div class="container">
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <span>Default Settings</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('update.settings') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <label> Late Mark At</label>
                                <input type="text" name="late_mark_at" class="form-control" value="{{$settings->late_mark_at}}"><span style="display: inline;"><b>min</b></span>
                            </div>
                            <div class="col-md-6">
                                <label>Email Send To</label> <span> <b>(, seperated)</b></span>
                                <input type="text" name="email_send_to" class="form-control" value="{{$settings->email_send_to}}" >

                            </div>
                        </div>

                        <div class="col" align="center">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>

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
                    <span>LOGIN PAGE IMAGES</span>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Image</th>
                                <th scope="col">Active</th>
                                <th scope="col">Edit</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $i = 1; ?>
                            @foreach($login_images as $lm)
                            <tr>
                                <td>{{ $login_images->firstItem()+$loop->index }}</td>
                                {{-- <td><img src="{{ Storage::url($lm->image_location) }}" height="75" width="75" alt="" /></td> --}}
                                <td><img src="{{ url('storage/'.$lm->image_location) }}" height="75" width="75" alt="" /></td>
                                <td>{{ $lm->current_image }}</td>
                                <td><a href="{{route('update.loginImage',$lm->id)}}" class="btn-sm btn-success">Set as Login Image</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $login_images->links('pagination::bootstrap-4')  }}
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <span>ADD IMAGE</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('store.loginImage') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="form-group">
                            <div>
                                <label>Image</label>
                            </div>
                            <div>
                                <input type="file" name="login_image" class="form-control" accept="image/jpg,image/png,image/jpeg,image/gif,image/svg">
                            </div>
                            @error('login_image')
                            <span class="text-danger">{{ $message  }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div>
                                <label>Set as current Image</label>
                            </div>
                            <div>
                                <select class="form-control" name="current_image">
                                    <option value="">--select--</option>
                                    <option value="Y">Yes</option>
                                    <option value="N">No</option>
                                </select>
                            </div>
                            @error('current_image')
                            <span class="text-danger">{{ $message  }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col" align="center">
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="{{asset('js/app.js')}}"></script>

@endsection('admin')