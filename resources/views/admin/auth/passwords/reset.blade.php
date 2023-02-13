@extends('admin.layout.auth')
@section("css")
<style>
.list-group{
    z-index:10;display:none; 
	position:absolute; 
    color:red;
}
.msg
{
	position:absolute; 
    color:red;
}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    <div class="col-md-8">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/password/reset') }}"
                            autocomplete="off" onSubmit="return confirmPasswordCondition(this)">
                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                        value="{{ $email or old('email') }}" autofocus autocomplete="off">

                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password"
                                        autocomplete="new-password">

                                    @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" autocomplete="off">

                                    @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Reset Password
                                    </button>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="col-md-4">
                        <ul  id="d1" class="list-group">
                            <li class="list-group-item list-group-item-success">Password Conditions</li>
                            <li class="list-group-item" id=d12><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Upper Case Letter</li>
                            <li class="list-group-item" id=d13 ><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Lower Case Letter </li>
                            <li class="list-group-item" id=d14><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Special Char </li>
                            <li class="list-group-item" id=d15><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Number</li>
                            <li class="list-group-item" id=d16><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Length 8 Char</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("js")
<script>
    $(document).ready(function() {        
        var flag = 'T';
        $('#password').keyup(function(){
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
                $("#d1").fadeOut();
                $('#display_box').css("color","green");
                $('#display_box').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> "+str);
            }else{
                $("#d1").show();
                $('#display_box').css("color","red");
                $('#display_box').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> "+str);
            }
        });
        ///////////////////
        $('#password').blur(function(){
            $("#d1").fadeOut();
        });
        ///////////
        $('#password').focus(function(){
            $("#d1").show();
        });
        ////////////
        confirmPasswordCondition = function(){
            console.log(flag);
            if(flag == "F"){
                alert("Please match password Conditions.");
                $("#password").focus();
                return false;
            }
            return true;
        }
    })
</script>
@endsection