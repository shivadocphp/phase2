@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Dashboard </h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Admin Dashboard</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-xl-3 col-lg-6 colmd-12">
            <div class="card bg-lighter">
                <div class="card-body dash2">
                    <i class="fe fe-git-branch text-orange"></i>
                    <span class="count-numbers counter text-primary">100</span>
                    <span class="count-name">Employees</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 colmd-12">
            <div class="card bg-lighter">
                <div class="card-body dash2">
                    <i class="fe fe-send text-orange"></i>
                    <span class="count-numbers counter text-primary">300</span>
                    <span class="count-name">Clients</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 colmd-12">
            <div class="card bg-lighter">
                <div class="card-body dash2">
                    <i class="fe fe-database text-orange"></i>
                    <span class="count-numbers counter text-primary">5990</span>
                    <span class="count-name">Requirements</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 colmd-12">
            <div class="card bg-lighter ">
                <div class="card-body dash2">
                    <i class="fe fe-users text-orange"></i>
                    <span class="count-numbers counter text-primary">68750</span>
                    <span class="count-name">Candidates</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-lg-6 colmd-12">
            <div class="card bg-lighter">
                <div class="card-body dash2">
                    <form method="post" action="{{ route('store.emp_login') }}">
                        @csrf
                        <?php
                        $attendance = \Illuminate\Support\Facades\DB::table('employee_attendances')
                            ->where('emp_code', \Illuminate\Support\Facades\Auth::user()->id)
                            ->where('attendance_date', \Carbon\Carbon::now()->format('Y-m-d'))->get();
                        if(count($attendance) == 0){
                        ?>
                        <center>
                            <button type="submit" name="clock" value="clock_in" class="btn btn-primary" style="color: orangered;">
                                Clock In
                            </button>
                        </center><?php  } else {?>
                        <center>
                            @foreach($attendance as $attend)
                                <?php if($attend->logout_time==null){?>
                                <input type="hidden" name="at_id" value="{{ $attend->id }}">
                                <input type="hidden" name="login" value="{{ $attend->login_time }}">
                                Clock Started at {{ $attend->login_time }}


                                <button type="submit" name="clock"  value="clock_out" class="btn btn-primary"
                                        style="color: orangered;">
                                    Clock Out
                                </button>
                                <?php } else{?>
                                    Clock Started at {{ $attend->login_time }}<br>
                                    Clock Ended at {{ $attend->logout_time }}
                                <a href="" class="btn btn-primary"
                                    style="color: orangered;">View Attendance</a>
                                        <?php } ?>
                            @endforeach
                            <?php }?>
                        </center>
                    </form>

                </div>
            </div>
        </div>
        <?php if(count($attendance) != 0){?>
        <div class="col-xl-3 col-lg-6 colmd-12">
            <div class="card bg-lighter">
                <div class="card-body dash2">
                    <form method="post" action="{{ route('update.update_emp_login') }}">
                        {{method_field('patch')}}
                        @csrf

                        <div class="row">
                            <div class="col-md-12" style="align-content: center">
                                <?php foreach( $attendance as $at){?>
                                    <input type="hidden" name="at_id" value="{{ $at->id }}">
                            <?php  if($at->morning_break_in == null){?>
                                <button type="submit" name="break" value="morning_break_in"
                                        class="btn btn-primary delete"
                                        onclick=" return getConfirmation()" style="color: orangered;"> Morning Break In
                                </button>

                                <?php } else if ($at->morning_break_out == null) { ?>
                                    Morning Break Started at {{ $at->morning_break_in }}
                                    <button type="submit" name="break" value="morning_break_out"
                                            class="btn btn-primary delete"
                                            onclick=" return getConfirmation()" style="color: orangered;"> Morning Break Out
                                    </button>

                                <?php } else if ($at->lunch_break_in == null) { ?>
                                    <button type="submit" name="break" value="lunch_break_in"
                                            class="btn btn-primary delete"
                                            onclick=" return getConfirmation()" style="color: orangered;"> Lunch Break In
                                    </button>

                                    <?php } else if ($at->lunch_break_out == null) { ?>
                                    Lunch Break Started at {{ $at->lunch_break_in }}
                                    <button type="submit" name="break" value="lunch_break_out"
                                            class="btn btn-primary delete"
                                            onclick=" return getConfirmation()" style="color: orangered;"> Lunch Break Out
                                    </button>

                                    <?php } else if ($at->evening_break_in == null) { ?>
                                    <button type="submit" name="break" value="evening_break_in"
                                            class="btn btn-primary delete"
                                            onclick=" return getConfirmation()" style="color: orangered;"> Evening Break In
                                    </button>

                                    <?php } else if ($at->evening_break_out == null) { ?>
                                    Evening Break Started at {{ $at->evening_break_in }}
                                    <button type="submit" name="break" value="evening_break_out"
                                            class="btn btn-primary delete"
                                            onclick=" return getConfirmation()" style="color: orangered;"> Evening Break Out
                                    </button>

                                    <?php } else{?>
                                    Todays total break time is {{ $at->total_break_hours }}
<?php }?>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } } ?>
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
  <!--  <div class="row">
        <div class="col-xl-4 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Emial Campaign Monitor</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="bar BarChartShadow overflow-hidden" data-peity='{ "fill": ["#f2574c", "#6512ae"]}'>6,2,8,4,-3,8,1,-3,6,-5,9,2,-8,1,4,8,9,8,2,1</span>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="dash1">
                                <p class="">Subscribes</p>
                                <h4 class="text-primary">6,478</h4>
                                <h6><i class="fe fe-thumbs-up text-green mr-1"></i> last Month</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="dash1">
                                <p class="">UnSubscribes</p>
                                <h4 class="text-primary">1,896</h4>
                                <h6><i class="fe fe-thumbs-down text-red mr-1"></i> last 30 Month</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistics</h3>
                </div>
                <div class="card-body">
                    <canvas id="chart" class="chart-dropshadow chartsh overflow-hidden"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-12 col-lg-6">
            <div class="card overflow-hidden bg-lighter text-white">
                <div class="card-body pb-0">
                    <div class="float-left text-secondary">
                        <h5 class="text-secondary">Growth Rate</h5>
                        <h2 class="text-primary">8,975</h2>
                    </div>
                    <div class="float-right dash2 text-gray">
                        <i class="fe fe-trending-up"></i>
                    </div>
                </div>
                <div class="chart-wrapper mt-1 text-center overflow-hidden">
                    <span class="updating-chart">5,3,9,6,5,9,7,3,5,2,5,3,9,6,5,9,7,3,5,2</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-12 col-lg-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="mb-3"><i class="fas fa-download  mr-1"></i> Downloading</h5>
                    <input type="text" class="knob" value="85" data-thickness="0.2" data-width="120" data-height="120" data-bgColor="#f4f7fe" data-fgColor="#f2574c">
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-12 col-lg-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="mb-3"><i class="fas fa-dollar-sign  mr-1"></i> Earnings</h5>
                    <input type="text" class="knob mb-0" value="55" data-thickness="0.2" data-width="120" data-height="120" data-bgColor="#f4f7fe" data-fgColor="#6512ae">
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-12 col-lg-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="mb-3"><i class="fas fa-star  mr-1"></i> Favourites</h5>
                    <input type="text" class="knob mb-0" value="72" data-thickness="0.2" data-width="120" data-height="120" data-bgColor="#f4f7fe" data-fgColor="#aa4cf2">
                </div>
            </div>
        </div>
    </div>
