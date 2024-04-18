<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-TileColor" content="#0061da">
    <meta name="theme-color" content="#1643a3">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset ('backend/assets/images/brand/jsc.ico')}}" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset ('backend/assets/images/brand/jsc.ico')}}"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <!-- Title -->
    <title>JSC - Your Hiring Partner</title>

    <!--Bootstrap.min css-->
    <link rel="stylesheet" href="{{ asset ('backend/assets/plugins/bootstrap/css/bootstrap.min.css')}}">

    <!--Font Awesome-->
    <link href="{{ asset ('backend/assets/plugins/fontawesome-free/css/all.css')}}" rel="stylesheet">

    <!-- Dashboard Css -->
    <link href="{{ asset ('backend/assets/css/dashboard.css')}}" rel="stylesheet"/>

    <!-- vector-map -->
    <link href="{{ asset ('backend/assets/plugins/vector-map/jqvmap.min.css')}}" rel="stylesheet">

    <!-- Custom scroll bar css-->
    <link href="{{ asset ('backend/assets/plugins/scroll-bar/jquery.mCustomScrollbar.css')}}" rel="stylesheet"/>

    <!-- Horizonatl-menu Css -->
    <link href="{{ asset ('backend/assets/plugins/horizontal-menu/dropdown-effects/fade-down.css')}}" rel="stylesheet">
    <link href="{{ asset ('backend/assets/plugins/horizontal-menu/webslidemenu.css')}}" rel="stylesheet">

    <!-- morris Charts Plugin -->
    <link href="{{ asset ('backend/./assets/plugins/morris/morris.css')}}" rel="stylesheet"/>

    <!---Font icons-->
    <link href="{{ asset ('backend/assets/plugins/iconfonts/plugin.css')}}" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>


    <!--datatables-->


    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .hideextra { white-space: nowrap; overflow: hidden; text-overflow:ellipsis; }
    </style>
</head>

<body class="app sidebar-mini rtl">
<div id="global-loader">
    <div class="showbox">
    </div>
