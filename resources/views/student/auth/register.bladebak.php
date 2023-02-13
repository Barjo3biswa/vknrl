@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Register</div>

    <div class="panel-body">
        <form class="" method="POST" action="{{ route('student.register') }}"  style="padding-right:35px; padding-left:20px;">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="control-label">Name</label>

                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required
                    autofocus>

                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                <label for="mobile_no" class="control-label">Mobile No</label>

                <input id="mobile_no" type="number" class="form-control" name="mobile_no" value="{{ old('mobile_no') }}" required>

                @if ($errors->has('mobile_no'))
                <span class="help-block">
                    <strong>{{ $errors->first('mobile_no') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="control-label">E-Mail Address</label>

                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="control-label">Password</label>

                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group">
                <label for="password-confirm" class="control-label">Confirm Password</label>

                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    Register
                </button>
            </div>
            <div class="form-group">
                <a href="{{route("student.login")}}">
                    <button type="button" class="btn btn-warning btn-block">
                        Already Registered ? Login Here
                    </button>
                </a>
            </div>
        </form>
    </div>
</div>

@endsection