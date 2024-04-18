@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title"> Add Employees </h3>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <a href="" class="btn btn-med btn-warning" disabled >Personal
                        Details</a>
                <a href="{{ route('create.emp_official') }}" class="btn btn-med btn-primary" disabled style="color: orangered;"><font colour=orange>Employment Details</font></a>
                <a href="" class="btn btn-med btn-warning">Bank Details</a>

            </div>
            <div class="col-md-4" align="right"><a href="{{ route('all.employee') }}"
                                                   class="btn btn-med btn-info">View Employees</a>
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
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>{{ session('error') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
        </div>
        <div class="col-md-12">
            <form action="{{ route('store.employee_official') }}" method="POST">
                @csrf
                <table class="table table-striped">

                    <tr>
                        <td>

                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-6'>
                                        Employee Name
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="emp_name1"  value="{{ $emp_name }}" disabled>
                                        <input type="hidden" class="form-control" name="emp_name"  value="{{ $emp_name }}">
                                    </div>
                                    <div class='col-md-6'>

                                        <input type="hidden" name="emp_id" value="{{ $emp_id }}">
                                    </div>
                                </div></div>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-2'>
                                        Employement Mode
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="employementmode_id" id="mode_id" onclick="ShowHideDiv(this)">
                                            <option>--Select--</option>
                                            @foreach($emp_modes as $emp_mode)
                                                <option value="{{$emp_mode->id}}">{{ $emp_mode->employementmode }}</option>
                                            @endforeach
                                        </select>
                                        @error('employementmode_id')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>

                                    <div class='col-md-4' id="client1" style="display: block">
                                        Client
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="client_id" id="client_id">
                                            <option value="">--Select--</option>
                                            @foreach($clients as $c)
                                                <option value="{{$c->id}}">{{ $c->client_code." - ".$c->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class='col-md-3' id="client2" style="display: block">
                                        Deployed Location
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" name="location" id="location" required>
                                            <option value="">--Select location--</option>


                                        </select>
                                        @error('location')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-4'>
                                        Official Email ID
                                        <font color="red" size="3">*</font>
                                        <input type="email" class="form-control" name="official_emailid">
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
                                                <option value="{{$dept->id}}">{{ $dept->department }}</option>
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
                                                <option value="{{$desig->id}}">{{ $desig->designation }}</option>
                                            @endforeach

                                        </select>
                                        @error('designation_id')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>

                                    <div class='col-md-2'>
                                        ESIC No.

                                        <input type="number" class="form-control" name="esic_no" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "10">
                                    </div>
                                    <div class='col-md-2'>
                                        PF No

                                        <input type="number" class="form-control" name="pf_no" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "10">
                                    </div>
                                    <div class='col-md-2'>
                                        UAN No.
                                        <input type="number" class="form-control" name="uan_no" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "12">
                                    </div>
                                    <div class='col-md-2'>
                                        BGV
                                        <select class="form-control" name="bgv">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="row">
                                    <div class='col-md-2'>
                                        Date Of Joining

                                        <input type="date" class="form-control" name="joining_date">
                                        @error('joining_date')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Date of Relieving

                                        <input type="date" class="form-control" name="relieving_date">
                                    </div>

                                    <div class='col-md-8'>
                                        Comments
                                        <textarea class="form-control" name="comments" rows="2" cols="16"></textarea>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="btn btn-primary" name="save_draft" title="Save and add remaining details later" value="save_draft">SAVE AS DRAFT</button>
                                        <button type="submit" class="btn btn-primary" name="save_draft" title="Save and move to next step" value="save_draft_next">NEXT</button></div>
                                   <!-- <div class="col-md-6" align="right">  <a href=" route('create.emp_bank')" class="btn btn-primary" >Next &raquo;</a>-->
                                    </div>
                                </div>
                            </div>
                        </td></tr>
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
        }

        $(document).ready(function () {


            $('#client_id').on('change', function () {

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
                    success: function (result) {
                        $('#location').html('<option value="">Select location</option>');
                        $.each(result.location, function (key, value) {
                            $("#location").append('<option value="' + value
                                .id + '">' + value.address + ',' + value.state + ',' + value.city + '</option>');
                        });
                        //$('#city-dd').html('<option value="">Select City</option>');
                    }
                });
            });

        });
    </script>
@endsection
