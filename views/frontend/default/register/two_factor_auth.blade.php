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
				{{{ Config::get('lasallecmsfrontend.site_name') }}} Registration
			</div>

			<div class="panel-body text-center">

				<!-- Display Validation Errors -->
				<br />
				@include('usermanagement::frontend.default.common.errors')


				<br />
				<button class="btn btn-warning btn-lg">Enter the code that was sent to your phone</button>
				<br /><br />


				<!-- New Task Form -->
				{!! Form::open(['action' => '\Lasallecms\Usermanagement\Http\Controllers\Frontendauth\Register2faUserController@post2faRegister']) !!}

					<!-- 2FA Code -->
					<div style="margin-bottom: 25px; margin-top: 25px;" class="input-group">
							<span class="input-group-addon"><i class="fa fa-btn fa-phone" aria-hidden="true"></i></span>
							{!! Form::text('2facode', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'your two factor authorization code']) !!}
					</div>

					{!! Form::hidden('name', $name) !!}
					{!! Form::hidden('email', $email) !!}
					{!! Form::hidden('password', $password) !!}
					{!! Form::hidden('password_confirmation', $password_confirmation) !!}
					{!! Form::hidden('phone_country_code', $phone_country_code) !!}
					{!! Form::hidden('phone_number', $phone_number) !!}
					{!! Form::hidden('formStartDateTime', $formStartDateTime) !!}

					<!-- Register Button -->
					<button type="submit" class="btn btn-success">
					    <i class="fa fa-btn fa-sign-in" aria-hidden="true"></i>&nbsp;&nbsp;Register
					</button>

				</form>

			</div>

		</div>

	</div>
</div>


</body>
</html>