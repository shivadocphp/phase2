<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>BlackBox Login JSC - Your Hiring Partner</title>
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="icon" href="{{ asset ('backend/assets/images/brand/jsc.ico')}}" type="image/x-icon" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset ('backend/assets/images/brand/jsc.ico')}}" />
</head>

<body>
    <div class="loginpage">
        <div class="container">
            <div class="row loginbor">
                <div class="col-md-8">
                    <?php $loginimage = \App\Models\LoginImage::where('current_image', '=', 'Y')->first();
                    if ($loginimage != null) {
                        $image = $loginimage->image_location;
                        echo '<img width="50%" height="50%" src="' . asset('storage/' . $image) . '">';
                    } else { ?>
                        <img width="100%" height="50%" src="{{ asset('storage/images/login/login-new-page.jpg') }}">
                    <?php } ?>
                </div>
                <div class="col-md-4 log">
                    <h3 class="head">Login Now</h3><br><br>
                    <form method="post" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="lab">Official Email ID</label>
                            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="form-control autofocus @error('email') is-invalid @enderror">
                            @error('email')
                            <span class="invalid-feedback" role="alert" style="color: red">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="lab">Password</label>
                            <input type="password" name="password" id="password" required autocomplete="current-password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                            <span class="invalid-feedback" role="alert" style="color: red">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="checkbox">
                            <label class="remember">
                                <input type="checkbox" name="remember"> Remember me
                            </label>
                        </div><br>
                        <input type="submit" name="" class="button btn btn-default" value="Sign In">
                        @if (Route::has('password.request'))
                        <p class="remember">Forgot your password?
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Click Here') }}
                            </a>
                        </p>
                        @endif
                    </form>
                </div>
                <div class="col-md-12">
                    <p>Powered By <a target="_blank" href="http://digitalorbiscreators.org/">Digital Orbis Creators LLP</a></p>
                </div>
            </div>
        </div>
    </div>
    <!--<section>
    
    
    <div class="imgBx">
        <?php $loginimage = \App\Models\LoginImage::where('current_image', '=', 'Y')->first();
        if ($loginimage != null) {
            $image = $loginimage->image_location;
        ?>
        <img src="{{ asset('storage/'.Auth::user()->profile_photo_path) }}" height="75" width="75" alt="" >

           {{-- <img src="{{ asset('public/storage/images/'.$image) }}"> --}}
           <img src="{{ url('storage/'.$image) }}">
       <?php } else {
        ?>
        <img src="{{asset('/images/login_page.jpg')}}">

        <?php  } ?>
    </div>
    <div class="contentBx">
        <div class="formBx">
            <h2>Login</h2>
            <form method="post" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="inputBx">
                    <span>Official Email ID</span>
                    <input  type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="autofocus @error('email') is-invalid @enderror">
                    @error('email')
                    <span class="invalid-feedback" role="alert" style="color: red">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div class="inputBx">
                    <span>Password</span>
                    <input type="password" name="password" id="password" required autocomplete="current-password" class="@error('password') is-invalid @enderror">
                    @error('password')
                    <span class="invalid-feedback" role="alert" style="color: red">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div class="remember">
                   <label><input type="checkbox" name="remember" >Remember me</label>


                </div>
                <div class="inputBx">
                   <input type="submit" name="" value="Sign In">
                   <button  type="submit" class="custom-btn btn-12"><span>Click!</span><span>Read More</span></button>
                </div><div class="inputBx">
                    @if (Route::has('password.request'))
                        <p>Forgot your passwordd?
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                           {{ __('Click Here') }}
                        </a></p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>

</html>