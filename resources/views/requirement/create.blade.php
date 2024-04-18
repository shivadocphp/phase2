@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Add Requirement </h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('create.requirement') }}" class="btn btn-med btn-new-full" disabled
                style="color: orangered;">Position Details</></a>
            <a href="" class="btn btn-med btn-primary">Conditions</a>


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
    <div class="row">
        <div class="col-md-14">
            <form action="{{ route('store.requirement') }}" method="POST">
                @csrf
                <table class="table table-striped">
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-3'>
                                        Client
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="client_id" id="client_id" required>
                                            <option value="">--Select Client--</option>
                                            @foreach($client as $cl)
                                            <option value="{{$cl->id}}" <?php if ($cl->id == $id) {
                                                        echo "selected";
                                                    }?>>{{$cl->client_code ."- ". $cl->company_name}}</option>
                                            @endforeach
                                        </select>
                                        @error('client_id')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-3'>
                                        Location <font color="red" size="3">*</font>
                                        <select class="form-control" name="location" id="location" required>
                                            <option value="">--Select location--</option>

                                        </select>
                                        @error('location')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Billing Option agreed <font color="red" size="3">*</font>
                                        <select class="form-control" name="agreed" id="agreed" required>
                                            <option value="">--Select location--</option>
                                        </select>
                                        @error('agreed')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Position <font color="red" size="3">*</font>
                                        <select class="form-control" name="position" required>
                                            <option value="">--Select position--</option>
                                            @foreach($designation as $d)
                                            <option value="{{$d->id}}">{{$d->designation}}</option>
                                            @endforeach
                                        </select>
                                        @error('position')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Total Position <font color="red" size="3">*</font>
                                        <select class="form-control" name="total_position" required>
                                            <option value="">--Select position--</option>
                                            @for($i=1;$i<=100;$i++) <option value="{{$i}}">{{$i}}</option>
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
                                            @for($i=0;$i<=15;$i++) <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                        </select> 
                                        @error('min_years')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-1 ">
                                        <select class="form-control" name="max_years" required>
                                            <option value="">-max-</option>
                                            @for($i=0;$i<=15;$i++) <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                        </select>
                                        @error('max_years')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-1">
                                        <input type="text" name="matriculation" class="form-control" placeholder="10th">
                                        @error('matriculation')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-1">
                                        <input type="text" name="plustwo" class="form-control" placeholder="12th">
                                        @error('plustwo')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        <select class="form-control" name="quali_level_id" id="quali_level_id">
                                            <option value="">--Select--</option>
                                            @foreach($qlevel as $ql)
                                            <option value="{{ $ql->id }}">{{$ql->qualificationlevel}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class='col-md-2'>
                                        <select class="form-control" name="quali_id" id="quali_id">
                                            <option>--select--</option>

                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="salary_min" placeholder="min salary" required
                                            class="form-control">
                                        @error('salary_min')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="salary_max" placeholder="max salary" required
                                            class="form-control">
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
                                                <option value="Y">Yes</option>
                                                <option value="N">No</option>
                                            </select>
                                            @error('cab_facility')
                                            <span class="text-danger">{{ $message  }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">Hiring Radius
                                            <input type="text" name="hiring_radius" class="form-control">
                                        </div>
                                        <div class="col-md-2">Role Type
                                            <select name="role_type" class="form-control">
                                                <option value="">--select--</option>
                                                <option value="On Role">On Role</option>
                                                <option value="Off Role">Off Role</option>
                                            </select>
                                            @error('role_type')
                                            <span class="text-danger">{{ $message  }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">Employement Type
                                            <input type="text" name="employement_type" class="form-control">
                                            @error('employement_type')
                                            <span class="text-danger">{{ $message  }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">Domain
                                            <input type="text" name="domain" class="form-control">
                                        </div>
                                        <div class="col-md-2">Status <font color="red" size="3">*</font>
                                            <select name="requirement_status" class="form-control">
                                                <option value="Active">Active</option>
                                                <option value="Hold">Hold</option>
                                                <option value="Prospect">Prospect</option>
                                                <option value="Closed by Client">Closed By Client</option>
                                                <option value="Closed by Company">Closed by Company</option>
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
                                        <textarea name="skills" class="form-control" required></textarea>
                                        @error('skills')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                        </div>-->
                                    <div class="col-md-6">
                                        Required Skills<font color="red" size="3">*</font>
                                        <select name="skills[]" id="tags" select2 select2-hidden-accessible
                                            multiple="multiple" style="width: 600px" required>
                                            @foreach($skills as $s)
                                            <option value="{{$s->id}}">{{ $s->skill }}</option>
                                            @endforeach
                                        </select>
                                        @error('skills')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror

                                        <!-- <input type="text" name="skills" id="skill_input" required class="form-control">-->
                                    </div>
                                    <div class="col-md-6">
                                        JD <font color="red" size="3">*</font>
                                        <textarea name="jd" class="form-control" required></textarea>
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

                                        <button type="submit" class="btn btn-primary" value="save_draft_next"
                                            title="Save and move to next step">NEXT
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