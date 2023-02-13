@extends('student.layouts.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('student.otp-verify') }}">
                        {{ csrf_field() }}
                        @if(session()->has("status"))
                            <div class="alert alert-warning">
                                <strong>Notice:</strong> {{session()->get("status")}}
                            </div>
                        @endif
                        <div class="form-group{{ $errors->has('otp') ? ' has-error' : '' }}">
                            <label for="otp" class="col-md-4 control-label">OTP</label>

                            <div class="col-md-6">
                                <input id="otp" type="text" class="form-control" name="otp" required>

                                @if ($errors->has('otp'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('otp') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Verify OTP
                                </button>
                                <a class="btn btn-link" href="{{ route('student.otp-resend') }}">
                                    Resend OTP
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