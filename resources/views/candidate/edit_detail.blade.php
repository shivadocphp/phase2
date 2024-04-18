@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Candidate : {{ $name }} <a href="{{ route('edit.candidate',$id)}}"><i class="fa fa-edit" style="color: green;font-size: medium"></i></a>
    </h3>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="{{ route('edit_detail.candidate',[$id,$name]) }}" class="btn btn-med btn-new-full " style="color: orangered;"> Processed Details</a><a href="#" class="btn btn-med btn-new-full" title="Add New" style="color: orangered;" data-toggle="modal" data-target="#addModal">
                    <i class="fa fa-plus"></i></a>
            </div>
        </div>
        <div class="col-md-4" align="right">
            <a href="{{ route('all.candidate') }}" class="btn btn-med btn-primary">View Candidates</a>
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
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('store.candidatedetail') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-striped" width="100%">
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class='col-md-6'>
                                                <input type="hidden" name="candidate_id" value="{{$id}}">
                                                <input type="hidden" name="candidate_name" value="{{$name}}">
                                                Processed for Requirement
                                                <font color="red" size="3">*</font>
                                                <select name="requirement_id" class="form-control" id="requirement_id" required>
                                                    <option value="">--Select Requirement--</option>
                                                    @foreach($requirement as $r)
                                                    <option value="{{ $r->id }}">
                                                        {{ $r->designation ." (".$r->total_position."), ".$r->address.",".$r->city.",".$r->state}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('requirement_id')
                                                <span class="text-danger">{{ $message  }}</span>
                                                @enderror
                                            </div>
                                            <div class='col-md-6'>
                                                Processed for Client
                                                <font color="red" size="3">*</font>
                                                <select name="client_id" class="form-control" id="client_id"></select>
                                                @error('client_id')
                                                <span class="text-danger">{{ $message  }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class='col-md-3'>
                                                Interview Mode
                                                <font color="red" size="3">*</font>
                                                <select name="interview_mode" class="form-control" multiple required>
                                                    <option value="">--Select--</option>
                                                    <option value="Telephonic">Telephonic</option>
                                                    <option value="F2F">F2F</option>
                                                    <option value="Skype">Skype</option>
                                                    <option value="Zoom">Zoom</option>
                                                    <option value="WebEx">WebEx</option>
                                                    <option value="Hangout">Hangout</option>
                                                    <option value="Google Meet">Google Meet</option>

                                                </select>
                                                @error('interview_mode')
                                                <span class="text-danger">{{ $message  }}</span>
                                                @enderror
                                            </div>
                                            <div class='col-md-3'>
                                                Available Time

                                                <input type="time" name="available_time" class="form-control">
                                                @error('available_time')
                                                <span class="text-danger">{{ $message  }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-3">
                                                Call back date
                                                <input type="date" name="call_back_date" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                Call back time
                                                <input type="time" name="call_back_time" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                Call back status
                                                <select name="call_back_status" class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="Call Back">Call Back</option>
                                                    <option value="Close">Close</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                Call Status <font color="red" size="3">*</font>
                                                <select name="call_status" class="form-control" required>
                                                    <option value="">--select--</option>
                                                    <option value="Connected / Waiting for response">Connected /
                                                        Waiting for
                                                        response
                                                    </option>
                                                    <option value="RNR / Not connected">RNR / Not connected</option>
                                                    <option value="Not Interested">Not Interested</option>
                                                    <option value="Interested / Processed">Interested / Processed
                                                    </option>
                                                    <option value="Processed / Not Responding">Processed / Not
                                                        Responding
                                                    </option>
                                                    </option>
                                                    <option value="Interview Scheduled / Shortlisted">Interview
                                                        Scheduled /
                                                        Shortlisted
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                Requirement Status <font color="red" size="3">*</font>
                                                <select name="requirement_status" class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="RNR">RNR</option>
                                                    <option value="Not Interested">Not Interested</option>
                                                    <option value="Rejected/Offer Rejected">Rejected/Offer
                                                        Rejected
                                                    </option>
                                                    <option value="Offered/Joined">Offered/Joined</option>
                                                    <option value="Processed">Processed</option>
                                                    <option value="Shortlisted">Shortlisted</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                Comments
                                                <textarea name="comments" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('update.candidatedetail') }}" method="POST">
                    {{method_field('patch')}}
                    @csrf
                    <div class="modal-body">
                        <table class="table table-striped" width="100%">
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class='col-md-6'>
                                                <input type="hidden" name="candidate_id" value="{{$id}}">
                                                <input type="hidden" name="candidate_name" value="{{$name}}">
                                                <input type="hidden" name="id" id="myid">
                                                Processed for Requirement
                                                <font color="red" size="3">*</font>
                                                <input type="text" name="requirement_id" id="myrequirement" disabled class="form-control">
                                                @error('requirement_id')
                                                <span class="text-danger">{{ $message  }}</span>
                                                @enderror
                                            </div>
                                            <div class='col-md-6'>
                                                Processed for Client
                                                <font color="red" size="3">*</font>
                                                <input type="text" name="client_id" id="myclient" disabled class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class='col-md-3'>
                                                Interview Mode
                                                <font color="red" size="3">*</font>
                                                <select name="interview_mode" class="form-control" required multiple id="myinterview_mode">
                                                    <option value="">--Select--</option>
                                                    <option value="Telephonic">Telephonic</option>
                                                    <option value="F2F">F2F</option>
                                                    <option value="Skype">Skype</option>
                                                    <option value="Zoom">Zoom</option>
                                                    <option value="WebEx">WebEx</option>
                                                    <option value="Hangout">Hangout</option>
                                                    <option value="Google Meet">Google Meet</option>

                                                </select>
                                                @error('interview_mode')
                                                <span class="text-danger">{{ $message  }}</span>
                                                @enderror
                                            </div>
                                            <div class='col-md-3'>
                                                Available Time

                                                <input type="time" name="available_time" id="myavailable_time" class="form-control" required>
                                                @error('available_time')
                                                <span class="text-danger">{{ $message  }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-3">
                                                Call back date
                                                <input type="date" name="call_back_date" id="mycall_back_date" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                Call back time
                                                <input type="time" name="call_back_time" id="mycall_back_time" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                Call back status
                                                <select name="call_back_status" id="mycall_back_status" class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="Call Back">Call Back</option>
                                                    <option value="Close">Close</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                Call Status <font color="red" size="3">*</font>
                                                <select name="call_status" class="form-control" id="mycall_status" required>
                                                    <option value="">--select--</option>
                                                    <option value="Connected / Waiting for response">Connected /
                                                        Waiting for
                                                        response
                                                    </option>
                                                    <option value="RNR / Not connected">RNR / Not connected</option>
                                                    <option value="Not Interested">Not Interested</option>
                                                    <option value="Interested / Processed">Interested / Processed
                                                    </option>
                                                    <option value="Processed / Not Responding">Processed / Not
                                                        Responding
                                                    </option>
                                                    </option>
                                                    <option value="Interview Scheduled / Shortlisted">Interview
                                                        Scheduled /
                                                        Shortlisted
                                                    </option>

                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                Requirement Status <font color="red" size="3">*</font>
                                                <select name="requirement_status" class="form-control" id="myrequirement_status" required>
                                                    <option value="">--Select--</option>
                                                    <option value="RNR">RNR</option>
                                                    <option value="Not Interested">Not Interested</option>
                                                    <option value="Rejected/Offer Rejected">Rejected/Offer
                                                        Rejected
                                                    </option>
                                                    <option value="Offered/Joined">Offered/Joined</option>
                                                    <option value="Processed">Processed</option>
                                                    <option value="Shortlisted">Shortlisted</option>

                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                Joining Date
                                                <input type="date" name="joining_date" id="myjoining_date" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                Invoice Generation Limit
                                                <input type="number" name="invoice_generation_limit" id="invoice_generation_limit" max="120" maxlength="3" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                Comments
                                                <textarea name="comments" class="form-control" id="mycomments"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">View</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modal_body"></div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-14">
            <div class="card">
                <div class="card-body">
                    <?php if (count($candidateDetail) > 0) { ?>
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr align="center">
                                    <th>No.</th>
                                    <th>Requirement</th>
                                    <th>Client</th>
                                    <th>Call Status</th>
                                    <th>Status</th>
                                    <th>Processed By</th>
                                    <th>Comments</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    <?php } else { ?>

                        <center>
                            <h5>NOT PROCESSED FOR ANY REQUIREMENTS</h5>
                        </center>


                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('#editModal').on('show.bs.modal', function(event) {
            // console.log('Modal Opened');
            var button = $(event.relatedTarget) // Button that triggered the modal
            var modal = $(this)

            modal.find('.modal-body #myrequirement').val(button.data('myrequirement'))
            modal.find('.modal-body #myclient').val(button.data('myclient'))
            modal.find('.modal-body #myinterview_mode').val(button.data('myinterview_mode'))
            modal.find('.modal-body #myavailable_time').val(button.data('myavailable_time'))
            modal.find('.modal-body #mycall_back_date').val(button.data('mycall_back_date'))
            modal.find('.modal-body #mycall_back_time').val(button.data('mycall_back_time'))
            modal.find('.modal-body #mycall_back_status').val(button.data('mycall_back_status'))
            modal.find('.modal-body #mycall_status').val(button.data('mycall_status'))
            modal.find('.modal-body #mycomments').val(button.data('mycomments'))
            modal.find('.modal-body #myid').val(button.data('myid'))
            modal.find('.modal-body #myrequirement_status').val(button.data('myrequirement_status'))
            modal.find('.modal-body #myjoining_date').val(button.data('myjoining_date'))


        })
        $('#showModal').on('show.bs.modal', function(event) {
            // console.log('Modal Opened');
            var button = $(event.relatedTarget) // Button that triggered the modal
            var requirement = button.data('myrequirement')
            var client = button.data('myclient')
            var interview_mode = button.data('myinterview_mode')
            var available_time = button.data('myavailable_time')
            var call_back_date = button.data('mycall_back_date')
            var call_back_time = button.data('mycall_back_time')
            var call_back_status = button.data('mycall_back_status')
            var call_status = button.data('mycall_status')
            var comments = button.data('mycomments')
            var status = button.data('myrequirement_status')
            var modal = $(this)


            var str =
                "<div class=row><div class=col-md-3>Requirement </div><div class=col-md-1> : </div><div class=col-md-8>" +
                button.data('myrequirement') + "</div></div>" +
                "<div class=row><div class=col-md-3>Client </div><div class=col-md-1>:</div><div class=col-md-8>" +
                button.data('myclient') + "</div></div>" +
                "<div class=row><div class=col-md-3>Interview Mode </div><div class=col-md-1> : </div><div class=col-md-8>" +
                button.data('myinterview_mode') + "</div></div>" +
                "<div class=row><div class=col-md-3>Available Time </div><div class=col-md-1> : </div><div class=col-md-8>" +
                button.data('myavailable_time') + "</div></div>" +
                "<div class=row><div class=col-md-3>Call Back Date </div><div class=col-md-1> : </div><div class=col-md-8>" +
                button.data('mycall_back_date') + "</div></div>" +
                "<div class=row><div class=col-md-3>Call Back Time </div><div class=col-md-1> : </div><div class=col-md-8>" +
                button.data('mycall_back_time') + "</div></div>" +
                "<div class=row><div class=col-md-3>Call Back Status </div><div class=col-md-1> : </div><div class=col-md-8>" +
                button.data('mycall_back_status') + "</div></div>" +
                "<div class=row><div class=col-md-3>Call Status </div><div class=col-md-1> : </div><div class=col-md-8>" +
                button.data('mycall_status') + "</div></div>" +
                "<div class=row><div class=col-md-3>Requirement Status </div><div class=col-md-1> : </div><div class=col-md-8>" +
                status + "</div></div>" +
                "<div class=row><div class=col-md-3>Comments </div><div class=col-md-1> : </div><div class=col-md-8>" +
                comments + "</div></div>";
            $("#modal_body").html(str);


        })
        $(function() {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                deferRender: true,
                ajax: "{{ route('processed.list',[$id,'edit']) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'requirement',
                        name: 'requirement'
                    },
                    {
                        data: 'client',
                        name: 'client'
                    },
                    {
                        data: 'call_status',
                        name: 'call_status'
                    },
                    {
                        data: 'requirement_status',
                        name: 'requirement_status'
                    },
                    {
                        data: 'processed_by',
                        name: 'processed_by'
                    },
                    {
                        data: 'comments',
                        name: 'comments'
                    },
                    //  { data:'added_by',name:'added_by'},


                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });

        });

        $(document).ready(function() {
            $('#requirement_id').on('change', function() {

                var idlevel = this.value;
                $("#client_id").html('');
                $.ajax({
                    url: "{{url('api/fetch-client')}}",
                    type: "POST",
                    data: {
                        requirement_id: idlevel,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#client_id').html('');
                        $.each(result.client, function(key, value) {
                            $("#client_id").append('<option value="' + value
                                .id + '">' + value.client_code + ' - ' + value
                                .company_name + '</option>');
                        });
                        //$('#city-dd').html('<option value="">Select City</option>');
                    }
                });
            });

        });
    </script>
    <style>
        @media screen and (min-width: 676px) {
            .modal-dialog {
                max-width: 1000px;
                /* New width for default modal */
            }
        }
    </style>

    @endsection