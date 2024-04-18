@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Edit Candidate</h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('edit.candidate',$id) }}" class="btn btn-med btn-primary" disabled
                style="color: orangered;"> Candidate Basic Details</a>
        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.candidate') }}" class="btn btn-med btn-success">View
                Candidates</a>
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
        <div class="card">
            <table class="table table-striped">
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-3'>
                                    Name : <span>
                                        <h4>{{ strtoupper($candidate->candidate_name)}}</h4>
                                    </span>
                                </div>
                                <div class='col-md-2'>
                                    Gender: {{ $candidate->gender }}
                                </div>
                                <div class='col-md-2'>
                                    Contact No. : {{$candidate->contact_no}}
                                </div>
                                <div class='col-md-2'>
                                    Alternate /Whatsapp No. : {{$candidate->whatsapp_no}}
                                </div>
                                <div class="col-md-3">
                                    Email ID : {{$candidate->candidate_email}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    Current Designation
                                    :<?php 
                                    if($designation){
                                    echo strtoupper($designation);
                                    }else{
                                        echo "-";
                                    }
                                    ?>
                                </div>
                                <div class="col-md-2">
                                    Current CTC : {{$candidate->current_salary}}
                                </div>
                                <div class="col-md-2">
                                    Expected CTC : {{$candidate->expected_salary}}
                                </div>
                                <div class="col-md-2">
                                    Total Experience : {{$candidate->total_exp}}
                                </div>
                                <div class="col-md-3">
                                    Notice Period : {{$candidate->notice_period ." ".$candidate->duration}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class='col-md-5'>
                                    Qualification: <?php 
                                        if($qualification){
                                        echo strtoupper($qualification);
                                        }else{
                                            echo "-";
                                        }
                                        ?>
                                </div>
                                <div class="col-md-2">
                                    Current Location : {{strtoupper($candidate->current_location)}}
                                </div>
                                <div class="col-md-2">
                                    Preferred Location: {{ strtoupper($candidate->preferred_location) }}
                                </div>
                                <div class="col-md-3">
                                    Passport : {{ strtoupper($candidate->passport) }}

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    Skills : {{$candidate->skills}}
                                </div>
                                <div class="col-md-6">
                                    Comments:
                                    @forelse($all_comments as $key => $row)
                                        {{ $row->comment }}
                                        <br>
                                        @empty
                                        <tr>
                                            <th scope="row" colspan="10">No Data To List . . . </th>
                                        </tr>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
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
    <div class="col-md-14">
        <div class="card">
            <?php if(count($candidateDetail) > 0){?>
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
            <?php }else{ ?>
            <center>NOT PROCCESSED FOR ANY POSITIONS</center><?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
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
        requirement + "</div></div>" +
        "<div class=row><div class=col-md-3>Client </div><div class=col-md-1>:</div><div class=col-md-8>" +
        client + "</div></div>" +
        "<div class=row><div class=col-md-3>Interview Mode </div><div class=col-md-1> : </div><div class=col-md-8>" +
        interview_mode + "</div></div>" +
        "<div class=row><div class=col-md-3>Available Time </div><div class=col-md-1> : </div><div class=col-md-8>" +
        available_time + "</div></div>" +
        "<div class=row><div class=col-md-3>Call Back Date </div><div class=col-md-1> : </div><div class=col-md-8>" +
        call_back_date + "</div></div>" +
        "<div class=row><div class=col-md-3>Call Back Time </div><div class=col-md-1> : </div><div class=col-md-8>" +
        call_back_time + "</div></div>" +
        "<div class=row><div class=col-md-3>Call Back Status </div><div class=col-md-1> : </div><div class=col-md-8>" +
        call_back_status + "</div></div>" +
        "<div class=row><div class=col-md-3>Call Status </div><div class=col-md-1> : </div><div class=col-md-8>" +
        call_status + "</div></div>" +
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
        ajax: "{{ route('processed.list',[$id,'view']) }}",
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
</script>
<style>
@media screen and (min-width: 676px) {
    .modal-dialog {
        max-width: 900px;
        /* New width for default modal */
    }
}
</style>
@endsection