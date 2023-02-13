<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>VKNRL School Of Nursing</title>

	<link rel="stylesheet" href="{{asset_public("website/css/bootstrap.min.css")}}">
	<link rel="stylesheet" href="{{asset_public("website/css/animate.css")}}">
	<link rel="stylesheet" href="{{asset_public("website/css/themify-icons.css")}}">
	<link rel="stylesheet" href="{{asset_public("website/css/flaticon.css")}}">
	<link rel="stylesheet" href="{{asset_public("website/css/style.css")}}">
	<link rel="stylesheet" href="{{asset_public("fonts.css")}}">
	<link rel="icon" href="{{asset("favicon.ico")}}" type="image/x-icon" />

</head>


<body>

	<header class="header_area">
		<div class="sub_header">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-md-4 col-xl-4">
						<div id="logo">
							<a href="{{url("/")}}"><img src="{{asset_public("website/img/logo.jpg")}}" alt="" title="logo" /></a>
						</div>
					</div>
					<div class="col-md-8 col-xl-8">
						<div class="sub_header_social_icon float-right">
							<!-- <a href="#"><i class="flaticon-phone"></i>+91-03776-265448</a>
							<a href="#" style="text-transform: lowercase;">Email: vknrlson@vkendra.org</a> -->
							<a href="#"><i class="flaticon-phone"></i>+91-94356 52161</a>
							<a href="#" style="text-transform: lowercase;">Email: vknrlns22@gmail.com</a>
							<a href="{{route("student.login")}}" class="register_icon"><i class="ti-arrow-right"></i>Login</a>
							<a href="{{route("student.register")}}" class="register_icon"><i class="ti-arrow-right"></i>Register</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>

    @yield("content")




	<footer class="copyright_part">
		<div class="container">
			<div class="row align-items-center">
				<p class="footer-text m-0 col-lg-8 col-md-12">
					Copyright &copy;
					<script
						type="dc1d43e4638e00e8b931e7d2-text/javascript">document.write(new Date().getFullYear()); </script>
					{{env("APP_NAME")}} . All rights reserved </p>
				<div class="col-lg-4 col-md-12 text-center text-lg-right footer-social">
					<a href="https://www.facebook.com/pages/category/Education/VKNRL-School-of-Nursing-2221741521377886/" target="_blank"><i class="ti-facebook"></i></a>
					{{-- <a href="#"> <i class="ti-twitter"></i> </a>
					<a href="#"><i class="ti-instagram"></i></a>
					<a href="#"><i class="ti-skype"></i></a> --}}
				</div>
			</div>
		</div>
	</footer>



	<script src="{{asset_public("website/js/jquery.min.js")}}" type="text/javascript"></script>
	<script src="{{asset_public("website/js/popper.min.js")}}" type="text/javascript"></script>
	<script src="{{asset_public("website/js/bootstrap.min.js")}}" type="text/javascript"></script>
	<script src="{{asset_public("website/js/custom.js")}}" type="dc1d43e4638e00e8b931e7d2-text/javascript"></script>
	@yield("js")

</html>