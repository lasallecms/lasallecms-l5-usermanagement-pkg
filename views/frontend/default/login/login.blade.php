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
				{{{ Config::get('lasallecmsfrontend.site_name') }}} Login
			</div>

			<div class="panel-body text-center">

				<!-- Display Validation Errors -->
				@include('usermanagement::frontend.default.common.errors')

				<!-- New Task Form -->
				{!! Form::open(['action' => '\Lasallecms\Usermanagement\Http\Controllers\Frontendauth\FrontendAuthController@postLogin']) !!}

					<!-- E-Mail Address -->
					<div style="margin-bottom: 25px; margin-top: 25px;" class="input-group">
							<span class="input-group-addon"><i class="fa fa-btn fa-envelope" aria-hidden="true"></i></span>
							{!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'email']) !!}
					</div>

					<!-- Password -->
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="fa fa-btn fa-lock" aria-hidden="true"></i></span>
						{!! Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'password']) !!}
					</div>


				    @if (config('lasallecmsusermanagement.auth_users_log_into_front_end_require_terms_of_service'))
					<div style="margin-bottom: 25px" class="input-group">
					    {!! Form::checkbox('terms-of-service', null) !!}&nbsp;&nbsp;I have read the <a href="{{{ config('lasallecmsusermanagement.auth_users_log_into_front_end_require_terms_of_service_url') }}}">Terms of Service</a>
					</div>
				    @endif

					<!-- Login Button -->
					<button type="submit" class="btn btn-success">
					    <i class="fa fa-btn fa-sign-in" aria-hidden="true"></i>&nbsp;&nbsp;Login
					</button>


                    <br /><br />
                    <!-- Reset Request Link -->
                    <a class="btn btn-warning" href="password/email" role="button">
                        <i class="fa fa-btn fa-envelope-o" aria-hidden="true"></i>&nbsp;&nbsp;Forgot Your Password?
                    </a>

				</form>

			</div>

		</div>

	</div>
</div>


</body>
</html>