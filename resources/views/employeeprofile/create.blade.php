@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Add Employees </h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('create.employee') }}" class="btn btn-med btn-new-full" disabled
                style="color: orangered;">Personal Details</></a>
            <a href="" class="btn btn-med btn-primary">Employment Details</a>
            <a href="" class="btn btn-med btn-primary">Bank Details</a>


        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.employee') }}" class="btn btn-med btn-primary">View
                Employees</a>
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

    </div>
    <div class="col-md-12">
        <form action="{{ route('store.employee') }}" method="POST">
            @csrf
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-2'>
                                    Title
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="subtitle">
                                        <option value="">--Select--</option>
                                        <option value="Mr." {{ old('subtitle') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                        <option value="Miss." {{ old('subtitle') == 'Miss.' ? 'selected' : '' }}>Miss.</option>
                                        <option value="Mrs." {{ old('subtitle') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                    </select>
                                    @error('subtitle')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    First Name
                                    <font color="red" size="3">*</font>
                                    <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}">
                                    @error('firstname')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    Middle Name
                                    <font color="red" size="3">*</font>
                                    <input type="text" class="form-control" name="middlename" value="{{ old('middlename') }}">
                                    @error('middlename')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    Last Name
                                    <font color="red" size="3">*</font>
                                    <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}">
                                    @error('lastname')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-1'>
                                    Gender
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="gender">
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Others" {{ old('gender') == 'Others' ? 'selected' : '' }}>Transgender</option>
                                        <option value="Prefer not to answer" {{ old('gender') == 'Prefer not to answer' ? 'selected' : '' }}>Prefer not to answer</option>
                                    </select>
                                    
                                    @error('gender')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    Shift
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="shift_id">
                                        <option value="1" {{ old('shift_id') == '1' ? 'selected' : '' }}>1(Morning)</option>
                                        <option value="2" {{ old('shift_id') == '2' ? 'selected' : '' }}>2(Night)</option>
                                    </select>
                                    
                                    @error('shift_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-1">
                                    Date of Birth
                                    <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
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
                                    <input type="text" class="form-control" name="personal_emailID" value="{{ old('personal_emailID') }}">
                                    @error('personal_emailID')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    Landline No.

                                    <input type="number" class="form-control" name="landline" value="{{ old('landline') }}">
                                </div>
                                <div class='col-md-2'>
                                    Mobile
                                    <font color="red" size="3">*</font>
                                    <input type="number" class="form-control" name="mobile1"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        maxlength="10" value="{{ old('mobile1') }}">
                                    @error('mobile1')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    Alternate Mobile No.
                                    <input type="number" class="form-control" name="mobile2"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        maxlength="10" value="{{ old('mobile2') }}">
                                </div>
                                <div class='col-md-2'>
                                    Differently Abled.
                                    <select name="diff_abled" class="form-control">
                                        <option value="No" {{ old('diff_abled') == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Yes" {{ old('diff_abled') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    </select>

                                </div>


                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-2'>
                                    Aadhaar Card No.

                                    <input class="form-control"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        type="number" maxlength="12" name="aadhaar_no" value="{{ old('aadhaar_no') }}">
                                </div>
                                <div class='col-md-2'>
                                    Pan Card No.
                                    <input type="text" class="form-control" name="pan_no" maxlength="10" minlength="10" value="{{ old('pan_no') }}">
                                </div>
                                <div class='col-md-2'>
                                    Driving Licence No.
                                    <input type="text" class="form-control" name="dl_no" value="{{ old('dl_no') }}">
                                </div>
                                <div class='col-md-2'>
                                    Date of Expiry
                                    <input type="date" class="form-control" name="dl_expiry_date" value="{{ old('dl_expiry_date') }}">
                                </div>
                                <div class='col-md-2'>
                                    Qualification Level
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="quali_level_id" id="quali_level_id">
                                        <option value="">--Select--</option>
                                        @foreach($qlevel as $ql)
                                        <option value="{{ $ql->id }}" {{ old('quali_level_id') == $ql->id ? 'selected' : '' }}>
                                            {{ $ql->qualificationlevel }}
                                        </option>
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
                                        <option>--select--</option>
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
                                        placeholder="Address Line 1" value="{{ old('p_address1') }}">
                                    @error('p_address1')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    <select name="p_state_id" id="p_state_id" class="form-control" onchange="updateCity();">
                                            <option value='0'>-- Select state --</option>
                                            @foreach($states as $st)
                                            <option value="{{ $st->id }}" {{ old('p_state_id') == $st->id ? 'selected' : '' }}>
                                                {{ $st->state }}
                                            </option>
                                                
                                            @endforeach
                                    </select>
                                    @error('p_state_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>

                                    <select name="p_city_id" class="form-control" id='p_city_id' style='width: 200px;'>
                                        <option value='0'>-- Select city --</option>

                                    </select>
                                    @error('p_city_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>

                                <div class='col-md-3'>
                                    <select class="form-control" name="p_country_id" id="p_country_id">
                                        @foreach($countries as $c)
                                        <option value="{{$c->id}}" <?php if($c->country=="India")  echo "selected";?>>
                                            {{$c->country}}</option>
                                        @endforeach
                                    </select>
                                    @error('p_country_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    <input type="number" class="form-control" name="p_address_pincode"
                                        placeholder="Pincode"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        maxlength="6" value="{{ old('p_address_pincode') }}">
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
                                            placeholder="Address Line 1" value="{{ old('c_address1') }}">
                                            @error('c_address1')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                    </div>
                                    <div class='col-md-2'>
                                    <select name="c_state_id" id="c_state_id" class="form-control">
                                        <option value="" {{ old('c_state_id') == '' ? 'selected' : '' }}>-- Select state --</option>
                                        @foreach($states as $st)
                                            <option value="{{$st->id}}" {{ old('c_state_id') == $st->id ? 'selected' : '' }}>{{$st->state}}</option>
                                        @endforeach
                                    </select>
                                    @error('c_state_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        <select name="c_city_id" id="c_city_id" class="form-control">


                                        </select>
                                        @error('c_city_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                    </div>

                                    <div class='col-md-3'>
                                        <select class="form-control" name="c_country_id" id="c_country_id">
                                            @foreach($countries as $c)
                                            <option value="{{$c->id}}"
                                                <?php if($c->country=="India")  echo "selected";?>>{{$c->country}}
                                            </option>
                                            @endforeach

                                        </select>

                                        @error('c_country_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                    </div>

                                    <div class='col-md-2'>
                                        <input type="number" class="form-control" name="c_address_pincode"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="6" placeholder="Pincode" maxlength="6" value="{{ old('c_address_pincode') }}">
                                    @error('c_address_pincode')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
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
                                        <option value="" {{ old('blood_group') == '' ? 'selected' : '' }}>--Select--</option>
                                        <option value="O-ve" {{ old('blood_group') == 'O-ve' ? 'selected' : '' }}>O-ve</option>
                                        <option value="O+ve" {{ old('blood_group') == 'O+ve' ? 'selected' : '' }}>O+ve</option>
                                        <option value="A-ve" {{ old('blood_group') == 'A-ve' ? 'selected' : '' }}>A-ve</option>
                                        <option value="A+ve" {{ old('blood_group') == 'A+ve' ? 'selected' : '' }}>A+ve</option>
                                        <option value="B-ve" {{ old('blood_group') == 'B-ve' ? 'selected' : '' }}>B-ve</option>
                                        <option value="B+ve" {{ old('blood_group') == 'B+ve' ? 'selected' : '' }}>B+ve</option>
                                    </select>
                                    @error('blood_group')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-3'>
                                    Guardian

                                    <select class="form-control" name="guardian_type">
                                        <option value="" {{ old('guardian_type') == '' ? 'selected' : '' }}>--Select Guardian--</option>
                                        <option value="Father" {{ old('guardian_type') == 'Father' ? 'selected' : '' }}>Father</option>
                                        <option value="Mother" {{ old('guardian_type') == 'Mother' ? 'selected' : '' }}>Mother</option>
                                        <option value="Brother" {{ old('guardian_type') == 'Brother' ? 'selected' : '' }}>Brother</option>
                                        <option value="Husband" {{ old('guardian_type') == 'Husband' ? 'selected' : '' }}>Husband</option>
                                        <option value="Wife" {{ old('guardian_type') == 'Wife' ? 'selected' : '' }}>Wife</option>
                                        <option value="Sister" {{ old('guardian_type') == 'Sister' ? 'selected' : '' }}>Sister</option>
                                    </select>
                                </div>
                                <div class='col-md-3'>
                                    Guardian Name

                                    <input type="text" class="form-control" name="guardian_name" value="{{ old('guardian_name') }}">
                                </div>
                                <div class='col-md-3'>
                                    Guardian Mobile No.
                                    <input type="number" class="form-control" name="guardian_mobile"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        maxlength="10" value="{{ old('guardian_mobile') }}">
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12" align="center">
                                    <button type="submit" class="btn btn-primary" value="save_draft" name="save_draft"
                                        title="Save and add remaining details later">SAVE AS DRAFT
                                    </button> <button type="submit" class="btn btn-primary" value="save_draft_next"
                                        name="save_draft" title="Save and move to next step">NEXT
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
<script type="text/javascript">
function ShowHideDiv(chkPassport) {
    var same_address_display = document.getElementById("same_address_display");
    same_address_display.style.display = same_address_com.checked ? "none" : "block";
}

$(document).ready(function() {
    $('#quali_level_id').on('change', function() {
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
            success: function(result) {
                $('#quali_id').html('<option value="">Select Qualification</option>');
                $.each(result.qualification, function(key, value) {
                    $("#quali_id").append('<option value="' + value
                        .id + '">' + value.qualification + '</option>');
                });
                //$('#city-dd').html('<option value="">Select City</option>');
            }
        });
    });

});
$(document).ready(function() {
    $('#p_state_id').on('change', function() {
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
            success: function(result) {
                $('#p_city_id').html('<option value="">Select City</option>');
                $.each(result.city, function(key, value) {
                    $("#p_city_id").append('<option value="' + value
                        .id + '">' + value.city + '</option>');
                });
                //$('#city-dd').html('<option value="">Select City</option>');
            }
        });
    });

});
$(document).ready(function() {
    $('#c_state_id').on('change', function() {
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
            success: function(result) {
                $('#c_city_id').html('<option value="">Select City</option>');
                $.each(result.city, function(key, value) {
                    $("#c_city_id").append('<option value="' + value
                        .id + '">' + value.city + '</option>');
                });
                //$('#city-dd').html('<option value="">Select City</option>');
            }
        });
    });

});


// CSRF Token
/* var CSRF_TOKEN = $('meta[name="csrf-token.php"]').attr('content');
 $(document).ready(function () {
     $('#p_city_id').select2({
         ajax: {
             url: 'route('cities.getcity')',
             //   type: "POST",
             dataType: 'json',
             delay: 250,
             data: function (params) {
                 return {
                     q: params.term, // search term
                     page: params.page
                 };
             },
             processResults: function (data, params) {
                 console.log(data);
                 // Transforms the top-level key of the response object from 'items' to 'results'
                 return {
                     results: data
                 };
             }
         }
     });

 });*/



/**/
</script>
@endsection