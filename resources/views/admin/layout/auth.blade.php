<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Multi Auth Guard') }} @yield('title')</title>

    <!-- Styles -->
    <link href="{{asset_public("css/app.css")}}" rel="stylesheet">

    <link href="{{ asset_public('css/zebra_bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset_public('css/inputmask.css') }}" rel="stylesheet">
    <link href="{{asset_public("css/font-awesome.min.css")}}" rel="stylesheet">
    
    <link href="{{asset_public("css/loader.css")}}" rel="stylesheet">
    <link href="{{asset_public("css/custom.css")}}" rel="stylesheet">
    <link href="{{asset_public("css/paper-bootstrap.css")}}" rel="stylesheet">
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!}
    </script>
    <style>
        .navbar-default {
            background-color: #319dd3;
            border-color: #1a6184;
        }

        .navbar-default .navbar-brand {
            color: #fcfeff;
        }

        .navbar-default .navbar-brand:hover,
        .navbar-default .navbar-brand:focus {
            color: #ffffff;
        }

        .navbar-default .navbar-text {
            color: #fcfeff;
        }

        .navbar-default .navbar-nav>li>a {
            color: #fcfeff;
        }

        .navbar-default .navbar-nav>li>a:hover,
        .navbar-default .navbar-nav>li>a:focus {
            color: #ffffff;
        }

        .navbar-default .navbar-nav>.active>a,
        .navbar-default .navbar-nav>.active>a:hover,
        .navbar-default .navbar-nav>.active>a:focus {
            color: #ffffff;
            background-color: #1a6184;
        }

        .navbar-default .navbar-nav>.open>a,
        .navbar-default .navbar-nav>.open>a:hover,
        .navbar-default .navbar-nav>.open>a:focus {
            color: #ffffff;
            background-color: #1a6184;
        }

        .navbar-default .navbar-toggle {
            border-color: #1a6184;
        }

        .navbar-default .navbar-toggle:hover,
        .navbar-default .navbar-toggle:focus {
            background-color: #1a6184;
        }

        .navbar-default .navbar-toggle .icon-bar {
            background-color: #fcfeff;
        }

        .navbar-default .navbar-collapse,
        .navbar-default .navbar-form {
            border-color: #fcfeff;
        }

        .navbar-default .navbar-link {
            color: #fcfeff;
        }

        .navbar-default .navbar-link:hover {
            color: #ffffff;
        }

        @media (max-width: 767px) {
            .navbar-default .navbar-nav .open .dropdown-menu>li>a {
                color: #fcfeff;
            }

            .navbar-default .navbar-nav .open .dropdown-menu>li>a:hover,
            .navbar-default .navbar-nav .open .dropdown-menu>li>a:focus {
                color: #ffffff;
            }

            .navbar-default .navbar-nav .open .dropdown-menu>.active>a,
            .navbar-default .navbar-nav .open .dropdown-menu>.active>a:hover,
            .navbar-default .navbar-nav .open .dropdown-menu>.active>a:focus {
                color: #ffffff;
                background-color: #1a6184;
            }
        }

        #page-content {
            background: #ffffff;
            padding: 5px;
            min-height: 80vh;
        }

        #app {
            min-height: 90vh;
            /* background: #fcfeff; */
        }

        .error {
            color: #a94442;
        }

        .is-invalid {
            border-color: #a94442;
        }
        .widget {
            margin: 0 0 25px 0;
            display: block;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
        }
        .widget .widget-heading {
            padding: 7px 15px;
            -webkit-border-radius: 2px 2px 0 0;
            -moz-border-radius: 2px 2px 0 0;
            border-radius: 2px 2px 0 0;
            text-transform: uppercase;
            text-align: center;
            background: #38BDFF;
            color: white;
        }
        .widget .widget-body {
            padding: 10px 15px;
            font-size: 36px;
            font-weight: 300;
            background: #8FDEFF;
        }
        .show-on-print{
            display: none;
        }
        {!!"@media"!!} print {
            a[href]:after {
                display: none;
                visibility: hidden;
            }
            footer{
                display: none;
            }
            .dont-print{
                display: none;
            }
            .show-on-print{
                display: initial;
            }
        }
    </style>
    @yield("css")
</head>

<body>
    @if(in_array(request()->ip(),["103.138.154.181", "127.0.0.1"]))
        <span class="label label-danger" style="position: fixed; right:0px; top:0px; z-index: 999999;">WebCom PC</span>
    @endif
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top nav-first">
            <div class="container-fluid">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ route('admin.home') }}">
                        {{ config('app.name', 'Laravel Multi Auth Guard') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @if(auth("admin")->check())
                            @include('admin.layout.menu')
                        @else
                            &nbsp;
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                        {{-- <li><a href="{{ url('/admin/login') }}">Login</a></li>
                        <li><a href="{{ url('/admin/register') }}">Register</a></li> --}}
                        @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/admin/logout') }}" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/admin/logout') }}" method="POST"
                                        style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        {{-- @include("admin.layout.navbar"); --}}
        
        <div class="loading" style="">Loading&#8230;</div>
        @include("common/alert")
        @yield('content')
    </div>
    @include('common/footer')

    <!-- Scripts -->
    <script src="{{asset_public("/js/app.js")}}"></script>
    <script>
        $(document).ready(function(){
            // $(":input").inputmask();
            $(".loading").fadeOut();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    @yield("js")    
    <script src="{{ asset_public('js/jquery.validate.js') }}"></script>
    <script src="{{ asset_public('js/jquery.blockUI.js') }}"></script>
    <script src="{{ asset_public('js/additional-methods.js') }}"></script>
    <script src="{{ asset_public('js/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset_public('js/zebra_datepicker.js') }}"></script>
    <script src="{{ asset_public('js/jquery.inputmask.bundle.min.js') }}"></script>
    @if(\Route::current()->getName() != "admin.login")
        <script type="text/javascript">
            function idleTimer() {
                var timer = {{((env("SESSION_LIFETIME", 30) * 60) * 1000)}};
                var t;
                //window.onload = resetTimer;
                window.onmousemove = resetTimer; // catches mouse movements
                window.onmousedown = resetTimer; // catches mouse movements
                window.onclick = resetTimer;     // catches mouse clicks
                window.onscroll = resetTimer;    // catches scrolling
                window.onkeypress = resetTimer;  //catches keyboard actions
            
            
                function reload() {
                    alert("No activity within "+(timer/1000)+" seconds; please log in again.");
                    window.location = self.location.href;  //Reloads the current page
                }
            
            function resetTimer() {
                    clearTimeout(t);
                    t = setTimeout(reload, (timer+3000));  // time is in milliseconds (1000 is 1 second)
                }
            }
            idleTimer();
        </script>
    @endif
</body>

</html>