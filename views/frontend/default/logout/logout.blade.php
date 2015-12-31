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
                @if (isset(Auth::user()->name))
                    Log {!! Auth::user()->name !!} out of<br />
                @else
                    Logout of
                @endif
                    {{{ Config::get('lasallecmsfrontend.site_name') }}}
            </div>


            <div class="panel-body text-center">

                <!-- Display Validation Errors -->
                @include('usermanagement::frontend.default.common.errors')

                <!-- Yes, logout -->
                {!! Form::open(['url' => 'logout']) !!}

                    <div class="form-group">
                        <button type="submit" class="btn btn-danger btn-lg">
                            <i class="fa fa-btn fa-check"></i>&nbsp;&nbsp;<strong>Yes</strong>, I want to logout!
                        </button>
                    </div>

                </form>

                <!-- No, do not logout -->
                <button onclick="goBack()" class="btn btn-success btn-lg">
                    <i class="fa fa-btn fa-remove"></i>&nbsp;&nbsp;<strong>No</strong>, I want to go back!
                </button>

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