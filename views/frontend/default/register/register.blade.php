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

				{{-- Display Validation Errors --}}
				@include('usermanagement::frontend.default.common.errors')

				{{-- New Task Form --}}
				@if ($two_factor_auth_workflow)
				    {!! Form::open(['action' => '\Lasallecms\Usermanagement\Http\Controllers\Frontendauth\Register2faUserController@post2faRegisterDisplayForm']) !!}
				@else
				    {!! Form::open(['action' => '\Lasallecms\Usermanagement\Http\Controllers\Frontendauth\RegisterUserController@postRegister']) !!}
				@endif

				{{-- Name --}}
				<div style="margin-bottom: 25px; margin-top: 25px;" class="input-group">
					<span class="input-group-addon"><i class="fa fa-btn fa-user"></i></span>
					{!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'name']) !!}
				</div>

				{{-- E-Mail Address --}}
				<div style="margin-bottom: 25px; margin-top: 25px;" class="input-group">
					<span class="input-group-addon"><i class="fa fa-btn fa-envelope"></i></span>
					{!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'email']) !!}
				</div>

				{{-- Password --}}
				<div style="margin-bottom: 25px" class="input-group">
					<span class="input-group-addon"><i class="fa fa-btn fa-lock"></i></span>
					{!! Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'password']) !!}
				</div>

				{{-- Confirm Password --}}
				<div style="margin-bottom: 25px" class="input-group">
					<span class="input-group-addon"><i class="fa fa-btn fa-lock"></i></span>
					{!! Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'confirm password']) !!}
				</div>


                {{-- 2FA Specific Fields --}}
                @if ($two_factor_auth_workflow)

					<br />
					<button class="btn btn-info">
						During registration,<br />we send you a code via text message,<br />that you then enter in the next screen.
					</button>
					<br /><br />

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="fa fa-btn fa-map-o"></i></span>
                        {!! Form::text('phone_country_code', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'country code (Canada and US is "1")']) !!}
                    </div>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="fa fa-btn fa-phone"></i></span>
                        {!! Form::text('phone_number', null,['class' => 'form-control', 'required' => 'required', 'placeholder' => 'cell phone number (no spaces nor hyphens)']) !!}
                    </div>
                @endif


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