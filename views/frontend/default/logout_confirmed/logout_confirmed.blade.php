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
                You are now logged out of<br />{{{ Config::get('lasallecmsfrontend.site_name') }}}
            </div>


            <div class="panel-body text-center">

                <!-- Display Validation Errors -->
                @include('usermanagement::frontend.default.common.errors')

                <!-- To Home Page -->
                <a href="{{ route('home') }}" class="btn btn-success">
                    <span class="fa fa-btn fa-home"></span>&nbsp;&nbsp;Home
                </a>

            </div>


        </div>

    </div>

</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>

</body>
</html>