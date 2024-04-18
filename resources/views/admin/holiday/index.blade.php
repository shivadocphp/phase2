
@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/Holidays </h3>

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
                    <div class="card-header">
                        <span>HOLIDAYS</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Holiday On</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Edit</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            @foreach($holiday as $h)
                                <tr>
                                    <td>{{ $holiday->firstItem()+$loop->index }}</td>
                                    <td>{{ $h->holiday_date }}</td>
                                    <td>{{ $h->holiday_reason }}</td>
                                    <td><a href="" class="" data-myreason="{{ $h->holiday_reason }}" data-myholiday="{{ $h->holiday_date }}" data-myid="{{ $h->id }}" data-toggle="modal" data-target="#edit" ><i class="fa fa-edit green" ></i></a>
                                        <a data-endpoint="{{ route('holiday.history',$h->id)}}" data-async="true" data-toggle="tooltip"  data-original-title="View Edit History" data-target="modal-xl" data-cache="false" data-check-link="view" class=""><i class="fa fa-eye black"></i></a>
                                    <a href="#" onclick="deleteHoliday('{{ route('destroy.holiday', $h->id) }}')" title="Delete">
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
                        <span>ADD HOLIDAY</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.holiday') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div>
                                    <label> Holiday on</label></div>
                                <div>
                                    <input type="date" name="holiday_date" class="form-control">
                                </div>
                                @error('holiday_date')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div>
                                    <label> Holiday Reason</label></div>
                                <div>
                                    <input type="text" name="holiday_reason" class="form-control">
                                </div>
                                @error('holiday_reason')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col" align="center">
                                    <button type="submit" class="btn btn-primary">Enter</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h4 class="modal-title" id="myModalLabel">Edit Holiday</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form  action="{{route('update.holiday')}}"  method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input type="hidden" name="id" value="" id="myid">
                                <div class="form-group">
                                    <label>Holiday on</label>
                                    <input type="date" name="holiday_date" class="form-control" id="myholiday_date" >
                                </div>
                                <div class="form-group">
                                    <label>Holiday reason</label>
                                    <input type="text" name="holiday_reason" class="form-control" id="myholiday_reason" >
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-xl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script src="{{asset('js/app.js')}}"></script>
    <script>
        $('#edit').on('show.bs.modal', function (event) {
            // console.log('Modal Opened');
            var button = $(event.relatedTarget) // Button that triggered the modal
            var holiday = button.data('myholiday')
            var reason = button.data('myreason')
            var id=button.data('myid')
            var modal = $(this)

            modal.find('.modal-body #myholiday_date').val(holiday)
            modal.find('.modal-body #myholiday_reason').val(reason)
            modal.find('.modal-body #myid').val(id)
        })
    </script>
    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
      
            //check Lock
            $(document).on('click', 'a[data-async="true"]', function (e) {
                e.preventDefault();
                var self    = $(this),
                    url     = self.data('endpoint'),
                    target  = self.data('target'),
                    cache   = self.data('cache'),
                    edit_id = self.data('id'),
                    check_link  = self.data('check-link');
                if(check_link=='view'){
                    $.ajax({
                        url     : url,
                        cache   : cache,
                        success : function(result){
                            if (target !== 'undefined'){
                                $('#'+target+' .modal-content').html(result);
                                $('#'+target).modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                            }
                        },
                        error : function(error){
                            console.log(error);
                        },
                    });
                }
            });
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


