@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Edit Requirement </h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('edit.requirement',$id) }}" class="btn btn-med btn-new-full"
                style="color: orangered;">Position Details</></a>
            <a href="{{ route('edit.requirement_con',$id) }}" class="btn btn-med btn-primary">Conditions</a>


        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.requirement') }}"
                class="btn btn-med btn-primary">View Requirements</a>
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
        <form action="{{ route('update.requirement') }}" method="POST">
            {{method_field('patch')}}
            @csrf
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-3'>
                                    Client
                                    <font color="red" size="3">*</font>
                                    <input type="hidden" value="{{ $id }}" name="requirement_id">
                                    <select class="form-control" name="client_id" id="client_id" required>
                                        <option value="">--Select Client--</option>
                                        @foreach($client as $cl)
                                        <option value="{{$cl->id}}" <?php  if ($requirement->client_id == $cl->id) {
                                                    echo "selected";
                                                }?>>{{$cl->client_code ."- ". $cl->company_name}}</option>
                                        @endforeach
                                    </select>
                                    @error('client')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-3'>
                                    Location
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="location" id="location" required>
                                        @foreach($client_address as $ca)
                                        <option value="{{$ca->id}}" <?php  if ($requirement->location == $ca->id) {
                                                    echo "selected";
                                                }?>>{{$ca->address .','.$ca->city.','.$ca->state}}</option>
                                        @endforeach
                                    </select>
                                    @error('location')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    Billing Option agreed
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="agreed" id="agreed" required>
                                        <option value="">--Select billing agreed--</option>
                                        @foreach($agreed_array as $val)
                                        <option value="{{$val}}" <?php  if ($requirement->agreed == $val) {
                                                        echo "selected";
                                                    }?>>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                    @error('agreed')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>

                                <div class='col-md-2'>
                                    Position
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="position" required>
                                        <option value="">--Select position--</option>
                                        @foreach($designation as $d)
                                        <option value="{{$d->id}}" <?php
                                                    if ($requirement->position == $d->id) {
                                                        echo "selected";
                                                    }
                                                    ?>>{{$d->designation}}</option>
                                        @endforeach
                                    </select>
                                    @error('position')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    Total Position
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="total_position" required>
                                        <option value="">--Select position--</option>
                                        @for($i=1;$i<=100;$i++) <option value="{{$i}}" <?php
                                                    if ($requirement->total_position == $i) {
                                                        echo "selected";
                                                    }
                                                    ?>>{{$i}}</option>
                                            @endfor
                                    </select>
                                    @error('total_position')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>


                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    Years of Experience <font color="red" size="3">*</font>
                                </div>
                                <div class="col-md-6">
                                    Educational Qualification
                                </div>
                                <div class="col-md-4">
                                    Salary Range <font color="red" size="3">*</font>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1">
                                    <select class="form-control" name="min_years" required>
                                        <option value="">-min-</option>
                                        @for($i=0;$i<=15;$i++) <option value="{{$i}}" <?php
                                                    if ($requirement->min_years == $i) {
                                                        echo "selected";
                                                    }
                                                    ?>>{{$i}}</option>
                                            @endfor
                                    </select> @error('min_years')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-1 ">
                                    <select class="form-control" name="max_years" required>
                                        <option value="">-max-</option>
                                        @for($i=0;$i<=15;$i++) <option value="{{$i}}" <?php
                                                    if ($requirement->max_years == $i) {
                                                        echo "selected";
                                                    }
                                                    ?>>{{$i}}</option>
                                            @endfor
                                    </select>
                                    @error('max_years')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-1">
                                    <input type="text" name="matriculation" class="form-control" placeholder="10th"
                                        value="{{$requirement->matriculation}}">
                                    @error('matriculation')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-1">
                                    <input type="text" name="plustwo" class="form-control" placeholder="12th"
                                        value="{{ $requirement->plustwo }}">
                                    @error('plustwo')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    <select class="form-control" name="quali_level_id" id="quali_level_id">
                                        <option value="">--Select--</option>
                                        @foreach($qlevel as $ql)
                                        <option value="{{ $ql->id }}" <?php
                                                    if ($requirement->quali_level_id == $ql->id) {
                                                        echo "selected";
                                                    }
                                                    ?>>{{$ql->qualificationlevel}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class='col-md-2'>
                                    <select class="form-control" name="quali_id" id="quali_id">
                                        <option value="">--select--</option>
                                        @foreach($qualification as $q)
                                        <option value="{{ $q->id }}" <?php
                                                    if ($requirement->quali_id == $q->id) {
                                                        echo "selected";
                                                    }
                                                    ?>>{{$q->qualification}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="salary_min" placeholder="min salary" required
                                        class="form-control" value="{{ $requirement->salary_min }}">
                                    @error('salary_min')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="salary_max" placeholder="max salary" required
                                        class="form-control" value="{{ $requirement->salary_max }}">
                                    @error('salary_max')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">

                            </div>
                        </div>

                        <div class="form-group">
                            <div class='col-md-12'>
                                <div class="row">
                                    <div class="col-md-2">Cab Facility
                                        <select name="cab_facility" class="form-control">
                                            <option value="">--select--</option>
                                            <option value="Y" <?php
                                                    if ($requirement->cab_facility == "Y") {
                                                        echo "selected";
                                                    }
                                                    ?>>Yes
                                            </option>
                                            <option value="N" <?php
                                                    if ($requirement->cab_facility == "N") {
                                                        echo "selected";
                                                    }
                                                    ?>>No
                                            </option>
                                        </select>
                                        @error('cab_facility')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">Hiring Radius
                                        <input type="text" name="hiring_radius" class="form-control"
                                            value="{{ $requirement->hiring_radius }}">
                                    </div>
                                    <div class="col-md-2">Role Type
                                        <select name="role_type" class="form-control">
                                            <option value="">--select--</option>
                                            <option value="On Role" <?php
                                                    if ($requirement->role_type == "On Role") {
                                                        echo "selected";
                                                    }
                                                    ?>>On Role
                                            </option>
                                            <option value="Off Role" <?php
                                                    if ($requirement->role_type == "Off Role") {
                                                        echo "selected";
                                                    }
                                                    ?>>Off Role
                                            </option>
                                        </select>
                                        @error('role_type')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">Employement Type
                                        <input type="text" name="employement_type" class="form-control"
                                            value="{{$requirement->employement_type}}">
                                        @error('employement_type')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">Domain
                                        <input type="text" name="domain" class="form-control">
                                    </div>
                                    <div class="col-md-2">Status <font color="red" size="3">*</font>
                                        <select name="requirement_status" class="form-control">
                                            <option value="Active" <?php
                                                    if ($requirement->requirement_status == "Active") {
                                                        echo "selected";
                                                    }
                                                    ?>>Active
                                            </option>
                                            <option value="Hold" <?php
                                                    if ($requirement->requirement_status == "Hold") {
                                                        echo "selected";
                                                    }
                                                    ?>>Hold
                                            </option>
                                            <option value="Prospect" <?php
                                                    if ($requirement->requirement_status == "Prospect") {
                                                        echo "selected";
                                                    }
                                                    ?>>Prospect
                                            </option>
                                            <option value="Closed by Client" <?php
                                                    if ($requirement->requirement_status == "Closed by Client") {
                                                        echo "selected";
                                                    }
                                                    ?>>Closed By Client
                                            </option>
                                            <option value="Closed by Company" <?php
                                                    if ($requirement->requirement_status == "Closed by Company") {
                                                        echo "selected";
                                                    }
                                                    ?>>Closed by Company
                                            </option>
                                        </select>
                                        @error('requirement_status')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <!-- <div class="col-md-6">
                                        Required Skills <font color="red" size="3">*</font>
                                        <textarea name="skills" class="form-control"
                                                  required>{{ $requirement->skills }}</textarea>
                                        @error('skills')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>-->
                                <div class="col-md-6">


                                    Required Skills<font color="red" size="3">*</font>
                                    <select name="skills[]" id="tags" select2 select2-hidden-accessible
                                        multiple="multiple" style="width: 600px">
                                        <?php  
                                                $skill = json_decode($requirement->skills);
                                               ?>
                                        @foreach($skills as $s)

                                        <option value="{{$s->id}}" <?php if(is_array($skill))
                                                   {if(in_array($s->id,$skill)){ echo "selected";}}?>>{{ $s->skill }}
                                        </option>
                                        @endforeach
                                    </select>



                                    <!-- <input type="text" name="skills" id="skill_input" required class="form-control">-->
                                </div>
                                <div class="col-md-6">
                                    JD <font color="red" size="3">*</font>
                                    <textarea name="jd" class="form-control" required>{{ $requirement->jd }}</textarea>
                                    @error('jd')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="form-group">

                            <div class="row">


                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12" align="center">

                                    <button type="submit" class="btn btn-primary" value="save_position" name="save"
                                        title="Save and move to next step">UPDATE
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
<script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
var values = $('#tags option[selected="true"]').map(function() {
    return $(this).val();
}).get();

// you have no need of .trigger("change") if you dont want to trigger an event
$('#tags').select2({
    placeholder: "Please select"
});
ClassicEditor.create(document.querySelector('#editor'))
    .catch(error => {
        console.error(error);
    });

function ShowHideDiv(chkPassport) {
    var same_address_display = document.getElementById("same_address_display");
    same_address_display.style.display = same_address_com.checked ? "none" : "block";
}

$(document).ready(function() {

    $('#quali_level_id').on('change', function() {
        e.preventDefault();
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

    $('#client_id').on('change', function() {
        e.preventDefault();
        var idlevel = this.value;
        $("#location").html('');
        $("#agreed").html('');
        $.ajax({
            url: "{{url('api/fetch-clientlocation')}}",
            type: "POST",
            data: {
                client_id: idlevel,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $('#location').html('<option value="">Select location</option>');
                $.each(result.location, function(key, value) {
                    $("#location").append('<option value="' + value
                        .id + '">' + value.address + ',' + value.state + ',' +
                        value.city + '</option>');
                });
                $('#agreed').html('<option value="">Select billing agreed</option>');
                $.each(result.agreed, function(key, value) {
                    if (value != null) {
                        $("#agreed").append('<option value="' + value +
                            '">' + value + '</option>');
                    }
                });
                //$('#city-dd').html('<option value="">Select City</option>');
            }
        });
    });

});
</script>
@endsection