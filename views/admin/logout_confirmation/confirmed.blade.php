<!doctype html>
<html lang="en">
<head>
    <!-- START: meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- title tag -->
    <!-- http://www.netlingo.com/tips/html-code-cheat-sheet.php -->
    <title>Admin Login &verbar; {{{ Config::get('lasallecms.site_name') }}}</title>



    <!-- Bootstrap -->
    <!-- from http://getbootstrap.com/getting-started -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">


    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    <!-- Custom styles for this template http://getbootstrap.com/examples/navbar-fixed-top/-->
    <link media="all" type="text/css" rel="stylesheet" href="{{{ Config::get('app.url') }}}/{{{ Config::get('lasallecms.public_folder') }}}/packages/usermanagement/admin/logout_confirmation/confirmed.css" >
</head>


<body>


<div class="container">

    <div id="loginbox" style="margin-top:200px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">

        <div class="panel panel-info" >

            <div class="panel-heading">
                <div class="panel-title" style="text-align: center;font-weight:bolder;font-size:140%;">You are now logged out of the {{{ Config::get('lasallecms.site_name') }}} Administration</div>
            </div>

            <div style="padding-top:25px" class="panel-body" >

                <div class="">
                    <a href="{{ route('admin.login') }}" class="btn btn-success btn-small">
                        <span class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;&nbsp;Log back into Admin
                    </a>
                </div>

            </div>

        </div>

    </div>

</div>





</body>

</html>