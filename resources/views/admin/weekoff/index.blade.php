@extends('admin.admin_master')
@section('admin')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-multidatespicker/1.6.6/jquery-ui.multidatespicker.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-multidatespicker/1.6.6/jquery-ui.multidatespicker.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>


<div class="page-header">
    <h3 class="page-title">Backend/Week Off </h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                @if(session('success'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="card-body">
                    <table class="table" id="holidayTable">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Emp Code</th>
                                <th scope="col">Weekoff On</th>
                                <th scope="col">Type</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $i = 1; ?>
                            @foreach($holiday as $h)
                            <tr>
                                <td>{{ $holiday->firstItem()+$loop->index }}</td>
                                <td>{{ $h->emp_code }}</td>
                                <td>{{ $h->date }}</td>
                                <td>{{ $h->type }}</td>
                                <!-- <td>
                                    <a href="{{route('destroy.weekoff', $h->id) }}" title="Delete"><i class="fa fa-trash" style="color: red"></i></a>
                                </td> -->
                                <td>
                                    <a href="#" onclick="deleteHoliday('{{ route('destroy.weekoff', $h->id) }}')" title="Delete">
                                        <i class="fa fa-trash" style="color: red"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $holiday->links('pagination::bootstrap-4')  }}
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <span>ADD Week Off</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('store.weekoff') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="col-md-6">
                                Select Employee
                                <font color="red" size="3">*</font>
                                <div class="form-group">
                                    @foreach($employees as $emp)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="employee_{{ $emp->id }}" name="employee[]" value="{{ $emp->emp_code }}">
                                        <label class="form-check-label" for="employee_{{ $emp->id }}">
                                            {{ $emp->emp_code }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label> Week off on</label>
                            </div>
                            <div class="container">
                                <input id="date" type="text" name='date' />
                            </div>
                        </div>
                        <input type="hidden" name='WO' value="wo" />
                        <div class="row">
                            <div class="col" align="center">
                                <button type="submit" class="btn btn-primary" id="submit_button">Enter</button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>


</div>
<script src="{{asset('js/app.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#holidayTable').DataTable();
    });
</script>

<script>
    $('#date').multiDatesPicker({
        dateFormat: "yy-mm-dd"
    });
</script>

<script>
    function deleteHoliday(url) {
        if (confirm('Are you sure you want to delete this record?')) {
            window.location.href = url;
        }
    }
</script>
@endsection