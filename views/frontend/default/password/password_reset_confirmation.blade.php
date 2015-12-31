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

						<!-- New Task Form -->
				{!! Form::open(['action' => '\Lasallecms\Usermanagement\Http\Controllers\Frontendauth\ResetsPasswordsController@postReset']) !!}

				<input type="hidden" name="token" value="{{ $token }}">

				<!-- E-Mail Address -->
				<div style="margin-bottom: 25px; margin-top: 25px;" class="input-group">
					<span class="input-group-addon"><i class="fa fa-btn fa-envelope"></i></span>
					{!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'email']) !!}
				</div>

				<!-- Password -->
				<div style="margin-bottom: 25px" class="input-group">
					<span class="input-group-addon"><i class="fa fa-btn fa-lock"></i></span>
					{!! Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'password']) !!}
				</div>

				<!-- Confirm Password -->
				<div style="margin-bottom: 25px" class="input-group">
					<span class="input-group-addon"><i class="fa fa-btn fa-lock"></i></span>
					{!! Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'confirm password']) !!}
				</div>

				<!-- Login Button -->
				<button type="submit" class="btn btn-success">
					<i class="fa fa-btn fa-sign-in"></i>&nbsp;&nbsp;Reset Password
				</button>

				</form>

			</div>

		</div>

	</div>
</div>


</body>
</html>