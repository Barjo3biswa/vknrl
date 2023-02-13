@extends('layouts.website')

@section('content')
<section class="">
    <div class="container">
        <div class="row align-content-center">
            <div class="col-lg-6 col-xl-6">
                @include("student.guidelines")
            </div>
            <div class="col-lg-6 col-xl-6">
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

            <form class="" method="POST" action="{{ route('student.password.email') }}"
                style="padding-right:35px; padding-left:50px;" autocomplete="off">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="control-label">E-Mail Address</label>

                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email"
                        required>

                    @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
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
                    <button type="submit" class="btn btn-primary">
                        Send Password Reset Link
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
</section>
@endsection