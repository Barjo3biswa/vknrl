@extends('layouts.website')

@section('content')
<section class="">
    <div class="container">
        <div class="row align-content-center">
            <div class="col-lg-6 col-xl-6">
                @include("student.guidelines")
            </div>
            <div class="col-lg-6 col-xl-6">
                <div class="login" style="height:500px;">
                    <i ripple>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill="#C7C7C7"
                                d="m12,2c-5.52,0-10,4.48-10,10s4.48,10,10,10,10-4.48,10-10-4.48-10-10-10zm1,17h-2v-2h2zm2.07-7.75-0.9,0.92c-0.411277,0.329613-0.918558,0.542566-1.20218,1.03749-0.08045,0.14038-0.189078,0.293598-0.187645,0.470854,0.02236,2.76567,0.03004-0.166108,0.07573,1.85002l-1.80787,0.04803-0.04803-1.0764c-0.02822-0.632307-0.377947-1.42259,1.17-2.83l1.24-1.26c0.37-0.36,0.59-0.86,0.59-1.41,0-1.1-0.9-2-2-2s-2,0.9-2,2h-2c0-2.21,1.79-4,4-4s4,1.79,4,4c0,0.88-0.36,1.68-0.930005,2.25z" />
                        </svg>
                    </i>
                    <span>Existing user login here</span>
                    <form class="" method="POST" action="{{ route('student.login',['who'=>$who]) }}"
                        autocomplete="off" onsubmit="return LoginEncrypter(this)">
                        <input autocomplete="off" name="hidden" type="text" style="display:none;">
                        {{ csrf_field() }}
                        @if(session()->has("status"))
                            <div class="alert alert-info">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                {{session()->get("status")}}
                            </div>
                        @endif
                        <div class="form-group{{ $errors->has('mobile_no') ? ' has-error' : '' }}" id="u">
                            {{-- <label for="mobile_no" class="control-label">Mobile No</label> --}}
    
                            <input id="mobile_no" type="text" class="form-control {{ $errors->has('mobile_no') ? ' is-invalid' : '' }}" name="mobile_no"
                                value="{{ old('mobile_no') }}" required autofocus alt="login" spellcheck=false size="18" autocomplete="off">
                                <span class="form-highlight"></span>
								<span class="form-bar"></span>
								<label for="username" class="float-label">Mobile No</label>
                            @if ($errors->has('mobile_no'))
                            <span class="help-block">
                                <strong>{{ $errors->first('mobile_no') }}</strong>
                            </span>
                            @endif
                            <erroru>
                                Mobile No is required
                                <i>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M0 0h24v24h-24z" fill="none" />
                                        <path d="M1 21h22l-11-19-11 19zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z" />
                                    </svg>
                                </i>
                            </erroru>
                        </div>
    
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}" id="p">
                            {{-- <label for="password" class="control-label">Password</label> --}}
    
                            <input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required  size="18" alt="login" spellcheck="false" autocomplete="new-password">
                            <span class="form-highlight"></span>
                            <span class="form-bar"></span>
                            <label for="password" class="float-label">Password</label>
                            @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                            <errorp>
									Password is required
                                <i>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M0 0h24v24h-24z" fill="none" />
                                        <path d="M1 21h22l-11-19-11 19zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z" />
                                    </svg>
                                </i>
                            </errorp>
                        </div>
                        <div class="form-group row {{ $errors->has('captcha') ? ' has-error' : '' }}" id="p">
                            {{-- <label for="captcha" class="control-label">captcha</label> --}}
                            <div class="col-sm-6">
                                    <p><img src="{!!captcha_src()!!}" alt="captcha" style="border:1px solid black;"></p>
                            </div>
                            <div class="col-sm-6">

                                <input id="captcha" type="text" class="form-control {{ $errors->has('captcha') ? ' is-invalid' : '' }}" name="captcha" required  size="18" alt="captcha" spellcheck="false" autocomplete="off">
                                <span class="form-highlight"></span>
                                <span class="form-bar"></span>
                                <label for="captcha" class="float-label">Captcha</label>
                                @if ($errors->has('captcha'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('captcha') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
    
                        <div class="form-group">
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} id="rem">
                                    <label for="rem">Stay Signed in</label>
								<button id="submit" type="submit" ripple>Log in</button>
                        </div>
    
                        {{-- <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                Login
                            </button>
    
                            <a class="btn btn-link" href="{{ route('student.password.request') }}">
                                Forgot Your Password?
                            </a>
                            <a class="btn btn-link" href="{{ route('student.register') }}">
                            Register
                            </a>
                        </div> --}}
                        {{-- <div class="form-group">
                            <a href="{{route("student.register")}}">
                                <button type="button" class="btn btn-warning btn-block">
                                    New user ? Register Here
                                </button>
                            </a>
                        </div> --}}
                    </form>
                    <footer><a href="{{route("student.password.request")}}">Forgot Your Password?</a></footer>
                    {{-- <footer><a href="{{route("student.register")}}">New user ? Register Here</a></footer> --}}
                </div>
            </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section("js")
<script src="{{asset_public("js/app.js")}}"></script>
<script src="{{asset_public("js/aes.js")}}"></script>
<script src="{{asset_public("js/aes-json-format.js")}}"></script>
@php
    $admin_login_crypt = md5(time().uniqid());
    // $crypt2 = md5(date('Y-md h:is').uniqid());

    \Session::put('admin_login_crypt', $admin_login_crypt);
    // Session::set('crypt2', $crypt2);
@endphp
<script>
    LoginEncrypter = function(Obj){
        var encrypted_pass = CryptoJS.AES.encrypt(jQuery("#password").val(), "{{$admin_login_crypt}}", {format: CryptoJSAesJson}).toString();
        // var decrypted_pass = CryptoJS.AES.decrypt(encrypted_pass, "123456", {format: CryptoJSAesJson}).toString();
        // console.log(encrypted_pass);
        // console.log(decrypted_pass);
        jQuery("#password").val(encrypted_pass);
        return true;
    }
</script>
@endsection