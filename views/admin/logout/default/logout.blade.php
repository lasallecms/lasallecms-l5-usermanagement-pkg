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
    <link media="all" type="text/css" rel="stylesheet" href="{{{ Config::get('app.url') }}}/packages/usermanagement/admin/logout/{{{ Config::get('lasallecmsusermanagement.admin_login_view_folder') }}}/logout.css" >
</head>


<body>


<div class="container">

    <div id="loginbox" style="margin-top:200px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">

        <div class="panel panel-info" >

            <div class="panel-heading">
                <div class="panel-title" style="text-align: center;font-weight:bolder;font-size:140%;">Confirm: Logout of {{{ Config::get('lasallecmsfrontend.site_name') }}} Administration?</div>
            </div>

            <div style="padding-top:10px" class="panel-body" >

                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>


                {!! Form::open(['action' => '\Lasallecms\Usermanagement\Http\Controllers\AdminAuth\AdminLogoutController@destroy']) !!}


                    <div style="margin-top:0px" class="form-group">

                        <div class="col-sm-12 controls">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="glyphicon glyphicon-okA"></i>  <strong>Yes</strong>, I want to logout!
                            </button>
                        </div>

                    </div>

                </form>


                <br /><br /><br />

                <div style="margin-top:0px;margin-left:50px;" class="form-group">

                    <div class="col-sm-12 controls">
                        <button onclick="goBack()" class="btn btn-success btn-lg">
                            <i class="glyphicon glyphicon-removeA"></i> Oops! <strong>No</strong>, I want to go back!
                        </button>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
    function goBack() {
        window.history.back()
    }
</script>



</body>
</html>