<!doctype html>
<html lang="en">

@include('usermanagement::frontend.default.common.header')

    <!-- Custom styles for this template http://getbootstrap.com/examples/navbar-fixed-top/-->
    <link media="all" type="text/css" rel="stylesheet" href="{{{ Config::get('app.url') }}}/packages/usermanagement/frontend/{{{ Config::get('lasallecmsfrontend.frontend_template_name') }}}/login/login.css">
</head>

<body>

<div class="container">

	<div class="col-sm-offset-2 col-sm-8" style="margin-top:200px;">
		<div class="panel panel-default">

			<div class="panel-heading">
				{{{ Config::get('lasallecmsfrontend.site_name') }}}
			</div>

			<div class="panel-body text-center">

				<!-- Display Validation Errors -->
				<br />
				@include('usermanagement::frontend.default.common.errors')


				<br /><br />
				<button class="btn btn-warning btn-lg">Enter the code that was sent to your phone</button>
				<br /><br />


				<!-- New Task Form -->
				{!! Form::open(['action' => '\Lasallecms\Usermanagement\Http\Controllers\Frontendauth\FrontendAuthController@post2FALogin']) !!}

					<!-- 2FA Code -->
					<div style="margin-bottom: 25px; margin-top: 25px;" class="input-group">
							<span class="input-group-addon"><i class="fa fa-btn fa-phone"></i></span>
							{!! Form::text('2facode', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'your two factor authorization code']) !!}
					</div>


					<!-- Login Button -->
					<button type="submit" class="btn btn-success">
					    <i class="fa fa-btn fa-sign-in"></i>&nbsp;&nbsp;Login
					</button>

				</form>

			</div>

		</div>

	</div>
</div>


</body>
</html>