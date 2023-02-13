<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset_public('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <div class="container-fluid" style="min-height:100px; margin-bottom:20px;">
        <img src="{{asset_public("banner.jpg")}}" alt="" class="img-responsive">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-6">
                    @include("student.guidelines")
                
                </div>
                <div class="col-md-4 col-md-offset-1 col-sm-6">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @include('common/footer')
    <!-- Scripts -->
    <script src="{{ asset_public('js/app.js') }}"></script>
    <script src="{{ asset_public('js/jquery.validate.js') }}"></script>
    <script src="{{ asset_public('js/bootstrap-notify.min.js') }}"></script>
</body>
</html>
