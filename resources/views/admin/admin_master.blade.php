<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-TileColor" content="#0061da">
    <meta name="theme-color" content="#1643a3">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset ('backend/assets/images/brand/jsc.ico')}}" type="image/x-icon" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset ('backend/assets/images/brand/jsc.ico')}}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Title -->
    <title>JSC - Your Hiring Partner</title>

    <!--Bootstrap.min css-->
    <link rel="stylesheet" href="{{ asset ('backend/assets/plugins/bootstrap/css/bootstrap.min.css')}}">

    <!--Font Awesome-->
    <link href="{{ asset ('backend/assets/plugins/fontawesome-free/css/all.css')}}" rel="stylesheet">
    <!--Select 2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Dashboard Css -->
    <link href="{{ asset ('backend/assets/css/dashboard.css')}}" rel="stylesheet" />

    <!-- vector-map -->
    <link href="{{ asset ('backend/assets/plugins/vector-map/jqvmap.min.css')}}" rel="stylesheet">

    <!-- Custom scroll bar css-->
    <link href="{{ asset ('backend/assets/plugins/scroll-bar/jquery.mCustomScrollbar.css')}}" rel="stylesheet" />

    <!-- Horizonatl-menu Css -->
    <link href="{{ asset ('backend/assets/plugins/horizontal-menu/dropdown-effects/fade-down.css')}}" rel="stylesheet">
    <link href="{{ asset ('backend/assets/plugins/horizontal-menu/webslidemenu.css')}}" rel="stylesheet">

    <!-- morris Charts Plugin -->
    <link href="{{ asset ('backend/./assets/plugins/morris/morris.css')}}" rel="stylesheet" />

    <!---Font icons-->
    <link href="{{ asset ('backend/assets/plugins/iconfonts/plugin.css')}}" rel="stylesheet" />

    <!--Select 2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
        .hideextra {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        a:hover {
            color: #fd4400 !important;
        }



        .notification-container {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>

<body class="app sidebar-mini rtl" onload=display_ct();>
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
                        <style>
                            /*.animated-arrow span, .animated-arrow span::before, .animated-arrow span::after{
                                background:orange !important;
                            }*/
                            .animated-arrow.hor-toggle span,
                            .animated-arrow.hor-toggle span::before,
                            .animated-arrow.hor-toggle span::after {
                                background: black !important;
                            }
                        </style>
                        <a id="horizontal-navtoggle" class="animated-arrow hor-toggle"><span></span></a>
                        <a class="header-brand" href="">
                            <img alt="logo" class="header-brand-img" src="{{ asset ('backend/assets/images/brand/jsc_logo.png')}}">
                        </a>
                        <div class="d-flex order-lg-2 ml-auto">
                            <!-- to clear the configuration -->
                            <div class="d-none d-md-flex">
                                <a href="{{ route('clear.cache') }}" class="nav-link icon full-screen-link" title="Clear Cache">
                                    <i class="fe fe-refresh-cw"></i> <!-- Refresh/Reload Icon -->
                                </a>
                            </div>
                            <!-- for full screen -->
                            <!-- <div class="d-none d-md-flex">
                                <a href="#" class="nav-link icon full-screen-link" id="fullscreen-button">
                                    <i class="fe fe-minimize "></i>
                                </a>
                            </div> -->

                            <div class="dropdown d-none d-md-flex">
                                <!-- $notificationCount = auth()->user()->unreadNotifications()->count(); -->
                                <a class="nav-link icon" data-toggle="dropdown">
                                    <i class="fa fa-bell"></i>
                                    <span class=" nav-unread badge badge-danger  badge-pill">{{ auth()->user()->notifications()->count() }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item text-center">Notifications</span>
                                        <span class="notification-tag tag tag-default tag-danger float-xs-right m-0"> Recent</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <div class="notification-container">
                                        @if(auth()->user()->notifications)
                                        @foreach(auth()->user()->notifications as $notification)
                                        <a class="dropdown-item d-flex pb-3" href="">
                                        <div>
                                            <div class="small text-muted">
                                                 {{ $notification->data['msg']}} BY {{ $notification->data['name']}}
                                            </div>
                                        </div>
                                        </a>
                                        <!-- <a class="dropdown-item d-flex pb-3" href="">
                                            <div>
                                                <div class="small text-muted">
                                                    Notification 2
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex pb-3" href="">
                                            <div>
                                                <div class="small text-muted">
                                                    Notification 3
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex pb-3" href="">
                                            <div>
                                                <div class="small text-muted">
                                                    Notification 4
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex pb-3" href="">
                                            <div>
                                                <div class="small text-muted">
                                                    Notification 5
                                                </div>
                                            </div>
                                        </a> -->
                                        @endforeach
                                        @else
                                        <p style="text-align: center;padding: 12px 0px 0px 0px;">No record found</p>
                                        @endif

                                        </li>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="nav-link pr-0 leading-none d-flex" data-toggle="dropdown" href="#">
                                    <span class=" avatar-md brround">
                                        {{-- <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}"> --}}
                                        <img src="{{ asset('storage/'.Auth::user()->profile_photo_path) }}" width="50" alt="" style="border-radius: 100px;">
                                    </span>
                                    <span></span>
                                    <span class="ml-2 d-none d-lg-block">
                                        <span class="text-dark">{{ strtoupper(auth()->user()->name) }}</span>
                                    </span>
                                    <div class="card-body">
                                        @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        </div>
                                        @endif
                                        <span class="text-dark"><span id='ct7'></span></span>
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
                        <ul class="horizontalMenu-list">
                            <li aria-haspopup="true">
                                <a href="{{ route('dashboard') }}" class="sub-icon {{ (Route::is('dashboard') ? 'active' : '') }}"><!--<i
                                    class="si si-screen-desktop"></i>--> Dashboard </a>
                            </li>
                            @if(auth()->user()->can('Add Employees') || auth()->user()->can('Edit Employees') || auth()->user()->can('View Employees')
                            || auth()->user()->can('Add Attendance') || auth()->user()->can('Edit Attendance') || auth()->user()->can('Own Attendance') || auth()->user()->can('View All Attendance')
                            || auth()->user()->can('Manage Team') || auth()->user()->can('View Own Leave') || auth()->user()->can('View All Leave'))
                            <li aria-haspopup="true">
                                <a href="{{ route('all.employee') }}" class="sub-icon {{ (Route::is('all.employee') || Route::is('inactive.employee') || Route::is('ipj.employee') || Route::is('create.employee') || Route::is('create.emp_official') || Route::is('create.emp_bank') || Route::is('show.employee') || Route::is('edit.employee_personal') || Route::is('edit.emp_bank') || Route::is('edit.emp_official') || Route::is('edit.emp_pip') || Route::is('edit.emp_image') || Route::is('edit.emp_salary') || Route::is('employee.manager_employee') || Route::is('employee.manager_employee_filter') || Route::is('all.attendance') || Route::is('all.team') || Route::is('all.emp_leave') ? 'active' : '') }}"><!--<i
                                        class="si si-chart"></i>--> Employees <i class="fa horizontal-icon"></i></a>
                                <ul class="sub-menu">
                                    @if(auth()->user()->can('Add Employees'))
                                    <li aria-haspopup="true"><a href="{{ route('create.employee') }}"> Add Employees</a></li>
                                    @endif
                                    @if(auth()->user()->can('Edit Employees') || auth()->user()->can('View Employees'))
                                    <li aria-haspopup="true"><a href="{{ route('all.employee') }}"> View Employees</a></li>
                                    @endif
                                    @if(auth()->user()->can('Add Attendance') || auth()->user()->can('Edit Attendance')
                                    || auth()->user()->can('View Own Attendance') || auth()->user()->can('View All Attendance'))
                                    <li aria-haspopup="true"><a href="{{ route('all.attendance') }}">Attendance</a></li>
                                    @endif
                                    @if(Auth::user()->id==1 || auth()->user()->can('Manage Team') )
                                    <li aria-haspopup="true"><a href="{{ route('all.team') }}">Team management</a></li>
                                    @endif
                                    @if(auth()->user()->can('View Own Leave') || auth()->user()->can('View All Leave'))
                                    <li aria-haspopup="true"><a href="{{ route('all.emp_leave') }}"> Leave Management</a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if(auth()->user()->can('Add Client') || auth()->user()->can('Edit Client') || auth()->user()->can('View Client'))
                            <li aria-haspopup="true">
                                <a href="{{ route('all.client') }}" class="sub-icon {{ (Route::is('all.client') ? 'active' : '') }}"><!--<i class="si si-rocket"></i>-->Clients <i class="fa  horizontal-icon"></i></a>
                            </li>
                            @endif
                            @if(auth()->user()->can('View Requirement'))
                            <li aria-haspopup="true">
                                <a href="{{ route('all.requirement') }}" class="sub-icon {{ (Route::is('all.requirement') ? 'active' : '') }}"><!--<i class="si si-grid"></i>--> Requirements <i class="fa  horizontal-icon"></i></a>
                            </li>
                            @endif
                            @if(auth()->user()->can('View Client Allocation') || auth()->user()->can('View Requirement Allocation'))
                            <li aria-haspopup="true">
                                <a href="{{ route('all.allocation') }}" class="sub-icon {{ (Route::is('all.allocation') ? 'active' : '') }}"><!--<i class="fas fa-sitemap"></i> -->Allocation </a>
                            </li>
                            @endif
                            @if(auth()->user()->can('Add Candidate') || auth()->user()->can('Edit Candidate') || auth()->user()->can('View Candidate') || auth()->user()->can('Track Recruiter') || auth()->user()->can('Track Client'))
                            <li aria-haspopup="true">
                                <a href="{{ route('all.candidate') }}" class="sub-icon {{ (Route::is('all.candidate') ? 'active' : '') }}"><!--<i class="si si-docs"></i>--> Candidates </a>
                            </li>
                            @endif
                            @if(auth()->user()->can('Add Invoice') || auth()->user()->can('Edit Invoice'))
                            <li aria-haspopup="true">
                                <a href="{{ route('all.invoice') }}" class="sub-icon {{ (Route::is('all.invoice') ? 'active' : '') }}"><!--<i class="si si-docs"></i>--> Invoices </a>
                            </li>
                            @endif
                            @if (Auth::user()->id == 1 || auth()->user()->can('Manage payroll'))
                            <li aria-haspopup="true">
                                <a href="" class="sub-icon {{ (Route::is('payroll.slab.list') || Route::is('payroll.slab.create') || Route::is('payroll.slab.edit') || Route::is('payroll.list') || Route::is('payroll.create') || Route::is('payroll.edit') ? 'active' : '') }} "><!--<i class="si si-docs"></i>--> Payroll </a>
                                <ul class="sub-menu">
                                    <li aria-haspopup="true" class="sub-menu-sub">
                                        <a href="#">Payroll Slab</a>
                                        <ul class="sub-menu">
                                            <li aria-haspopup="true">
                                                <a href="{{ route('payroll.slab.list') }}">List Payroll Slab</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('payroll.slab.create') }}">Add Payroll Slab</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li aria-haspopup="true" class="sub-menu-sub">
                                        <a href="#">Employee Payroll</a>
                                        <ul class="sub-menu">
                                            <li aria-haspopup="true">
                                                <a href="{{ route('payroll.list') }}">List Employee Payroll</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('payroll.create') }}">Add Employee Payroll</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            @endif
                            @if (auth()->user()->can('Manage Policies') || auth()->user()->can('Manage Performance') || auth()->user()->can('Performance Assessment') || auth()->user()->can('Performance Review'))

                            <li aria-haspopup="true">
                                <a href="" class="sub-icon {{ (Route::is('all.policy') || Route::is('all.performanceassessment') || Route::is('all.performancereview') || Route::is('employee.performanceassessment') || Route::is('employee.review') ? 'active' : '') }}">Policies</a>
                                <ul class="sub-menu">
                                    @if (auth()->user()->can('Manage Policies'))
                                    <li aria-haspopup="true">
                                        <a href="{{ route('all.policy') }}"> View Policies</a>
                                    </li>
                                    @endif
                                    @if (auth()->user()->can('Manage Performance') || auth()->user()->can('Performance Assessment') || auth()->user()->can('Performance Review'))
                                    <li aria-haspopup="true" class="sub-menu-sub">
                                        <a href="#">Forms</a>
                                        <ul class="sub-menu">
                                            @if (Auth::user()->id == 1 || auth()->user()->can('Manage Performance'))
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.performanceassessment') }}">Performance Assessment</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.performancereview') }}">Performance Review Form</a>
                                            </li>
                                            @else
                                            @if (auth()->user()->can('Performance Assessment'))
                                            <li aria-haspopup="true">
                                                <a href="{{ route('employee.performanceassessment') }}">Performance Assessment Form</a>
                                            </li>
                                            @endif
                                            @if (auth()->user()->can('Performance Review'))
                                            <li aria-haspopup="true">
                                                <a href="{{ route('employee.review') }}">Performance Review Form</a>
                                            </li>
                                            @endif
                                            @endif
                                        </ul>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if (auth()->user()->can('Manage user privilege'))
                            <li aria-haspopup="true">
                                <a href="#" class="sub-icon {{ (Route::is('all.role') || Route::is('all.permission') || Route::is('all.user_role') ? 'active' : '') }}"><i class="si si-user"></i> User settings</a>
                                <ul class="sub-menu">
                                    <li aria-haspopup="true"><a href="{{ route('all.role') }}">Roles</a></li>
                                    <li aria-haspopup="true"><a href="{{ route('all.permission') }}">Permissions</a></li>
                                    <li aria-haspopup="true"><a href="{{route('all.user_role')}}"> User Privileges</a></li>
                                    {{-- <li aria-haspopup="true"><a href=""> Sms / Email </a></li> --}}
                                </ul>
                            </li>
                            @endif
                            @if(auth()->user()->can('Manage Settings'))
                            <li aria-haspopup="true">
                                <a href="#" class="sub-icon {{ (Route::is('all.emp_mode') || Route::is('all.designation') || Route::is('all.department') || Route::is('all.leavetype') || Route::is('all.holiday') || Route::is('all.loginImage') ? 'active' : '') }}"><i class="si si-settings"></i> Settings </a>
                                <ul class="sub-menu">
                                    <li aria-haspopup="true"><a href="{{ route('all.loginImage') }}"> Site </a></li>
                                    <li aria-haspopup="true" class="sub-menu-sub">
                                        <a href="#">Employee</a>
                                        <ul class="sub-menu">
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.emp_mode') }}">Employement
                                                    Mode</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.designation') }}">Designations</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.department') }}">Department</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.leavetype') }}">Leave Type</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.holiday') }}">Holidays</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li aria-haspopup="true" class="sub-menu-sub">
                                        <a href="#">Client</a>
                                        <ul class="sub-menu">
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.industrytype') }}">Industry Type</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.servicetype') }}">Service Type</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.gst') }}">GST Code</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li aria-haspopup="true" class="sub-menu-sub">
                                        <a href="#">Candidate</a>
                                        <ul class="sub-menu">
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.qualificationlevels') }}">Qualification
                                                    Level</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.qualification') }}">Qualification</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li aria-haspopup="true" class="sub-menu-sub">
                                        <a href="#">General</a>
                                        <ul class="sub-menu">
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.company') }}">Company Details</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.country') }}"> Countries</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.state') }}">States</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.city') }}"> Cities</a>
                                            </li>
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.skill') }}">Skills</a>
                                            </li>
                                            <!--<li aria-haspopup="true"><a href="">Blood Group</a></li>-->
                                        </ul>
                                    </li>
                                    <li aria-haspopup="true" class="sub-menu-sub">
                                        <a href="#">Others</a>
                                        <ul class="sub-menu">
                                            <li aria-haspopup="true">
                                                <a href="{{ route('all.expendituretype') }}">Expenditure Type</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            @endif
                            <li aria-haspopup="true">
                                <a href="#" class="sub-icon"><i class="si si-home"></i> Profile </a>
                                <ul class="sub-menu">
                                    <li aria-haspopup="true">
                                        <a class="dropdown-item" href="{{route('profile')}}"><i class="dropdown-icon fe fe-user"></i>My Profile</a>
                                    </li>
                                </ul>
                            </li>
                            <li aria-haspopup="true" style="float: right">
                                <a class="sub-icon" href="{{ route('user.logout') }}" style="text-align: right"><i class="fe fe-power"></i> Log Out</a>
                            </li>
                        </ul>
                    </nav>
                    <!--Menu HTML Code-->
                </div>
            </div>

            <!--Horizontal-menu-->

            <!--app-content open-->
            <div class="content-area">
                <div class="container">
                    @yield('admin')
                </div>
                <div>
                    <!--footer-->
                    <footer class="footer">
                        <div class="container">
                            <div class="row align-items-center flex-row-reverse">
                                <div class="col-md-12 col-sm-12 mt-3 mt-lg-0 text-center">
                                    Copyright © 2024 <a href="#">DOC</a>. Designed by <a href="#">Digital Orbis Creators</a>
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
    </div>
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
    <!-- -->
    <script type="text/javascript">
        $(".nav li").on("click", function() {
            $(".nav li").removeClass("active");
            $(this).addClass("active");
        });
    </script>
    <script type="text/javascript">
        function display_ct7() {
            var x = new Date()
            var ampm = x.getHours() >= 12 ? ' PM' : ' AM';
            hours = x.getHours() % 12;
            hours = hours ? hours : 12;
            hours = hours.toString().length == 1 ? 0 + hours.toString() : hours;

            var minutes = x.getMinutes().toString()
            minutes = minutes.length == 1 ? 0 + minutes : minutes;

            var seconds = x.getSeconds().toString()
            seconds = seconds.length == 1 ? 0 + seconds : seconds;

            var month = (x.getMonth() + 1).toString();
            month = month.length == 1 ? 0 + month : month;

            var dt = x.getDate().toString();
            dt = dt.length == 1 ? 0 + dt : dt;

            var x1 = month + "/" + dt + "/" + x.getFullYear();
            x1 = x1 + " - " + hours + ":" + minutes + ":" + seconds + " " + ampm;
            document.getElementById('ct7').innerHTML = x1;
            display_c7();
        }

        function display_c7() {
            var refresh = 1000; // Refresh rate in milli seconds
            mytime = setTimeout('display_ct7()', refresh)
        }
        display_c7()
    </script>
</body>

</html>