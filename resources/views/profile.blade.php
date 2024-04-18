@extends('admin.admin_master')
@section('admin')

<style>
    * {
        box-sizing: border-box;
    }

    /* Create two equal columns that floats next to each other */
    .column {
        float: left;
        width: 50%;
        padding: 10px;
        height: 300px;
        /* Should be removed. Only for demonstration */
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }
</style>
<div class="page-header">
    <h3 class="page-title">Profile Updation</h3>

</div>

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
    <div class="card-body">
        <div class="col-md-12">
            <form action="{{ route('update.profileimage') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h5>NAME</h5>
                <input type="text" name="name" value="{{Auth::user()->name}}" disabled>
                <h5>UPDATE PROFILE PHOTO</h5>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="file" name="profile_pic" accept="image/jpg,image/png,image/jpeg,image/gif,image/svg">
                            <img src="{{ asset('storage/'.Auth::user()->profile_photo_path) }}" height="75" width="75" alt="">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                    {{-- <img src="{{ Storage::url(\Illuminate\Support\Facades\Auth::user()->profile_photo_path) }}"> --}}
                </div>
            </form>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="col-md-12">
            <h5>UPDATE PASSWORD</h5>
            <form action="{{ route('update.profile') }}" method="POST">
                {{method_field('patch')}}
                {{csrf_field()}}

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2">
                            Password
                        </div>
                        <div class="col-md-3">
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-md-3">
                            Confirm Password
                        </div>
                        <div class="col-md-3">
                            <input type="password" name="cpassword" class="form-control">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col" align="center">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- <div class="card">
                <div class="card-body">

                </div>
            </div> --}}

<div class="row">
    <div class="col">
    </div>
</div>


<script>
    function getConfirmation() {
        var retVal = confirm("Do you want to continue ?");
        if (retVal == true) {
            return true;
        } else {
            return false;
        }
    }
</script>


@endsection('admin')