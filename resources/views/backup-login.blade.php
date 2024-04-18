<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>BlackBox Login</title>
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
</head>
<body>
<section>
    <div class="imgBx">
        <?php $loginimage = \App\Models\LoginImage::where('current_image','=','Y')->first();
        if($loginimage!=null){
        $image = $loginimage->image_location;
        ?>
           <!-- <img src="{{ Storage::url($image) }}">-->
           {{-- <img src="{{ asset('public/storage/images/'.$image) }}"> --}}
           <img src="{{ url('storage/'.$image) }}">
       <?php }else{
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
                  <!--  <button  type="submit" class="custom-btn btn-12"><span>Click!</span><span>Read More</span></button>-->
                </div><div class="inputBx">
                    @if (Route::has('password.request'))
                        <p>Forgot your password?
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                           {{ __('Click Here') }}
                        </a></p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>
</body>
</html>
