<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/plugins/images/favicon.png') }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset(path: 'assets/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Menu CSS -->
    <link href="{{ asset('assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">

    <!-- animation CSS -->
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <!-- color CSS -->
    <link href="{{ asset('assets/css/colors/default.css') }}" id="theme" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>


<body>

    <body class="fix-header">
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                    stroke-miterlimit="10" />
            </svg>
        </div>

        <div id="wrapper">
            @include('layouts.navbar')
            @include('layouts.sidebar')

            <div id="page-wrapper">
                @yield('content')

                @include('layouts.footer')
            </div>
        </div>


        <!-- /#wrapper -->
        <!-- jQuery -->
        <script src="{{ asset('assets/plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="{{ asset('assets/bootstrap/dist/js/bootstrap.min.js') }}"></script>

        <!-- Menu Plugin JavaScript -->
        <script src="{{ asset('assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>

        <!-- slimscroll JavaScript -->
        <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>

        <!-- Wave Effects -->
        <script src="{{ asset('assets/js/waves.js') }}"></script>

        <!-- Custom Theme JavaScript -->
        <script src="{{ asset('assets/js/custom.min.js') }}"></script>
        @yield('scripts') 

    </body>

</body>
