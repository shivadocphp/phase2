@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title"> Edit Employees </h3>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <a href="{{ route('edit.employee_personal',[$id]) }}" class="btn btn-med btn-new-full" disabled  style="color: orangered;"><font colour=white>Personal Details</font></a>
                <a href="{{ route('edit.emp_official',[$id]) }}" class="btn btn-med btn-primary"><font colour=white>Employment Details</font></a>
                <a href="{{ route('edit.emp_bank',[$id]) }}" class="btn btn-med btn-primary"><font colour=white>Bank Details</font></a>
                <a href="{{ route('edit.emp_pip',[$id]) }}" class="btn btn-med btn-primary"><font colour=white>PIP Details</font></a>
                <a href="{{ route('edit.emp_salary',[$id]) }}" class="btn btn-med btn-primary" ><font colour=white>Salary</font></a>
            </div>
            <div class="col-md-4" align="right"><a href="{{ route('all.employee') }}"
                                                   class="btn btn-med btn-primary">View Employees</a>
                <a href="{{ route('create.employee') }}"
                   class="btn btn-med btn-primary">Add Employees</a>
            </div>


        </div>
        <div>
            @if(session('success'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session('error') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
        </div>
        <div class="col-md-12">
            <form action="{{ route('update.employee') }}" method="POST">
                {{method_field('patch')}}
                @csrf
                <table class="table table-responsive">
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-2'>
                                        <input type="hidden" value="{{ $emp_personal->id }}" name="emp_id">
                                        <input type="hidden" value="{{ $emp_personal->emp_code }}" name="emp_code">
                                        Title
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="subtitle">
                                            <option value="Mr." <?php  if($emp_personal->subtitle=="Mr.") echo "selected";?>>Mr.</option>
                                            <option value="Miss." <?php  if($emp_personal->subtitle=="Miss.") echo "selected";?>>Miss.</option>
                                            <option value="Mrs." <?php  if($emp_personal->subtitle=="Mrs.") echo "selected";?>>Mrs.</option>
                                        </select>
                                        @error('subtitle')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        First Name
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="firstname" value="{{ $emp_personal-> firstname}}">
                                        @error('firstname')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Middle Name
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="middlename" value="{{ $emp_personal->middlename }}">

                                    </div>
                                    <div class='col-md-2'>
                                        Last Name
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="lastname" value="{{ $emp_personal-> lastname}}">

                                    </div>
                                    <div class='col-md-1'>
                                        Gender
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="gender">
                                            <option value="Male" <?php  if($emp_personal->gender=="Male") echo "selected";?>>Male</option>
                                            <option value="Female" <?php  if($emp_personal->gender=="Female") echo "selected";?>>Female</option>
                                            <option value="Transgender Male" <?php  if($emp_personal->gender=="Transgender Male") echo "selected";?>>Transgender Male</option>
                                            <option value="Transgender Female" <?php  if($emp_personal->gender=="Transgender Female") echo "selected";?>>Transgender Female</option>
                                            <option value="Others" <?php  if($emp_personal->gender=="Others") echo "selected";?>>Prefer not to answer</option>
                                        </select>
                                        @error('gender')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                    Shift
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="shift_id">
                                        <option value="1" <?php  if($emp_personal->shift_id=="1") echo "selected";?>>1(Morning)</option>
                                        <option value="2" <?php  if($emp_personal->shift_id=="2") echo "selected";?>>2(Night)</option>
                                    </select>
                                    
                                    @error('shift_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                    <div class="col-md-1">
                                        Date of Birth
                                        <input type="date" name="dob" class="form-control" value="{{ $emp_personal->dob }}">
                                        @error('dob')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-4'>
                                        Personal Email ID
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="personal_emailID" value="{{ $emp_personal->personal_emailID}}">
                                        @error('personal_emailID')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Landline No.

                                        <input type="number" class="form-control" name="landline" value="{{ $emp_personal->landline}}">
                                    </div>
                                    <div class='col-md-2'>
                                        Mobile
                                        <font color="red" size="3">*</font>
                                        <input type="number" class="form-control" name="mobile1" value="{{ $emp_personal->mobile1}}">
                                        @error('mobile1')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Alternate Mobile No.
                                        <input type="number" class="form-control" name="mobile2" value="{{ $emp_personal->mobile2}}">
                                    </div>
                                    <div class='col-md-2'>
                                        Differently Abled.
                                        <select name="diff_abled" class="form-control">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>

                                    </div>


                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-2'>
                                        Aadhaar Card No.
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="aadhaar_no" value="{{ $emp_personal->aadhaar_no}}">
                                        @error('aadhaar_no')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Pan Card No.
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="pan_no" value="{{ $emp_personal->pan_no}}">
                                        @error('pan_no')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Driving Licence No.
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="dl_no" value="{{ $emp_personal->dl_no}}">
                                        @error('dl_no')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Date of Expiry
                                        <font color="red" size="3">*</font>
                                        <input type="date" class="form-control" name="dl_expiry_date" value="{{ $emp_personal->dl_expiry_date}}">
                                        @error('dl_expiry_date')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Qualification Level
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="quali_level_id" id="quali_level_id">
                                            <option value="">--Select--</option>
                                            @foreach($qlevel as $ql)
                                                <option value="{{ $ql->id }}"  <?php  if($emp_personal->quali_level_id == $ql->id) echo "selected";?>>{{$ql->qualificationlevel}}</option>
                                            @endforeach
                                        </select>
                                        @error('quali_level_id')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Qualification
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="quali_id" id="quali_id">
                                            @foreach($qualification as $quali)
                                                <option value="{{ $quali->id }}" <?php if($quali->id == $emp_personal->quali_id) echo "selected"; ?>>{{$quali->qualification}}</option>
                                            @endforeach

                                        </select>
                                        @error('quali_id')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class='col-md-12'>
                                    Residential Address <font color="red" size="3">*</font><br>
                                </div>
                                <div class="row">
                                    <div class='col-md-3'>
                                        <input type="text" class="form-control" name="p_address1"
                                               placeholder="Address Line 1" value="{{ $emp_personal->p_address1}}">
                                        @error('p_address1')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        <select name="p_state_id" id="p_state_id" class="form-control">
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}"  <?php  if($emp_personal->p_state_id==$state->id) echo "selected";?>>{{ $state->state }}</option>
                                            @endforeach
                                        </select>
                                        @error('p_state_id')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        <select name="p_city_id" id="p_city_id" class="form-control">
                                            <option value="">Select City</option>
                                            @foreach($p_cities as $city)
                                                <option value="{{ $city->id }}"  <?php  if($emp_personal->p_city_id==$city->id) echo "selected";?>>{{ $city->city }}</option>
                                            @endforeach
                                        </select>
                                        @error('p_city_id')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>

                                    <div class='col-md-3'>
                                        <select class="form-control" name="p_country_id" id="p_country_id">
                                            <option>Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" <?php  if($emp_personal->p_country_id==$country->id) echo "selected";?>>{{ $country->country }}</option>
                                            @endforeach
                                        </select>
                                        @error('p_country_id')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        <input type="number" class="form-control" name="p_address_pincode"
                                               placeholder="Pincode" maxlength="6" value="{{ $emp_personal->p_address_pincode}}">
                                        @error('p_address_pincode')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class='col-md-12'>
                                    Communication Address:<font color="red" size="3">*</font>

                                    Same as Residential Address?
                                    <input type="checkbox" id="same_address_com" onclick="ShowHideDiv(this)"
                                           name="same_address" value="same">
                                </div>

                                <div id="same_address_display" style="display: block">
                                    <div class="row">
                                        <div class='col-md-3'>
                                            <input type="text" class="form-control" name="c_address1"
                                                   placeholder="Address Line 1" value="{{ $emp_personal->c_address1}}">
                                        </div>
                                        <div class='col-md-2'>
                                            <select name="c_state_id" id="c_state_id" class="form-control">
                                                <option value="">Select State</option>
                                                @foreach($states as $state)
                                                    <option value="{{ $state->id }}" <?php  if($emp_personal->c_state_id==$state->id) echo "selected";?>>{{ $state->state }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class='col-md-2'>
                                            <select name="c_city_id" id="c_city_id" class="form-control">
                                                <option value="">Select city</option>
                                                @foreach($c_cities as $city)
                                                    <option value="{{ $city->id }}" <?php  if($emp_personal->c_city_id==$city->id) echo "selected";?>>{{ $city->city }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class='col-md-3'>
                                            <select class="form-control" name="c_country_id" id="c_country_id">
                                                <option>Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" <?php  if($emp_personal->c_country_id==$country->id) echo "selected";?>>{{ $country->country }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class='col-md-2'>
                                            <input type="number" class="form-control" name="c_address_pincode"
                                                   placeholder="Pincode" maxlength="6" value="{{ $emp_personal->c_address_pincode}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="row">
                                    <div class='col-md-3'>
                                        Blood Group
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="blood_group">
                                            <option>--Select--</option>
                                            <option value="O-ve" <?php  if($emp_personal->blood_group=="O-ve") echo "selected";?>>O-ve</option>
                                            <option value="O+ve" <?php  if($emp_personal->blood_group=="O+ve") echo "selected";?>>O+ve</option>
                                            <option value="A-ve" <?php  if($emp_personal->blood_group=="A-ve") echo "selected";?>>A-ve</option>
                                            <option value="A+ve" <?php  if($emp_personal->blood_group=="A+ve") echo "selected";?>>A+ve</option>
                                            <option value="B-ve" <?php  if($emp_personal->blood_group=="B-ve") echo "selected";?>>B-ve</option>
                                            <option value="B+ve" <?php  if($emp_personal->blood_group=="B+ve") echo "selected";?>>B+ve</option>
                                        </select>
                                    </div>
                                    <div class='col-md-3'>
                                        Guardian

                                        <select class="form-control" name="guardian_type">
                                            <option value="">--Select Guardian--</option>
                                            <option value="Father" <?php  if($emp_personal->guardian_type=="Father") echo "selected";?>>Father</option>
                                            <option value="Mother" <?php  if($emp_personal->guardian_type=="Mother") echo "selected";?>>Mother</option>
                                            <option value="Brother" <?php  if($emp_personal->guardian_type=="Brother") echo "selected";?>>Brother</option>
                                            <option value="Husband" <?php  if($emp_personal->guardian_type=="Husband") echo "selected";?>>Husband</option>
                                            <option value="Wife" <?php  if($emp_personal->guardian_type=="Wife") echo "selected";?>>Wife</option>
                                            <option value="Sister" <?php  if($emp_personal->guardian_type=="Sister") echo "selected";?>>Sister</option>
                                        </select>
                                    </div>
                                    <div class='col-md-3'>
                                        Guardian Name

                                        <input type="text" class="form-control" name="guardian_name" value="{{ $emp_personal->guardian_name}}">
                                    </div>
                                    <div class='col-md-3'>
                                        Guardian Mobile No.
                                        <input type="number" class="form-control" name="guardian_mobile" value="{{ $emp_personal->guardian_mobile}}">
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="btn btn-primary" value="edit_personal" name="edit_personal">UPDATE
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <script>
        function ShowHideDiv(chkPassport) {
            var same_address_display = document.getElementById("same_address_display");
            same_address_display.style.display = same_address_com.checked ? "none" : "block";
        }
        $(document).ready(function () {
            $('#quali_level_id').on('change', function () {
                var idlevel = this.value;
                $("#quali_id").html('');
                $.ajax({
                    url: "{{url('api/fetch-qualification')}}",
                    type: "POST",
                    data: {
                        quali_level_id: idlevel,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#quali_id').html('');
                        $.each(result.qualification, function (key, value) {
                            $("#quali_id").append('<option value="' + value
                                .id + '">' + value.qualification + '</option>');
                        });
                        //$('#city-dd').html('<option value="">Select City</option>');
                    }
                });
            });

        });
        $(document).ready(function () {
            $('#p_state_id').on('change', function () {
                var idlevel = this.value;
                $("#p_city_id").html('');
                $.ajax({
                    url: "{{url('api/fetch-city')}}",
                    type: "POST",
                    data: {
                        state_id: idlevel,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#p_city_id').html('');
                        $.each(result.city, function (key, value) {
                            $("#p_city_id").append('<option value="' + value
                                .id + '">' + value.city + '</option>');
                        });
                        //$('#city-dd').html('<option value="">Select City</option>');
                    }
                });
            });

        });
        $(document).ready(function () {
            $('#c_state_id').on('change', function () {
                var idlevel = this.value;
                $("#c_city_id").html('');
                $.ajax({
                    url: "{{url('api/fetch-city')}}",
                    type: "POST",
                    data: {
                        state_id: idlevel,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#c_city_id').html('');
                        $.each(result.city, function (key, value) {
                            $("#c_city_id").append('<option value="' + value
                                .id + '">' + value.city + '</option>');
                        });
                        //$('#city-dd').html('<option value="">Select City</option>');
                    }
                });
            });

        });

    </script>
@endsection
