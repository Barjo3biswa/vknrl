@extends('layouts.website')

@section('content')

<section class="">
    <div class="container">
        <div class="row align-content-center">
            <div class="col-lg-6 col-xl-6">
                @include("student.guidelines")
            </div>
            <div class="col-lg-6 col-xl-6">

                <div class="login register">
                    <i ripple>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill="#C7C7C7"
                                d="m12,2c-5.52,0-10,4.48-10,10s4.48,10,10,10,10-4.48,10-10-4.48-10-10-10zm1,17h-2v-2h2zm2.07-7.75-0.9,0.92c-0.411277,0.329613-0.918558,0.542566-1.20218,1.03749-0.08045,0.14038-0.189078,0.293598-0.187645,0.470854,0.02236,2.76567,0.03004-0.166108,0.07573,1.85002l-1.80787,0.04803-0.04803-1.0764c-0.02822-0.632307-0.377947-1.42259,1.17-2.83l1.24-1.26c0.37-0.36,0.59-0.86,0.59-1.41,0-1.1-0.9-2-2-2s-2,0.9-2,2h-2c0-2.21,1.79-4,4-4s4,1.79,4,4c0,0.88-0.36,1.68-0.930005,2.25z" />
                        </svg>
                    </i>
                    <span>New user register here</span>
                    <form class="" method="POST" action="{{ route('student.register') }}"
                        style="padding-right:35px; padding-left:20px;" autocomplete="off" onsubmit="return LoginEncrypter();">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}" id="u">
                            {{-- <label for="name" class="control-label">Name</label> --}}

                            <input id="name" type="text"
                                class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                value="{{ old('name') }}" required autofocus size="18" alt="login" required="">
                            <span class="form-highlight"></span>
                            <span class="form-bar"></span>
                            <label for="username" class="float-label">Name</label>

                            @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('mobile_no') ? ' has-error' : '' }}" id="u">
                            {{-- <label for="mobile_no" class="control-label">Mobile No</label> --}}

                            <input id="mobile_no" type="number"
                                class="form-control {{ $errors->has('mobile_no') ? ' is-invalid' : '' }}"
                                name="mobile_no" value="{{ old('mobile_no') }}" required size="10" maxlength="10"
                                minlength="10">
                            <span class="form-highlight"></span>
                            <span class="form-bar"></span>
                            <label for="username" class="float-label">Mobile No.</label>

                            @if ($errors->has('mobile_no'))
                            <span class="help-block">
                                <strong>{{ $errors->first('mobile_no') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}" id="u">
                            {{-- <label for="email" class="control-label">E-Mail Address</label> --}}

                            <input id="u" id="email" type="text"
                                class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                value="{{ old('email') }}" required spellcheck=false size="50" alt="login">
                            <span class="form-highlight"></span>
                            <span class="form-bar"></span>
                            <label for="username" class="float-label">Email </label>

                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}" id="p">
                            {{-- <label for="password" class="control-label">Password</label> --}}

                            <input id="password" type="password"
                                class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                                required minlength="8"
                                data-html="true" 
                                data-toggle="popover" 
                                data-trigger="focus" 
                                title="Password Conditions" 
                                data-content="<ul  id='d1' class='list-group'>
                                        {{-- <li class='list-group-item list-group-item-success'>Password Conditions</li> --}}
                                        <li class='list-group-item' id=d12><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Upper Case Letter</li>
                                        <li class='list-group-item' id=d13 ><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Lower Case Letter </li>
                                        <li class='list-group-item' id=d14><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Special Char </li>
                                        <li class='list-group-item' id=d15><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Number</li>
                                        <li class='list-group-item' id=d16><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Length 8 Char</li>
                                    </ul>">
                            {{-- <input id="password" type="password" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                            name="password" required> --}}
                            <span class="form-highlight"></span>
                            <span class="form-bar"></span>
                            <label for="password" class="float-label">Password (min 8 char alphabet & numeric only)</label>

                            @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group">
                            {{-- <label for="password-confirm" class="control-label">Confirm Password</label> --}}

                            <input id="password-confirm" type="password"
                                class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                name="password_confirmation" required>
                            <span class="form-highlight"></span>
                            <span class="form-bar"></span>
                            <label for="password" class="float-label">Confirm Password</label>
                        </div>

                        <div class="form-group row {{ $errors->has('captcha') ? ' has-error' : '' }}" id="p">
                                {{-- <label for="captcha" class="control-label">captcha</label> --}}
                                <div class="col-sm-6">
                                        <p><img src="{!!captcha_src()!!}" alt="catcha" style="border:1px solid black;"></p>
                                </div>
                                <div class="col-sm-6">
    
                                    <input id="captcha" type="text" class="form-control {{ $errors->has('captcha') ? ' is-invalid' : '' }}" name="captcha" required  size="18" alt="catcha" spellcheck="false">
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
                            <button id="submit" type="submit" ripple>Register</button>
                        </div>
                        {{-- <div class="form-group">
                            <a href="{{route("student.login")}}">
                        <button type="button" class="btn btn-warning btn-block">
                            Already Registered ? Login Here
                        </button>
                        </a>
                </div> --}}
                </form>

            </div>
        </div>
    </div>
    </div>
