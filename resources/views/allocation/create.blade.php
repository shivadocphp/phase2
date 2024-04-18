@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Allocate Team </h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('create.allocation') }}" class="btn btn-med btn-new-full" disabled
                style="color: orangered;">Client->team</></a>
        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.allocation') }}" class="btn btn-med btn-primary">View
                Alocation</a>
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
    <div class="col-md-14">
        <form action="{{ route('store.allocation') }}" method="POST">
            @csrf
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-4'>
                                    Client
                                    <font color="red" size="3">*</font>
                                    <select class="form-control" name="client_id" required>
                                        <option value="">--Select Client--</option>
                                        @foreach($client as $cl)
                                        <option value="{{$cl->id}}">{{$cl->client_code ."- ". $cl->company_name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('client')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class='col-md-3'>
                                    Team
                                    <font color="red" size="3">*</font>

                                    <select name="team_id[]" id="team_id" select2 select2-hidden-accessible
                                        multiple="multiple" style="width: 600px" class="form-control">
                                        <option value="">--Select team--</option>
                                        @foreach($team as $t)
                                        <option value="{{$t->id}}">{{$t->team }}</option>
                                        @endforeach
                                    </select>
                                    @error('team_id')
                                    <span class="text-danger">{{ $message  }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button type="submit" class="btn btn-primary" value="save_draft_next"
                                        title="Save and move to next step">Allocate
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
var values = $('#team_id option[selected="true"]').map(function() {
    return $(this).val();
}).get();

// you have no need of .trigger("change") if you dont want to trigger an event
$('#team_id').select2({
    placeholder: "Please select team"
});
</script>
@endsection