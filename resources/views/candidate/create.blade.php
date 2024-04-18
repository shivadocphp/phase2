@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
{{-- selec2 cdn --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="page-header">
    <h3 class="page-title">Add Candidate</h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('create.candidate') }}" class="btn btn-med btn-new-full" disabled
                style="color: orangered;"> Candidate Basic Details</a>


        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.candidate') }}" class="btn btn-med btn-primary">View
                Candidates</a>
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
    <div class="row">
        <div class="col-md-14">
            <form action="{{ route('store.candidate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <table class="table table-striped">
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-3'>
                                        Upload Resume
                                        <font color="red" size="3">*</font>
                                        <input type="file" name="candidate_resume" class="form-control" id="candidate_resume" required >
                                        @error('candidate_resume')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-3'>
                                        Name
                                        <font color="red" size="3">*</font>
                                        <input type="text" name="candidate_name" class="form-control" id="candidate_name" required value="{{ old('candidate_name') }}">
                                        @error('candidate_name')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Gender
                                        <font color="red" size="3">*</font>
                                        <select name="gender" class="form-control" required>
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
                                        Contact No.
                                        <font color="red" size="3">*</font>
                                        <input type="number" name="contact_no" class="form-control" required value="{{ old('contact_no') }}"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        @error('contact_no')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>  
                                        Whatsapp <i class="fab fa-whatsapp fa-1x" style=" color:#fff;
                                        background:linear-gradient(#25d366,#25d366) 14% 84%/16% 16% no-repeat,radial-gradient(#25d366 60%,transparent 0);"></i>
                                        <font color="red" size="3">*</font>
                                        <input type="number" name="whatsapp_no" class="form-control" required value="{{ old('whatsapp_no') }}"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        @error('whatsapp_no')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        Email ID <i class="fa fa-envelope-square fa-1x" style="color: darkred"></i>
                                        <font color="red" size="3">*</font>
                                        <input type="email" name="candidate_email" class="form-control" required value="{{ old('candidate_email') }}">
                                        @error('candidate_email')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        Current Company
                                        <input type="text" name="current_company" class="form-control" required value="{{ old('current_company') }}">
                                        @error('current_company')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        Current Designation
                                        <select name="designation" class="form-control">
                                            <option value="">--Select--</option>
                                            @foreach($designation as $desig)
                                            <option value="{{$desig->id}}" {{ old('designation') == $desig->id ? 'selected' : '' }}>{{$desig->designation}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        Current salary (CTC)
                                        <input type="number" name="current_salary" class="form-control" 
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="7" value="{{ old('current_salary') }}">

                                    </div>
                                    <div class="col-md-2">
                                        Expected salary (CTC)
                                        <input type="number" name="expected_salary" class="form-control" value="{{ old('expected_salary') }}"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="7">

                                    </div>
                                    <div class="col-md-3">
                                        Notice Period
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="notice_period" value="{{ old('notice_period') }}"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="2">
                                            <div class="input-group-append">
                                                <select name="duration" class="form-control">
                                                    <option value="Days" {{ old('duration') == 'Days' ? 'selected' : '' }}>Days</option>
                                                    <option value="Months" {{ old('duration') == 'Months' ? 'selected' : '' }}>Months</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        Total Experience
                                        <input type="text" name="total_exp" class="form-control" value="{{ old('total_exp') }}">
                                    </div>
                                    <div class="col-md-2">
                                        Current Location
                                        <input type="text" name="current_location" class="form-control" required value="{{ old('current_location') }}"
                                            id="current_location">
                                        @error('current_location')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        Preferred Location<font color="red" size="3">*</font>
                                        <input type="text" name="preferred_location" class="form-control" required value="{{ old('preferred_location') }}"
                                            id="preferred_location">
                                        @error('preferred_location')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        Employement Mode<font color="red" size="3">*</font>
                                        <select name="employement_mode" class="form-control">
                                            <option value="">--Select--</option>
                                            <option value="Permanent" {{ old('employement_mode') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                            <option value="Contract" {{ old('employement_mode') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                        </select>
                                        @error('employement_mode')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        PF Status<font color="red" size="3">*</font>
                                        <select name="pf_status" class="form-control">
                                            <option value="">--Select--</option>
                                            <option value="Yes" {{ old('pf_status') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="No" {{ old('pf_status') == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                        @error('pf_status')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-1'>
                                        Passport
                                        <font color="red" size="3">*</font>
                                        <select name="passport" class="form-control" required>
                                            <option value="No" {{ old('passport') == 'No' ? 'selected' : '' }}>No</option>
                                            <option value="Yes" {{ old('passport') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                        @error('passport')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        Shift<font color="red" size="3">*</font>
                                        <select name="preferred_shift" class="form-control">
                                            <option value="">--Select--</option>
                                            <option value="Day" {{ old('preferred_shift') == 'Day' ? 'selected' : '' }}>Day</option>
                                            <option value="Night" {{ old('preferred_shift') == 'Night' ? 'selected' : '' }}>Night</option>
                                        </select>
                                        @error('preferred_shift')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Qualification Level
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="quali_level_id" id="quali_level_id" required>
                                            <option value="">--Select--</option>
                                            @foreach($qlevel as $ql)
                                            <option value="{{ $ql->id }}" {{ old('quali_level_id') == $ql->id ? 'selected' : '' }}>{{$ql->qualificationlevel}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class='col-md-3'>
                                        Qualification
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="quali_id" id="quali_id" required>
                                            <option>--select--</option>

                                        </select>
                                    </div>
                                    <div class='col-md-2'>
                                        Communication
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="communication" id="communication" required>
                                            <option>--select--</option>
                                            <option value="Excellent" {{ old('communication') == 'Excellent' ? 'selected' : '' }}>Excellent</option>
                                            <option value="Good" {{ old('communication') == 'Good' ? 'selected' : '' }}>Good</option>
                                            <option value="Average" {{ old('communication') == 'Average' ? 'selected' : '' }}>Average</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        Skills<font color="red" size="3">*</font><br>
                                        <select name="skills[]" id="tags" select2 select2-hidden-accessible
                                            multiple="multiple" class="form-control" style="width: 600px" required>
                                            @foreach($skills as $s)
                                            <option value="{{$s->id}}">{{ $s->skill }}</option>
                                            @endforeach
                                        </select>
                                        <!-- <input type="text" name="skills" id="skill_input" required class="form-control">-->
                                    </div>
                                    <div class="col-md-2">Profile Status
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="status" id="status" required>
                                            <option value="">--select--</option>
                                            <option value="Interested"  {{ old('status') == 'Interested' ? 'selected' : '' }}>Interested</option>
                                            <option value="Not Interested"  {{ old('status') == 'Not Interested' ? 'selected' : '' }}>Not Interested</option>
                                            <option value="Fake profile"  {{ old('status') == 'Fake profile' ? 'selected' : '' }}>Fake profile</option>
                                            <option value="Not looking for change"  {{ old('status') == 'Not looking for change' ? 'selected' : '' }}>Not looking for change</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">Profile Source
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="profile_source" id="profile_source" required>
                                            <option value="">--select--</option>
                                            <option value="Naukri" {{ old('profile_source') == 'Naukri' ? 'selected' : '' }}>Naukri</option>
                                            <option value="Not Interested" {{ old('profile_source') == 'Not Interested' ? 'selected' : '' }}>Data</option>
                                            <option value="Fake profile" {{ old('profile_source') == 'Fake profile' ? 'selected' : '' }}>Fake profile</option>
                                            <option value="LinkedIn" {{ old('profile_source') == 'LinkedIn' ? 'selected' : '' }}>LinkedIn</option>
                                            <option value="Indeed" {{ old('profile_source') == 'Indeed' ? 'selected' : '' }}>Indeed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="width:100%">
                                        Comments
                                        <textarea name="comments" class="form-control" rows="2">
                                        {{ old('comments') }}
                                        </textarea>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12" align="center">
                                        <br>
                                        <button type="submit" class="btn btn-primary" value="save_draft_next"
                                            title="Save and move to next step">Save
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


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js">
</script>
<script type="text/javascript">
var values = $('#tags option[selected="true"]').map(function() {
    return $(this).val();
}).get();

// you have no need of .trigger("change") if you dont want to trigger an event
$('#tags').select2({
    placeholder: "Please select"
});

var routecandidate = "{{ route('autocomplete') }}";

$('#candidate_name').typeahead({

    source: function(query, process) {
        return $.get(routecandidate, {
            query: query,

        }, function(data) {
            console.log(data);
            return process(data);
        });
    }
});

var routelocation = "{{ route('autocompletelocation') }}";

$('#current_location').typeahead({

    source: function(query, process) {
        return $.get(routelocation, {
            query: query,

        }, function(data) {
            console.log(data);
            return process(data);
        });
    }
});


$('#preferred_location').typeahead({

    source: function(query, process) {
        return $.get(routelocation, {
            query: query,

        }, function(data) {
            console.log(data);
            return process(data);
        });
    }
});


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
            }
        });
    });
});
</script>
@endsection