<!--<div class="row">
        <div class="col-xl-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Messages</h3>
                </div>
                <div class="">
                    <div class="list d-flex align-items-center border-bottom p-3">
                        <div class="avatar avatar-lg brround d-block" style="background-image: url(assets/images/users/female/9.jpg)"></div>
                        <div class="wrapper w-100 ml-3">
                            <p class="mb-0 d-flex">
                                <b>Lisa	Glover</b>
                                <small class="text-muted ml-auto">15 mins ago</small>
                            </p>
                            <div class="justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">Hey You it's me again..attached now</p>
                                </div>
                            </div>
                            <div class="mt-1 text-muted">
                                <i class="si si-action-undo mr-1"></i>
                                <i class="si si-settings"></i>
                            </div>
                        </div>
                    </div>
                    <div class="list d-flex align-items-center border-bottom p-3">
                        <div class="avatar avatar-lg brround d-block" style="background-image: url(assets/images/users/male/23.jpg)"></div>
                        <div class="wrapper w-100 ml-3">
                            <p class="mb-0 d-flex">
                                <b>John	Randall</b>
                                <small class="text-muted ml-auto">30 mins ago</small>
                            </p>
                            <div class="justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">Hey I attached some new PSD files...</p>
                                </div>
                            </div>
                            <div class="mt-1 text-muted">
                                <i class="si si-action-undo mr-1"></i>
                                <i class="si si-settings"></i>
                            </div>
                        </div>
                    </div>
                    <div class="list d-flex align-items-center border-bottom p-3">
                        <div class="avatar avatar-lg brround d-block" style="background-image: url(assets/images/users/female/9.jpg)"></div>
                        <div class="wrapper w-100 ml-3">
                            <p class="mb-0 d-flex">
                                <b>Lisa	Glover</b>
                                <small class="text-muted ml-auto">2 days ago</small>
                            </p>
                            <div class="justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">Hi Please Send the Update File.</p>
                                </div>
                            </div>
                            <div class="mt-1 text-muted">
                                <i class="si si-action-undo mr-1"></i>
                                <i class="si si-settings"></i>
                            </div>
                        </div>
                    </div>
                    <div class="list d-flex align-items-center p-3">
                        <div class="avatar avatar-lg brround d-block" style="background-image: url(assets/images/users/male/23.jpg)"></div>
                        <div class="wrapper w-100 ml-3">
                            <p class="mb-0 d-flex">
                                <b>John	Randall </b>
                                <small class="text-muted ml-auto">6 days ago</small>
                            </p>
                            <div class="justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">Hello My new Templates Adding.because those who do not know how to pleasure .</p>
                                </div>
                            </div>
                            <div class="mt-1 text-muted">
                                <i class="si si-action-undo mr-1"></i>
                                <i class="si si-settings"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tasks</h3>
                </div>
                <div class="">
                    <div class="list d-flex align-items-center border-bottom p-4">
                        <div class="">
                            <span class="avatar bg-primary brround avatar-md">CH</span>
                        </div>
                        <div class="wrapper w-100 ml-3">
                            <p class="mb-0 d-flex">
                                <b>New Websites is Created</b>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-clock text-muted mr-1"></i>
                                    <small class="text-muted ml-auto">30 mins ago</small>
                                    <p class="mb-0"></p>
                                </div>
                                <div class="mr-2">
                                    <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="si si-options-vertical text-dark"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Reply</a>
                                        <a class="dropdown-item" href="#">Report Spam</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list d-flex align-items-center border-bottom p-4">
                        <div class="">
                            <span class="avatar bg-danger brround avatar-md">N</span>
                        </div>
                        <div class="wrapper w-100 ml-3">
                            <p class="mb-0 d-flex">
                                <b>Prepare For the Next Project</b>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-clock text-muted mr-1"></i>
                                    <small class="text-muted ml-auto">2 hours ago</small>
                                    <p class="mb-0"></p>
                                </div>
                                <div class="mr-2">
                                    <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="si si-options-vertical text-dark"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Reply</a>
                                        <a class="dropdown-item" href="#">Report Spam</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list d-flex align-items-center border-bottom p-4">
                        <div class="">
                            <span class="avatar bg-info brround avatar-md">S</span>
                        </div>
                        <div class="wrapper w-100 ml-3">
                            <p class="mb-0 d-flex">
                                <b>Decide the live Discussion Time</b>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-clock text-muted mr-1"></i>
                                    <small class="text-muted ml-auto">3 hours ago</small>
                                    <p class="mb-0"></p>
                                </div>
                                <div class="mr-2">
                                    <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="si si-options-vertical text-dark"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Reply</a>
                                        <a class="dropdown-item" href="#">Report Spam</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list d-flex align-items-center border-bottom p-4">
                        <div class="">
                            <span class="avatar bg-warning brround avatar-md">K</span>
                        </div>
                        <div class="wrapper w-100 ml-3">
                            <p class="mb-0 d-flex">
                                <b>Team Review meeting at yesterday at 3:00 pm</b>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-clock text-muted mr-1"></i>
                                    <small class="text-muted ml-auto">4 hours ago</small>
                                    <p class="mb-0"></p>
                                </div>
                                <div class="mr-2">
                                    <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="si si-options-vertical text-dark"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Reply</a>
                                        <a class="dropdown-item" href="#">Report Spam</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list d-flex align-items-center p-4">
                        <div class="">
                            <span class="avatar bg-success brround avatar-md">R</span>
                        </div>
                        <div class="wrapper w-100 ml-3">
                            <p class="mb-0 d-flex">
                                <b>Prepare for Presentation</b>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-clock text-muted mr-1"></i>
                                    <small class="text-muted ml-auto">1 days ago</small>
                                    <p class="mb-0"></p>
                                </div>
                                <div class="mr-2">
                                    <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="si si-options-vertical text-dark"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Reply</a>
                                        <a class="dropdown-item" href="#">Report Spam</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Activity</h3>
                </div>
                <div class="card-body">
                    <div class="activity">
                        <img src="assets/images/users/male/24.jpg" alt="" class="img-activity">
                        <div class="time-activity">
                            <div class="item-activity">
                                <p class="mb-0"><b>Adam	Berry</b> Add a new projects <b> AngularJS Template</b></p>
                                <small class="text-muted ">30 mins ago</small>
                            </div>
                        </div>
                        <img src="assets/images/users/female/10.jpg" alt="" class="img-activity">
                        <div class="time-activity">
                            <div class="item-activity">
                                <p class="mb-0"><b>Irene Hunter</b> Add a new projects <b>Free HTML Template</b></p>
                                <small class="text-muted ">1 days ago</small>
                            </div>
                        </div>
                        <img src="assets/images/users/male/4.jpg" alt="" class="img-activity">
                        <div class="time-activity">
                            <div class="item-activity">
                                <p class="mb-0"><b>John	Payne</b> Add a new projects <b>Free PSD Template</b></p>
                                <small class="text-muted ">3 days ago</small>
                            </div>
                        </div>
                        <img src="assets/images/users/female/8.jpg" alt="" class="img-activity">
                        <div class="time-activity">
                            <div class="item-activity mb-0">
                                <p class="mb-0"><b>Julia Hardacre</b> Add a new projects <b>Free UI Template</b></p>
                                <small class="text-muted ">5 days ago</small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>-->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter  border text-nowrap">
                            <thead>
                            <tr>
                                <th class="w-1">ID</th>
                                <th>Visitor Name</th>
                                <th>Gender</th>
                                <th>Status</th>
                                <th>Phone Number</th>
                                <th>Date</th>
                                <th>Loaction</th>
                                <th>Operation</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><span class="text-muted">#6754</span></td>
                                <td>Adam Berry</td>
                                <td>Male</td>
                                <td><span class="badge badge-pill badge-primary">Normal</span></td>
                                <td>+1 23 456 9876</td>
                                <td><i class="mdi mdi-av-timer text-muted mr-1"></i>10-10-2018</td>
                                <td>USA</td>
                                <td>
                                    <a href="javascript:void(0)" class="mr-3" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fe fe-edit-2 text-dark fs-16"></i></a>
                                    <a href="javascript:void(0)" class="mr-3" data-toggle="tooltip" title="" data-original-title="Check"><i class="fe fe-file text-dark fs-16"></i></a>
                                    <a href="javascript:void(0)" class="mr-3" data-toggle="tooltip" title="" data-original-title="Add"><i class="fe fe-folder-plus text-dark fs-16"></i></a>
                                    <a href="javascript:void(0)" class="mr-3" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fe fe-trash-2 text-dark fs-16"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="text-muted">#5643</span></td>
                                <td>Kylie Peake</td>
                                <td>Female</td>
                                <td><span class="badge badge-pill badge-success">Unusual</span></td>
                                <td>+0 45 678 9966</td>
                                <td><i class="mdi mdi-av-timer text-muted mr-1"></i>08-10-2018</td>
                                <td>Arizona</td>
                                <td>
                                    <a href="javascript:void(0)" class="mr-3" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fe fe-edit-2 text-dark fs-16"></i></a>
                                    <a href="javascript:void(0)" class="mr-3" data-toggle="tooltip" title="" data-original-title="Check"><i class="fe fe-file text-dark fs-16"></i></a>
                                    <a href="javascript:void(0)" class="mr-3" data-toggle="tooltip" title="" data-original-title="Add"><i class="fe fe-folder-plus text-dark fs-16"></i></a>
                                    <a href="javascript:void(0)" class="mr-3" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fe fe-trash-2 text-dark fs-16"></i></a>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection('admin')
