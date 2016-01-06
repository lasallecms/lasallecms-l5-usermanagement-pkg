<!doctype html>
<html lang="en">

@include('usermanagement::frontend.default.common.header')

		<!-- Custom styles for this template http://getbootstrap.com/examples/navbar-fixed-top/-->
<link media="all" type="text/css" rel="stylesheet" href="{{{ Config::get('app.url') }}}/packages/usermanagement/frontend/{{{ Config::get('lasallecmsfrontend.frontend_template_name') }}}/password/password.css">
</head>

<body>

<div class="container">

	<div class="col-sm-offset-2 col-sm-8" style="margin-top:200px;">
		<div class="panel panel-default">

			<div class="panel-heading">
				Password Reset Email Sent<br />{{{ Config::get('lasallecmsfrontend.site_name') }}}
			</div>

			<div class="panel-body text-center">

				<br />

				<button class="btn btn-info">
					<h4>Congratulations! Your password reset email was sent to<br />{!! $email !!}</h4>
				</button>

				<br /><br /><br />

				<!-- To Home Page -->
				<a href="{{ route('home') }}" class="btn btn-success">
					<span class="fa fa-btn fa-home"></span>&nbsp;&nbsp;Home
				</a>

				<br /><br />

			</div>

		</div>

	</div>
</div>


</body>
</html>