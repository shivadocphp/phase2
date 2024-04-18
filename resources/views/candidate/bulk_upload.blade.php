@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Candidates: Bulk Upload<a href="{{ URL::to('/')}}/blank_excel_candidates.csv"
            target="_blank" download>
            <input type="button" class="btn btn-new" value="Download sample excel file"></a></h3>
    <div class="row">
        <a href="{{ route('create.candidate') }}" class="btn btn-new">Add</a>

    </div>
</div>
<div class="container">
    <div class="row">
        <div class="card">
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
        @if(session('msg'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>{{ session('msg') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
            <div class="card-body">
                <form method="post" action="{{route('upload.candidate')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2" style="width:25%;text-align:end;">
                                Proccessed for Client
                            </div>
                            <div class="col-md-6" style="width:75%;">
                                <select class="form-control" name="client_id" id="client_id" required>
                                    <option value="">--Select Client--</option>
                                    @foreach($clients as $client)
                                    <option value="<?= $client->id ?>"><?= $client->client_code ?> -
                                        <?= $client->company_name ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2" style="width:25%;text-align:end">
                                Proccessed for Requirement
                            </div>
                            <div class="col-md-6" style="width:75%">
                                <select class="form-control" id="requirement_id" name="requirement_id" required>
                                    <option value="">--Select Requirement--</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2" style="width:25%;text-align:end">
                                Upload CSV file
                            </div>
                            <div class="col-md-6" style="width:75%">
                                <input type="file" required name="bulk" class="form-control" accept=".csv">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-8" style="width:100%;text-align:center;">
                            <input type="submit" name="upload" class="btn btn-new" value="Upload">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#client_id').on('change', function() {
        var idlevel = this.value;
        $("#requirement_id").html('');
        $.ajax({
            url: "{{url('api/fetch-clientRequirement')}}",
            type: "POST",
            data: {
                client_id: idlevel,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $('#requirement_id').html('<option value="">Select Requirement</option>');
                $.each(result.requirement, function(key, value) {
                    $("#requirement_id").append('<option value="' + value
                        .id + '">' + value.designation + '(' + value
                        .total_position + ') -' + value.address + ', ' + value
                        .city + ', ' + value.state + '</option>');
                });
                //$('#city-dd').html('<option value="">Select City</option>');
            }
        });
    });

});
</script>

@endsection('admin')