<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login To Your Panel</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{url('/public_html/admin')}}/css/bootstrap.min.css" rel='stylesheet' type='text/css'/>

    <!-- Custom CSS -->
    <link href="{{url('/public_html/admin')}}/css/admin_login.css" rel='stylesheet' type='text/css'/>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{url('/public_html/admin')}}/js/bootstrap.min.js"></script>

</head>
<body>

    <div class="container">
        <div class="row" style="text-align: center;margin-top: 15px;">
            {!! \Session::get("msg") !!}
        </div>
    </div>

    @yield('subview')

</body>
</html>
