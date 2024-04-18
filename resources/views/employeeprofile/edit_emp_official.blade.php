@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Edit Employees </h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('edit.employee_personal',[$id]) }}" class="btn btn-med btn-primary">
                <font colour=white>Personal Details</font>
            </a>
            <a href="{{ route('edit.emp_official',[$id]) }}" class="btn btn-med btn-new-full" disabled=""
                style="color: orangered;">
                <font colour=white>Employment Details</font>
            </a>
            <a href="{{ route('edit.emp_bank',[$id]) }}" class="btn btn-med btn-primary">
                <font colour=white>Bank
                    Details</font>
            </a>
            <a href="{{ route('edit.emp_pip',[$id]) }}" class="btn btn-med btn-primary">
                <font colour=white>PIP
                    Details</font>
            </a>
            <a href="{{ route('edit.emp_salary',[$id]) }}" class="btn btn-med btn-primary">
                <font colour=white>Salary</font>
            </a>
        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.employee') }}" class="btn btn-med btn-primary">View
                Employees</a>
            <a href="{{ route('create.employee') }}" class="btn btn-med btn-primary">Add Employees</a>
        </div>
    </div>
    <div>@if(session('success'))
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
        <form action="{{ route('update.emp_official') }}" method="POST">
            {{method_field('patch')}}
            @csrf
            <table class="table table-striped">

                <tr>
                    <td>

                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-6'>
                                    Employee Name
                                    <font color="red" size="3">*</font>
                                    <input type="text" class="form-control" name="empname" value="{{ $emp_name }}"
                                        disabled>
                                </div>
                                <div class='col-md-6'>
                                    Employee Code
                                    <font color="red" size="3">*</font>
                                    <input type="text" class="form-control" name="emp_code" value="{{$emp_code}}"
                                        disabled>
                                    <input type="hidden" class="form-control" name="emp_id" value="{{ $id }}">
                                    <input type="hidden" class="form-control" name="emp_code" value="{{ $emp_code }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-2'>
                                    Employement Mode
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="employementmode_id" id="mode_id"
                                        onclick="ShowHideDiv(this)">
                                        <option>--Select--</option>
                                        @foreach($emp_modes as $emp_mode)
                                        <option value="{{$emp_mode->id}}" <?php if ($o_exists == 1) {
                                                    if ($emp_offcial->employementmode_id == $emp_mode->id) echo "selected";
                                                }?>>{{ $emp_mode->employementmode }}</option>
                                        @endforeach
                                    </select>
                                    @error('employementmode_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>

                                <div class='col-md-4' id="client1" style="display: block">
                                    Client
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="client_id" id="client_id" required>
                                        <option value="">--Select--</option>
                                        @foreach($clients as $c)
                                        <option value="{{$c->id}}" <?php {
                                                    if ($emp_offcial->client_id == $c->id) echo "selected";
                                                }?>>{{ $c->client_code." - ".$c->company_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class='col-md-3' id="client2" style="display: block">
                                    Deployed Location
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="location" id="location" required>
                                        <option value="">--Select location--</option>
                                        <?php if ($client_location != null) { ?>

                                        @foreach($client_location as $cl)
                                        <option value="{{ $cl->id }}" rel="{{ $cl->id }}"
                                            
                                            <?php 
                                                    if ($emp_offcial->location == $cl->id) {echo 'selected';}?> >
                                                    {{ $cl->address }}, {{ $cl->city }}, {{ $cl->state }}
                                        </option>
                                        @endforeach
                                        <?php } ?>

                                    </select>
                                    
                                    @error('location')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-3'>
                                    Official Email ID
                                    <font color="red" size="3">*</font>
                                    <input type="email" class="form-control" name="official_emailid"
                                        <?php if($o_exists == 1){ ?> value="{{ $emp_offcial-> official_emailid}}"
                                        <?php }?>>
                                    @error('official_emailid')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-2'>
                                    Department
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="department_id">
                                        <option>--Select Department--</option>
                                        @foreach($depts as $dept)
                                        <option value="{{$dept->id}}" <?php if ($o_exists == 1) {
                                                    if ($emp_offcial->department_id == $dept->id) echo "selected";
                                                }?>>{{ $dept->department }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    Designation
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="designation_id">
                                        <option>--Select Designation--</option>
                                        @foreach($desigs as $desig)
                                        <option value="{{$desig->id}}" <?php if ($o_exists == 1) {
                                                    if ($emp_offcial->designation_id == $desig->id) echo "selected";
                                                }?>>{{ $desig->designation }}</option>
                                        @endforeach

                                    </select>
                                    @error('designation_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>

                                <div class='col-md-2'>
                                    ESIC No.

                                    <input type="number" class="form-control" name="esic_no"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        maxlength="10" <?php if($o_exists == 1){ ?> value="{{ $emp_offcial-> esic_no}}"
                                        <?php }?>>
                                </div>
                                <div class='col-md-2'>
                                    PF No

                                    <input type="number" class="form-control" name="pf_no"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        maxlength="10" <?php if($o_exists == 1){ ?> value="{{ $emp_offcial-> pf_no}}"
                                        <?php }?>>
                                </div>
                                <div class='col-md-2'>
                                    UAN No.
                                    <input type="number" class="form-control" name="uan_no"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        maxlength="12" <?php if($o_exists == 1){ ?> value="{{ $emp_offcial-> uan_no}}"
                                        <?php } ?>>
                                </div>
                                <div class='col-md-2'>
                                    BGV
                                    <select class="form-control" name="bgv">
                                        <option value="No" <?php if ($o_exists == 1) {
                                                if ($emp_offcial->bgv == "No") echo "selected";
                                            }?>>
                                            No
                                        </option>
                                        <option value="Yes" <?php if ($o_exists == 1) {
                                                if ($emp_offcial->bgv == "Yes") echo "selected";
                                            }?>>
                                            Yes
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">

                            <div class="row">
                                <div class='col-md-2'>
                                    Date Of Joining

                                    <input type="date" class="form-control" name="joining_date"
                                        <?php if($o_exists == 1){ ?>value="{{ $emp_offcial-> joining_date}}" <?php } ?>>
                                    @error('joining_date')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-2'>
                                    Date of Relieving

                                    <input type="date" class="form-control" name="relieving_date"
                                        <?php if($o_exists == 1){ ?> value="{{ $emp_offcial-> relieving_date}}"
                                        <?php } ?>>
                                </div>

                                <div class='col-md-8'>
                                    Comments
                                    <textarea class="form-control" name="comments" rows="2" cols="16"
                                        required=""> <?php if($o_exists == 1){ ?> {{ $emp_offcial-> comments}}<?php } ?></textarea>

                                    @error('comments')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
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
                                                    <td> @isset($row->addedBy) {{ $row->addedBy->name }} @endisset</td>
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

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12" align="center">
                                    <?php if($o_exists == 1){ ?>
                                    <button type="submit" class="btn btn-primary" name="edit_official"
                                        value="update">UPDATE
                                    </button><?php }else{?>
                                    <button type="submit" class="btn btn-primary" name="add_official"
                                        value="insert">SAVE
                                        <?php }?>
                                </div>
                                <!-- <div class="col-md-6" align="right">  <a href=" route('create.emp_bank')" class="btn btn-primary" >Next &raquo;</a>-->
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
    var mode_id = document.getElementById("mode_id");
    var client1 = document.getElementById("client1");
    var client2 = document.getElementById("client2");
    client1.style.display = mode_id.value != "1" ? "block" : "none";
    client2.style.display = mode_id.value != "1" ? "block" : "none";
    
    // added
    var client_id = document.getElementById("client_id");
    var location = document.getElementById("location");
    if(mode_id.value == '1'){
        client_id.removeAttribute("required");
        location.removeAttribute("required");
    }else{
        client_id.setAttribute("required", "required");
        location.setAttribute("required", "required");
    }
    // same_address_display.style.display = same_address_com.checked ? "none" : "block";
}

$(document).ready(function() {


    $('#client_id').on('change', function() {

        // e.preventDefault();
        // var selectedValue = $(this).val();
        // var selectedText = $(this).find("option:selected").text();
        // console.log(idlevel);

        var idlevel = this.value;
        $("#location").html('');
        $.ajax({
            url: "{{url('api/fetch-clientlocation')}}",
            type: "POST",
            data: {
                client_id: idlevel,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                // alert(result);
                var selectedLocationId = <?php echo json_encode($emp_offcial->location); ?>;
                $('#location').html('<option value="">Select location</option>');
                $.each(result.location, function(key, value) {
                    var selectedAttribute = value.id == selectedLocationId ? 'selected' : '';
                    // alert(selectedAttribute);
                    $("#location").append('<option value="' + value.id + '" rel="' + value.id + '" ' + 
                    selectedAttribute + '>' + 
                    value.address + ',' + value.state + ',' + value.city + 
                    '</option>');
                });
            }
        });
    });

});
</script>
@endsection