@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> {{ $allocates->client_code ." - ". $allocates->company_name}}
    </h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('allocate_task.allocation',$id) }}" class="btn btn-med btn-new-full" disabled
                style="color: orangered;">Allocate Requirements</a>
            <a href="{{ route('list_task.allocation',$id) }}" class="btn btn-med btn-primary">List</a>

        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.allocation') }}" class="btn btn-med btn-primary">View
                Alocation</a>
        </div>
    </div>
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

        <div class="col-md-14">
            <div class="card-body">
                <div class="tabset">
                    <!-- Tab 1 -->
                    <input type="radio" name="tabset" id="tab1" aria-controls="oneone" checked>
                    <label for="tab1">One to One</label>
                    <!-- Tab 2 -->
                    <input type="radio" name="tabset" id="tab2" aria-controls="manyone"  @if(session('tab')=="tab2") checked  @endif>
                    <label for="tab2">Many to One</label>
                    <!-- Tab 3 -->
                    <input type="radio" name="tabset" id="tab3" aria-controls="onemany" @if(session('tab')=="tab3") checked  @endif>
                    <label for="tab3">One to Many</label>

                    <div class="tab-panels">
                        
                        <section id="oneone" class="tab-panel">
                            <form action="{{ route('store.taskallocation',1) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" name="allocation_id" value="{{$id}}">
                                            <select name="requirement_id" id="requirement_id" required class="form-control">
                                                <option value="">--Select Requirement--</option>
                                                @foreach($requirements as $r)
                                                <option value="{{ $r->id }}" data-rel="{{ $r->total_position }}" rel="{{ $r->total_position }}">
                                                    {{ $r->designation."(". $r->total_position .")".",".$r->address.",".$r->city.",".$r->state }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="employee_id" required class="form-control">
                                                <option value="">--Select Team Member--</option>
                                                @foreach($team_mem as $tm)
                                                <option value="{{ $tm->id }}">
                                                    {{ $tm->emp_code ." - ".$tm->firstname." ".$tm->middlename." ".$tm->lastname }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="total_position" id="total_position" value="" required>
                                        <div class="col-md-2">
                                            <input type="number" name="allocated_no" class="form-control" required placeholder="Allocated No.">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-primary btn-sm" value="Allocate"
                                                title="Allocate"><i class="fa fa-save"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>

                        <section id="manyone" class="tab-panel">
                            <form action="{{ route('store.taskallocation',2) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">Requirement</div>
                                        <div class="col-md-6">
                                            <input type="hidden" name="allocation_id" value="{{$id}}">
                                            <select name="requirement_id[]" id="tags" select2 select2-hidden-accessible
                                                multiple="multiple" style="width: 600px" class="form-control">

                                                @foreach($requirements as $r)
                                                <option value="{{ $r->id }}">
                                                    {{ $r->designation."(". $r->total_position .")".",".$r->address.",".$r->city.",".$r->state }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">Employee</div>
                                        <div class="col-md-6">
                                            <select name="employee_id" required class="form-control">
                                                <option value="">--Select Team Member--</option>
                                                @foreach($team_mem as $tm)
                                                <option value="{{ $tm->id }}">
                                                    {{ $tm->emp_code ." - ".$tm->firstname." ".$tm->middlename." ".$tm->lastname }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">Allocated no</div>
                                        <div class="col-md-6">
                                            <input type="number" name="allocated_no" class="form-control" required
                                                placeholder="Allocated No.">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <center>
                                                <button type="submit" class="btn btn-primary btn-md" value="Allocate"
                                                    title="Allocate">SAVE</i>
                                                </button>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>

                        <section id="onemany" class="tab-panel">
                            <form action="{{ route('store.taskallocation',3) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">Requirement</div>
                                        <div class="col-md-6">
                                            <input type="hidden" name="allocation_id" value="{{$id}}">
                                            <select name="requirement_id" class="form-control" style="width: 600px">

                                                @foreach($requirements as $r)
                                                <option value="{{ $r->id }}">
                                                    {{ $r->designation."(". $r->total_position .")".",".$r->address.",".$r->city.",".$r->state }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">Employee</div>
                                        <div class="col-md-6">
                                            <select name="employee_id[]" id="employees" select2
                                                select2-hidden-accessible multiple="multiple" style="width: 600px"
                                                class="form-control">
                                                <option value="">--Select Team Member--</option>
                                                @foreach($team_mem as $tm)
                                                <option value="{{ $tm->id }}">
                                                    {{ $tm->emp_code ." - ".$tm->firstname." ".$tm->middlename." ".$tm->lastname }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">Allocated no</div>
                                        <div class="col-md-6">
                                            <input type="number" name="allocated_no" class="form-control" required
                                                placeholder="Allocated No.">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <center>
                                                <button type="submit" class="btn btn-primary btn-md" value="Allocate"
                                                    title="Allocate">SAVE</i>
                                                </button>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>

                    </div>
                </div>
            </div>
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
    placeholder: "Please select Requirement"
});

var values = $('#employees option[selected="true"]').map(function() {
    return $(this).val();
}).get();

// you have no need of .trigger("change") if you dont want to trigger an event
$('#employees').select2({
    placeholder: "Please select Employees"
});
</script>

<!-- to get the total position -->
<script>
$(document).ready(function() {
    $('#requirement_id').on('change', function() {
        var relValue = $(this).find('option:selected').data('rel');
        $('#total_position').val(relValue);
    });
});
</script>


@endsection