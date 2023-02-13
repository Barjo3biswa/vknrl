@extends('layouts.website')

@section('content')
<section class="">
    <div class="container">
        <div class="row align-content-center">
            <div class="col-lg-6 col-xl-6">
                @include("student.guidelines")
            </div>
            <div class="col-lg-6 col-xl-6">
                <form class="" method="POST" action="{{ route('student.password.request') }}"
                    style="padding-right:35px; padding-left:20px;" autocomplete="off">
                    {{ csrf_field() }}

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="control-label">E-Mail Address</label>

                        <input id="email" type="email" class="form-control" name="email"
                            value="{{ $email or old('email') }}" required autofocus autocomplete="off">

                        @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="control-label">Password</label>

                        <input id="password" type="password" class="form-control" name="password" required minlength="8" autocomplete="off"
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

                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password-confirm" class="control-label">Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            required autocomplete="off">

                        @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
@section('js')
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
</script>    
@endsection