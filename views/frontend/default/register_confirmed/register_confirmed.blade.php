<!doctype html>
<html lang="en">

@include('usermanagement::frontend.default.common.header')

		<!-- Custom styles for this template http://getbootstrap.com/examples/navbar-fixed-top/-->
<link media="all" type="text/css" rel="stylesheet" href="{{{ Config::get('app.url') }}}/packages/usermanagement/frontend/{{{ Config::get('lasallecmsfrontend.frontend_template_name') }}}/register_confirmed/register_confirmed.css">
</head>

<body>

<div class="container">

	<div class="col-sm-offset-2 col-sm-8" style="margin-top:200px;">
		<div class="panel panel-default">

			<div class="panel-heading">
				Congratulations {!! $username !!}!
			</div>

			<div class="panel-body text-center">

				<!-- Display Validation Errors -->
				@include('usermanagement::frontend.default.common.errors')


				<br />

				<button class="btn btn-info">
					You successfully registered at {{{ Config::get('lasallecmsfrontend.site_name') }}}
				</button>

                <br /><br /><br />

                <!-- To Home Page -->
                <a href="{{ route('home') }}" class="btn btn-default">
                    <span class="fa fa-btn fa-home" aria-hidden="true"></span>&nbsp;&nbsp;Home
                </a>


				@if (!$isUserLoggedIn)
					<br /><br />

					<!-- To Login Form -->
					<a href="{{ route('auth.login') }}" class="btn btn-success">
						<span class="fa fa-btn fa-sign-in" aria-hidden="true"></span>&nbsp;&nbsp;Login
					</a>
				@endif

				<br /><br />


			</div>

		</div>

	</div>
</div>


</body>
</html>