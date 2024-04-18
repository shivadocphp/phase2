@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Requirement: Conditions </h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('edit.requirement',$id) }}" class="btn btn-med btn-primary">Position Details</></a>
            <a href="{{ route('edit.requirement_con',$id) }}" class="btn btn-med btn-new-full"
                style="color: orangered;">Conditions</a>


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

    </div>
    <div class="row">
        <div class="col-md-14">
            <form action="{{ route('update.requirement') }}" method="POST">
                {{method_field('patch')}}
                @csrf
                @csrf
                <table class="table table-striped">
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="row">
                                    <input type="hidden" value="{{ $id }}" name="requirement_id">
                                    <div class='col-md-6'>
                                        Targeted Companies
                                        <textarea class="form-control" name="targeted_companies">{{$require->targeted_companies}}
                                        </textarea>
                                        @error('targeted_companies')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-6'>
                                        Non patch Companies
                                        <textarea class="form-control" name="nonpatch_companies">{{$require->nonpatch_companies}}
                                        </textarea>
                                        @error('nonpatch_companies')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-2'>
                                        Interview Rounds <font color="red" size="3">*</font>
                                        <select class="form-control" name="interview_rounds" required>
                                            <option value="">--Select --</option>
                                            @for($i=1;$i<=6;$i++) <option value="{{$i}}" <?php  if ($require->interview_rounds == $i) {
                                                        echo "selected";
                                                    }?>>{{$i}}</option>
                                                @endfor
                                        </select>
                                        @error('interview_rounds')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        How long the position is open? <font color="red" size="3">*</font>
                                        <input type="date" name="open_till" class="form-control"
                                            value="{{ $require->open_till }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        No. of consultants working?
                                        <input type="number" name="no_consultant" class="form-control" placeholder=""
                                            value="{{ $require->no_consultant }}">
                                    </div>
                                    <div class='col-md-2'>
                                        Is there any bond? <font color="red" size="3">*</font>
                                        <select class="form-control" name="bond" id="bond" required>
                                            <option value="">--Select--</option>
                                            <option value="Y" <?php if ($require->bond == "Y") {
                                                    echo "selected";
                                                }?>>Yes
                                            </option>
                                            <option value="N" <?php if ($require->bond == "N") {
                                                    echo "selected";
                                                }?>>No
                                            </option>
                                        </select>
                                    </div>
                                    <div class='col-md-2' id="bond_display" style="display: none;">
                                        Years of Bond <font color="red" size="3">*</font>
                                        <select class="form-control" name="bond_years" id="bond_years" >
                                            <option value="">--Select--</option>
                                            @for($i=0;$i<=3;$i++) 
                                            <option value="{{$i}}" <?php if ($require->bond_years == $i) {
                                                        echo "selected";
                                                    }?>>{{$i}}
                                            </option>
                                            @endfor

                                        </select>
                                    </div>
                                    <div class="col-md-2">TAT
                                        <input type="text" name="tat" class="form-control" value="{{ $require->tat }}">
                                    </div>

                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12" align="center">

                                        <button type="submit" class="btn btn-primary" value="save_conditon" name="save"
                                            title="Save">SAVE
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
<script type="text/javascript">
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
                //$('#city-dd').html('<option value="">Select City</option>');
            }
        });
    });

});
</script>

<!-- for bond -->
<script>
    $(document).ready(function() {
        $('#bond').change(function() {
            var bondValue = $(this).val();
            if (bondValue === 'Y') {
                $('#bond_display').show();
                $('#bond_years').prop('required', true);
            } else {
                $('#bond_display').hide();
                $("#bond_years").removeAttr('required');
            }
        });
    });
</script>
@endsection