</section>

@endsection
@section("js")
@php
    $admin_login_crypt = md5(time().uniqid());
    // $crypt2 = md5(date('Y-md h:is').uniqid());

    \Session::put('admin_login_crypt', $admin_login_crypt);
    // Session::set('crypt2', $crypt2);
@endphp
{{-- <script src="{{asset_public("js/app.js")}}"></script> --}}
<script src="{{asset_public("js/aes.js")}}"></script>
<script src="{{asset_public("js/aes-json-format.js")}}"></script>
<script>
$(window).load(function(){
    $('input#password').popover({trigger:'focus'});
});
$('#password').keyup(function(){
    console.log("Key Up event Fired");
    var str=$('#password').val();
    var upper_text= new RegExp('[A-Z]');
    var lower_text= new RegExp('[a-z]');
    var number_check=new RegExp('[0-9]');
    var special_char= new RegExp('[!/\'!$#%@\]');
    flag = 'T';
    if(str.match(upper_text)){
        $('#d12').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Upper Case Letter ");
        $('#d12').css("color", "green");
        }else{$('#d12').css("color", "red");
        $('#d12').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Upper Case Letter ");
        flag='F';
    }
    
    if(str.match(lower_text)){
        console.log("reached");
        $('#d13').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Lower Case Letter ");
        $('#d13').css("color", "green");
    }else{$('#d13').css("color", "red");
        $('#d13').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Lower Case Letter ");
        flag='F';
    }
    
    if(str.match(special_char)){
        $('#d14').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Special Char ");
        $('#d14').css("color", "green");
    }else{
        $('#d14').css("color", "red");
        $('#d14').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Special Char ");
        flag='F';
    }
    
    if(str.match(number_check)){
        $('#d15').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Number ");
        $('#d15').css("color", "green");
    }else{
        $('#d15').css("color", "red");
        $('#d15').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Number ");
        flag='F';
    }
    
    
    if(str.length>7){
        $('#d16').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Length 8 Char ");
        
        $('#d16').css("color", "green");
    }else{
        $('#d16').css("color", "red");
        $('#d16').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Length 8 Char ");
    
        flag='F';
    }
    if(flag=='T'){
        // $("#d1").fadeOut();
        $('#display_box').css("color","green");
        $('#display_box').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> "+str);
    }else{
        // $("#d1").show();
        $('#display_box').css("color","red");
        $('#display_box').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> "+str);
    }
});
$("#password").on('shown.bs.popover', function(){
    $('#password').keyup();
});
confirmPasswordCondition = function(){
    if(flag == "F"){
        alert("Please match password Conditions.");
        $("#password").focus();
        return false;
    }
    return true;
}

LoginEncrypter = function(Obj){
    if(!confirmPasswordCondition()){
        return false;
    }
    var encrypted_pass = CryptoJS.AES.encrypt(jQuery("#password").val(), "{{$admin_login_crypt}}", {format: CryptoJSAesJson}).toString();
    var ecrypted_password_confirmation = CryptoJS.AES.encrypt(jQuery("#password-confirm").val(), "{{$admin_login_crypt}}", {format: CryptoJSAesJson}).toString();
    {{--  // var decrypted_pass = CryptoJS.AES.decrypt(encrypted_pass, "123456", {format: CryptoJSAesJson}).toString();
    // console.log(encrypted_pass);
    // console.log(decrypted_pass);--}}
    jQuery("#password").val(btoa(encrypted_pass));
    jQuery("#password-confirm").val(btoa(ecrypted_password_confirmation));
    return true;
}
</script>
@endsection