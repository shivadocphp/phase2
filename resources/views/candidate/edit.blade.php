@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Edit Candidate</h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('edit.candidate',$id) }}" class="btn btn-med btn-new-full" disabled
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
            <form action="{{ route('update.candidate',$id) }}" method="POST" enctype="multipart/form-data" id="myform">
                {{method_field('patch')}}
                @csrf
                <table class="table table-striped" width="100%">
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-3'>
                                        Upload Resume &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
                                            href="{{ asset('storage/'.$candidate->candidate_resume) }}"
                                            target="_blank">View Uploaded Resume</a>
                                        <font color="red" size="3">*</font>
                                        <input type="file" name="candidate_resume" class="form-control"
                                            id="candidate_resume">
                                        @error('candidate_resume')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-3'>
                                        Name
                                        <font color="red" size="3">*</font>
                                        <input type="text" name="candidate_name" class="form-control"
                                            value="{{$candidate->candidate_name}}" required>
                                        @error('candidate_name')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Gender
                                        <font color="red" size="3">*</font>
                                        <select name="gender" class="form-control" required>
                                            <option value="Male" <?php if ($candidate->gender == "Male") {
                                                    echo "selected";
                                                }?>>Male
                                            </option>
                                            <option value="Female" <?php if ($candidate->gender == "Female") {
                                                    echo "selected";
                                                }?>>Female
                                            </option>
                                            <option value="Transgender" <?php if ($candidate->gender == "Transgender") {
                                                    echo "selected";
                                                }?>>Transgender
                                            </option>
                                            <option value="Others" <?php if ($candidate->gender == "Others") {
                                                    echo "selected";
                                                }?>>Prefer not to answer
                                            </option>
                                        </select>
                                        @error('gender')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Contact No.
                                        <font color="red" size="3">*</font>
                                        <input type="number" name="contact_no" class="form-control"
                                            value="{{$candidate->contact_no}}" required
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        @error('contact_no')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Whatsapp No.<a
                                            href="https://web.whatsapp.com/send?phone=+91{{$candidate->whatsapp_no}}&text=Hi"
                                            data-action="share/whatsapp/share" target="_blank"> <i
                                                class="fab fa-whatsapp fa-1x"
                                                style=" color:#fff;background:linear-gradient(#25d366,#25d366) 
                                                14% 84%/16% 16% no-repeat,radial-gradient(#25d366 60%,transparent 0);"></i></a>
                                        <font color="red" size="3">*</font>
                                        <input type="number" name="whatsapp_no" class="form-control"
                                            value="{{$candidate->whatsapp_no}}" required
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        @error('contact_no')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        Email ID <a target="_top" href="mailto:{{$candidate->candidate_email}}"><i
                                                class="fa fa-envelope-square fa-1x" style="color: darkred"></i></a>
                                        <font color="red" size="3">*</font>
                                        <input type="email" name="candidate_email" class="form-control" required
                                            value="{{$candidate->candidate_email}}">
                                        @error('email')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        Current Company
                                        <input type="text" name="current_company" class="form-control"
                                            value="{{$candidate->current_company}}">
                                        @error('current_company')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        Current Designation
                                        <select name="designation" class="form-control">
                                            <option value="">--Select--</option>
                                            @foreach($designation as $desig)
                                            <option value="{{$desig->id}}" <?php if ($candidate->designation == $desig->id) {
                                                        echo "selected";
                                                    } ?>>{{$desig->designation}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        Current salary (CTC)
                                        <input type="number" name="current_salary" class="form-control"
                                            value="{{$candidate->current_salary}}"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="7">

                                    </div>
                                    <div class="col-md-2">
                                        Expected salary (CTC)
                                        <input type="number" name="expected_salary" class="form-control"
                                            value="{{$candidate->expected_salary}}"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="7">
                                    </div>
                                    <div class="col-md-3">
                                        Notice Period
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="notice_period"
                                                value="{{$candidate->notice_period}}"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="2">
                                            <div class="input-group-append">
                                                <select name="duration" class="form-control">
                                                    <option value="Days" <?php  if ($candidate->duration == "Days") {
                                                            echo "selected";
                                                        }?>>Days
                                                    </option>
                                                    <option value="Months" <?php  if ($candidate->duration == "Months") {
                                                            echo "selected";
                                                        }?>>Months
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        Total Experience
                                        <input type="text" name="total_exp" class="form-control"
                                            value="{{$candidate->total_exp}}">
                                    </div>
                                    <div class="col-md-2">
                                        Current Location
                                        <input type="text" name="current_location"
                                            value="{{$candidate->current_location}}" class="form-control" required>
                                        @error('current_location')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        Preferred Location<font color="red" size="3">*</font>
                                        <input type="text" name="preferred_location"
                                            value="{{$candidate->preferred_location}}" class="form-control" required>
                                        @error('preferred_location')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        Employement Mode<font color="red" size="3">*</font>
                                        <select name="employement_mode" class="form-control" required>
                                            <option value="">--Select--</option>
                                            <option value="Permanent" <?php if ($candidate->employement_mode == "Permanent") {
                                                        echo "selected";
                                                    } ?>>Permanent</option>
                                            <option value="Contract" <?php if ($candidate->employement_mode == "Contract") {
                                                        echo "selected";
                                                    } ?>>Contract</option>
                                        </select>
                                        @error('employement_mode')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        PF Status<font color="red" size="3">*</font>
                                        <select name="pf_status" class="form-control" required>
                                            <option value="">--Select--</option>
                                            <option value="Yes" <?php if ($candidate->pf_status == "Yes") {
                                                        echo "selected";
                                                    } ?>>Yes</option>
                                            <option value="No" <?php if ($candidate->pf_status == "No") {
                                                        echo "selected";
                                                    } ?>>No</option>
                                        </select>
                                        @error('pf_status')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-1">
                                        Passport
                                        <select name="passport" class="form-control">
                                            <option value="">--Select--</option>
                                            <option value="No" <?php if ($candidate->passport == "No") {
                                                    echo "selected";
                                                } ?>>No
                                            </option>
                                            <option value="Yes" <?php if ($candidate->passport == "Yes") {
                                                    echo "selected";
                                                } ?>>Yes
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        Shift<font color="red" size="3">*</font>
                                        <select name="preferred_shift" class="form-control" required>
                                            <option value="">--Select--</option>
                                            <option value="Day" <?php if ($candidate->preferred_shift == "Day") {
                                                        echo "selected";
                                                    } ?>>Day</option>
                                            <option value="Night" <?php if ($candidate->preferred_shift == "Night") {
                                                        echo "selected";
                                                    } ?>>Night</option>
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
                                            <option value="{{ $ql->id }}" <?php if ($candidate->quali_level_id == $ql->id) {
                                                        echo "selected";
                                                    } ?>>{{$ql->qualificationlevel}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class='col-md-3'>
                                        Qualification
                                        <font color="red" size="3">*</font>
                                        <!-- {{ $candidate->quali_id }} -->
                                        <select class="form-control" name="quali_id" id="quali_id" required>
                                        <!-- @if($candidate->quali_id)
                                            @foreach($qualification as $q)
                                            <option value="{{ $q->id }}" -->
                                            <?php 
                                            // if ($candidate->quali_id == $q->id) {
                                            //     echo "selected";
                                            // }
                                                    ?>
                                                    <!-- > -->
                                            <!-- {{ $q->qualification }}</option>
                                            @endforeach
                                        @endif -->
                                        </select>
                                    </div>
                                    <div class='col-md-2'>
                                        Communication
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="communication" id="communication" required>
                                            <option value="">--select--</option>
                                            <option value="Excellent" <?php if ($candidate->communication == "Excellent") {
                                                        echo "selected";
                                                    } ?>>Excellent</option>
                                            <option value="Good" <?php if ($candidate->communication == "Good") {
                                                        echo "selected";
                                                    } ?>>Good</option>
                                            <option value="Average" <?php if ($candidate->communication == "Average") {
                                                        echo "selected";
                                                    } ?>>Average</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        Skills<font color="red" size="3">*</font><br>
                                        <?php
                                            if($candidate->skills != null){
                                            ?>
                                        <select name="skills[]" id="tags" select2 select2-hidden-accessible
                                            multiple="multiple" style="width: 600px" required>
                                            <?php
                                                $skill = json_decode($candidate->skills);
                                                ?>
                                            @foreach($skills as $s)

                                            <option value="{{$s->id}}" <?php if (in_array($s->id, $skill)) {
                                                        echo "selected";
                                                    }?>>{{ $s->skill }}</option>
                                            @endforeach
                                        </select>
                                        <?php } else { ?>
                                        <select name="skills[]" id="tags" select2 select2-hidden-accessible
                                            multiple="multiple" style="width: 600px" required>
                                            @foreach($skills as $s)

                                            <option value="{{$s->id}}">{{ $s->skill }}</option>
                                            @endforeach
                                        </select>
                                        <?php } ?>
                                        <!-- <input type="text" name="skills" id="skill_input" required class="form-control">-->
                                    </div>
                                    <div class="col-md-2">Profile Status
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="status" id="status" required>
                                            <option value="">--select--</option>
                                            <option value="Interested" <?php if ($candidate->status == "Interested") {
                                                        echo "selected";
                                                    } ?>>Interested</option>
                                            <option value="Not Interested" <?php if ($candidate->status == "Not Interested") {
                                                        echo "selected";
                                                    } ?>>Not Interested</option>
                                            <option value="Fake profile" <?php if ($candidate->status == "Fake profile") {
                                                        echo "selected";
                                                    } ?>>Fake profile</option>
                                            <option value="Not looking for change" <?php if ($candidate->status == "Not looking for change") {
                                                        echo "selected";
                                                    } ?>>Not looking for change</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">Profile Source
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="profile_source" id="profile_source" required>
                                            <option value="">--select--</option>
                                            <option value="Naukri" <?php if ($candidate->profile_source == "Naukri") {
                                                        echo "selected";
                                                    } ?>>Naukri</option>
                                            <option value="Data" <?php if ($candidate->profile_source == "Data") {
                                                        echo "selected";
                                                    } ?>>Data</option>
                                            <option value="LinkedIn" <?php if ($candidate->profile_source == "LinkedIn") {
                                                        echo "selected";
                                                    } ?>>LinkedIn</option>
                                            <option value="Indeed" <?php if ($candidate->profile_source == "Indeed") {
                                                        echo "selected";
                                                    } ?>>Indeed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="width:100%;">
                                        Comments
                                        <textarea name="comments" id="comments" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6" align="right">
                                        <br>
                                        <button type="submit" class="btn btn-primary" name="save_draft_next" value="submit1"
                                            title="Save and move to next step">Update
                                        </button>
                                    </div>
                                    <div class="col-md-6" align="left">
                                        <br>
                                        <button type="submit" class="btn btn-primary" name="save_comments" value="submit2"
                                        id="submitWithoutValidationButton" title="Save and move to next step">Update Comments
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Comment</th>
                                                        <th>Added By</th>
                                                        <th>Added at</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($all_comments as $key => $row)
                                                    <tr>
                                                        <td> {{ $key + $all_comments->firstItem()}}</td>
                                                        <td> {{ $row->comment }}</td>
                                                        <td> @isset($row->addedBy) {{ $row->addedBy->name }} @endisset
                                                        </td>
                                                        <td> {{ $row->created_at }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <th scope="row" colspan="10">No Data To List . . . </th>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                            {!! $all_comments->links() !!}
                                        </div>
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

<script type="text/javascript">
var values = $('#tags option[selected="true"]').map(function() {
    return $(this).val();
}).get();

// you have no need of .trigger("change") if you dont want to trigger an event
$('#tags').select2({
    placeholder: "Please select"
});
$(document).ready(function() {
    $('#quali_level_id').on('change', function(e) {
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
                var selectedLocationId = <?php echo json_encode($candidate->quali_id); ?>;
                $('#quali_id').html('<option value="">Select Qualification</option>');
                $.each(result.qualification, function(key, value) {
                    var selectedAttribute = value.id == selectedLocationId ? 'selected' : '';
                    $("#quali_id").append('<option value="' + value.id + '"'  + selectedAttribute +'>' + 
                    value.qualification + '</option>');
                });
            }
        });
    });
});
</script>
<!-- Submit the form without any validation -->
<script>
$(document).ready(function() {
    $('#submitWithoutValidationButton').on('click', function() {
        // $('#myform').submit();

        var commentValue = $('#comments').val(); // Assuming your comment input has an ID of 'commentInput'
        if (commentValue.trim() !== '') {
            $('#myform').submit();
        } else {
            alert('Please enter a comment.'); // Display an alert if the comment field is empty
            return false;
        }

    });
});
</script>
@endsection