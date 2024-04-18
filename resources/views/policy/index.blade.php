@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Company Policies </h3>

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
                    <span>Policies</span>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Policy</th>
                                <th scope="col">Document</th>
                                @if(Illuminate\Support\Facades\Auth::user()->id==1) <th scope="col">Is active?</th>
                                <th scope="col">Edit</th> @endif
                            </tr>
                        </thead>
                        <tbody>

                            <?php $i = 1; ?>
                            @foreach($policies as $policy)
                            <tr>
                                <td>{{ $policies->firstItem()+$loop->index }}</td>
                                <td>{{ $policy->policy }}</td> 
                                <td><a href="{{ asset('storage/'.$policy->document) }}" target="_blank" class="btn-sm btn-success">View</td>
                                @if(Illuminate\Support\Facades\Auth::user()->id==1) <td><?php if ($policy->is_active == 1) { ?><a href="{{route('status.policy',[$policy->id,0])}}" title="Click here to disable this policy">
                                            <i class="fa fa-check" style="color:green"></i></a><?php } else { ?>

                                        <a href="{{route('status.policy',[$policy->id,1])}}" title="Click here to enable this policy"><i class="fa fa-ban" style="color:red"></i></a>
                                    <?php } ?>
                                </td>
                                <td><a href="" data-mypolicy="{{ $policy->policy }}" data-myid="{{ $policy->id }}" data-toggle="modal" data-target="#edit">
                                        <i class="fa fa-edit" style="color:green"></i></a></td>@endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $policies->links('pagination::bootstrap-4')  }}
                </div>
            </div>
        </div>
        @if(Illuminate\Support\Facades\Auth::user()->id==1)
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <span>ADD POLICY</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('store.policy') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="form-group">
                            <div>
                                <label>Policy</label>
                            </div>
                            <div>
                                <input type="text" name="policy" class="form-control" required>

                            </div>
                            @error('policy')
                            <span class="text-danger">{{ $message  }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div>
                                <label>File</label>
                            </div>
                            <div>
                                <input type="File" name="document" class="form-control">
                            </div>
                            @error('policy_document')
                            <span class="text-danger">{{ $message  }}</span>
                            @enderror
                        </div>


                        <div class="row">
                            <div class="col" align="center">
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>

        <!-- EDIT-->
        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title" id="myModalLabel">Edit Policy</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('update.policy')}}" method="POST" enctype="multipart/form-data">
                        {{method_field('patch')}}
                        {{csrf_field()}}
                        <div class="modal-body">
                            <input type="hidden" name="id" value="" id="myid">
                            <div class="form-group">
                                <label>Policy</label>
                                <input type="text" name="policy" class="form-control" id="mypolicy" required>
                            </div>


                            <div class="form-group">
                                <div>
                                    <label>File</label>
                                </div>
                                <div>
                                    <input type="File" name="document" class="form-control">
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @endif
    </div>


</div>





<script src="{{asset('js/app.js')}}"></script>
<script>
    $('#edit').on('show.bs.modal', function(event) {
        // console.log('Modal Opened');
        var button = $(event.relatedTarget) // Button that triggered the modal
        var policy = button.data('mypolicy')

        var id = button.data('myid')
        var modal = $(this)

        modal.find('.modal-body #mypolicy').val(policy)
        modal.find('.modal-body #myid').val(id)

    })
</script>

@endsection('admin')