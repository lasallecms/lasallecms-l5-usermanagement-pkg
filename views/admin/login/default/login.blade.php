<!doctype html>
<html lang="en">
<head>
    <!-- START: meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- title tag -->
    <!-- http://www.netlingo.com/tips/html-code-cheat-sheet.php -->
    <title>Admin Login &verbar; {{{ Config::get('lasallecmsfrontend.site_name') }}}</title>



    <!-- Bootstrap -->
    <!-- from http://getbootstrap.com/getting-started -->

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">


    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    <!-- Custom styles for this template http://getbootstrap.com/examples/navbar-fixed-top/-->
    <link media="all" type="text/css" rel="stylesheet" href="{{{ Config::get('app.url') }}}/packages/usermanagement/admin/login/{{{ Config::get('auth.admin_login_view_folder') }}}/login.css" >
</head>


<body>

<div class="container">

    <div id="loginbox" style="margin-top:200px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">

        <div class="panel panel-info" >

            <div class="panel-heading">
                <div class="panel-title" style="text-align: center;font-weight:bolder;font-size:140%;">Welcome to the {{{ Config::get('lasallecmsfrontend.site_name') }}} Administration</div>
            </div>

            <div style="padding-top:30px" class="panel-body" >

                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>


                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                {!! Form::open(['action' => '\Lasallecms\Usermanagement\Http\Controllers\AdminAuth\AdminLoginController@postLogin']) !!}

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'email']) !!}
                    </div>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        {!! Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'password']) !!}
                    </div>

                    <input id="login-remember" type="hidden" name="remember" value="1">

                    <div style="margin-top:10px;margin-left:25px;" class="form-group">

                        <div class="col-sm-12 controls">
                            <button type="submit" class="btn btn-success">
                                <i class="glyphicon glyphicon-ok"></i>  Log into Admin
                            </button>
                        </div>

                    </div>

                </form>


            </div>

        </div>

    </div>

</div>



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>


</body>
</html>