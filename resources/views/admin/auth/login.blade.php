@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" autocomplete="off" role="form" method="POST" action="{{ url('/admin/login') }}" onSubmit="return LoginEncrypter(this)">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Username</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" autofocus autocomplete="off">

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" autocomplete="new-password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
                            {{-- <label for="password" class="col-md-4 control-label">Password</label> --}}
                            <div class="col-md-4 text-right">
                                    <p><img src="{!!captcha_src()!!}" alt="catcha" style="border:1px solid black;"></p>
                            </div>    

                            <div class="col-md-6">
                                <input id="captcha" required type="number" class="form-control" name="captcha" placeholder="Captcha" autocomplete="off">

                                @if ($errors->has('captcha'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('captcha') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ url('/admin/password/reset') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("js")
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
        var encrypted_pass = CryptoJS.AES.encrypt($("#password").val(), "{{$admin_login_crypt}}", {format: CryptoJSAesJson}).toString();
        // var decrypted_pass = CryptoJS.AES.decrypt(encrypted_pass, "123456", {format: CryptoJSAesJson}).toString();
        // console.log(encrypted_pass);
        // console.log(decrypted_pass);
        $("#password").val(encrypted_pass);
        return true;
    }
</script>
@endsection