</div>
<div class="page">
    <div class="page-main">
        <!-- Navbar-->
        <header class="app-header header">

            <!-- Navbar Right Menu-->
            <div class="container">
                <div class="d-flex">
                    <a id="horizontal-navtoggle" class="animated-arrow hor-toggle"><span></span></a>
                    <a class="header-brand" href="index.html">
                        <img alt="logo" class="header-brand-img"
                             src="{{ asset ('backend/assets/images/brand/jsc_logo.png')}}">
                    </a>
                    <div class="d-flex order-lg-2 ml-auto">
                        <div class="d-none d-md-flex">
                            <a href="#" class="nav-link icon full-screen-link" id="fullscreen-button">
                                <i class="fe fe-minimize "></i>
                            </a>
                        </div>


                        <div class="dropdown">
                            <a class="nav-link pr-0 leading-none d-flex" data-toggle="dropdown" href="#">
                                <span class="avatar avatar-md brround"
                                >
                                    <img src="{{ asset ('storage/'.auth()->user()->profile_photo_path)}}">
                                </span>
                                <span></span>
                                <span class="ml-2 d-none d-lg-block">
											<span class="text-dark">{{  strtoupper(auth()->user()->name) }}</span>
										</span>
                                <div class="card-body">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        </div>
                                    @endif

                                    <span class="text-dark">{{ __('You are logged in!') }}</span>

                                </div>

                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!--Horizontal-menu-->
        <div class="horizontal-main hor-menu clearfix">
            <div class="horizontal-mainwrapper container clearfix">
                <nav class="horizontalMenu clearfix">
                    <ul class="horizontalMenu-list" >
                        <li aria-haspopup="true" style="float: right"><a class="sub-icon" href="{{ route('user.logout') }}" >
                                <i class="fe fe-power"></i> Log Out</a></li>
                </nav>
                <!--Menu HTML Code-->
            </div>
        </div>
        <!--Horizontal-menu-->

        <!--app-content open-->
        <div class="content-area">
            <div class="container">


                    <div class="page-header">
                        <h3 class="page-title">Dashboard </h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </div>



                    <div class="row">
                        <div class="col-xl-12 col-lg-6 colmd-12">
                            <div class="card bg-lighter">
                                <div class="card-body dash2">
                                    <form method="post" action="{{ route('store.emp_login') }}">
                                        @csrf
                                        <?php
                                        $attendance = \Illuminate\Support\Facades\DB::table('employee_attendances')
                                            ->where('emp_code', \Illuminate\Support\Facades\Auth::user()->emp_code)
                                            // ->where('attendance_date', \Carbon\Carbon::now()->format('Y-m-d'))
                                            ->get();
                                        if(count($attendance) == 0){
                                        ?>
                                        <center>
                                            <button type="submit" name="clock" value="clock_in" class="btn btn-primary" style="color: orangered;">
                                                Clock In
                                            </button>
                                        </center><?php  } ?>

                                    </form>

                                </div>
                            </div>
                        </div>

                    </div>

                    <script>
                        function getConfirmation() {
                            var retVal = confirm("Do you want to continue ?");
                            if (retVal == true) {
                                return true;
                            } else {
                                return false;
                            }
                        }

                    </script>



            <div>

                <!--footer-->
                <footer class="footer">
                    <div class="container">
                        <div class="row align-items-center flex-row-reverse">
                            <div class="col-md-12 col-sm-12 mt-3 mt-lg-0 text-center">
                                Copyright © 2021 <a href="#">DOC</a>. Designed by <a href="#">Digital Orbis Creators</a>
                                All rights
                                reserved.
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- End Footer-->
            </div>
        </div>
    </div>

    <!-- Back to top -->
    <a href="#top" id="back-to-top" style="display: inline;"><i class="fas fa-angle-up "></i></a>

    <!-- Dashboard Core -->
    <script src="{{ asset('backend/assets/js/vendors/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/selectize.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/jquery.tablesorter.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/circle-progress.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/rating/jquery.rating-stars.js') }}"></script>

    <!--Bootstrap.min js-->
    <script src="{{ asset('backend/assets/plugins/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>


    <!--Horizontalmenu js-->
    <script src="{{ asset('backend/assets/plugins/horizontal-menu/webslidemenu.js') }}"></script>

    <!-- Custom scroll bar Js-->
    <script src="{{ asset('backend/assets/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js') }}"></script>


    <!-- Input Mask Plugin -->
    <script src="{{ asset('backend/assets/plugins/input-mask/jquery.mask.min.js') }}"></script>

    <!-- Progress -->
    <script src="{{ asset('backend/assets/js/vendors/circle-progress.min.js') }}"></script>

    <!-- Chart js -->
    <script src="{{ asset('backend/assets/plugins/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/chart.js/chart.extension.js') }}"></script>

    <!--Jquery.knob js-->
    <script src="{{ asset('backend/assets/plugins/othercharts/jquery.knob.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/othercharts/othercharts.js') }}"></script>

    <!--Jquery.sparkline js-->
    <script src="{{ asset('backend/assets/plugins/othercharts/jquery.sparkline.min.js') }}"></script>

    <!-- peitychart -->
    <script src="{{ asset('backend/assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/peitychart/peitychart.init.js') }}"></script>

    <!--Counters -->
    <script src="{{ asset('backend/assets/plugins/counters/counterup.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/counters/waypoints.min.js') }}"></script>

    <!-- custom js -->
    <script src="{{ asset('backend/assets/js/custom.js') }}"></script>
    <script src="{{ asset('backend/assets/js/index.js') }}"></script>
    <!--Datatable-->

    <!--   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>-->
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript">
        $(".nav li").on("click", function () {
            $(".nav li").removeClass("active");
            $(this).addClass("active");
        });


    </script>


</body>
</html>
