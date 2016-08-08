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
				Password reset successful!<br />{{{ Config::get('lasallecmsfrontend.site_name') }}}
			</div>

			<div class="panel-body text-center">

				<!-- Display Validation Errors -->
				@include('usermanagement::frontend.default.common.errors')


				<br />

				<button class="btn btn-info">
					{!! $username !!}<br /><br />You successfully reset your password!
				</button>

				<br /><br /><br />

				<!-- To Home Page -->
				<a href="{{ route('home') }}" class="btn btn-success">
					<span class="fa fa-btn fa-home" aria-hidden="true"></span>&nbsp;&nbsp;Home
				</a>

				<br /><br />



			</div>

		</div>

	</div>
</div>


</body>
</html>