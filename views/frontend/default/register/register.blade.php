<!doctype html>
<html lang="en">

@include('usermanagement::frontend.default.common.header')

		<!-- Custom styles for this template http://getbootstrap.com/examples/navbar-fixed-top/-->
<link media="all" type="text/css" rel="stylesheet" href="{{{ Config::get('app.url') }}}/packages/usermanagement/frontend/{{{ Config::get('lasallecmsfrontend.frontend_template_name') }}}/register/register.css">
</head>

<body>

<div class="container">

	<div class="col-sm-offset-2 col-sm-8" style="margin-top:200px;">
		<div class="panel panel-default">

			<div class="panel-heading">
				{{{ Config::get('lasallecmsfrontend.site_name') }}} Register
			</div>

			<div class="panel-body text-center">

				<!-- Display Validation Errors -->
				@include('usermanagement::frontend.default.common.errors')

				<!-- New Task Form -->
				{!! Form::open(['action' => '\Lasallecms\Usermanagement\Http\Controllers\Frontendauth\RegisterUserController@postRegister']) !!}

				<!-- Name -->
				<div style="margin-bottom: 25px; margin-top: 25px;" class="input-group">
					<span class="input-group-addon"><i class="fa fa-btn fa-user"></i></span>
					{!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'name']) !!}
				</div>

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
					<i class="fa fa-btn fa-sign-in"></i>&nbsp;&nbsp;Register
				</button>

				</form>

			</div>

		</div>

	</div>
</div>


</body>
</html>