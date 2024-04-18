@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Team Management</h3>
    @can('Manage Report')
    <span>
        <button type="button" class="btn btn-new" id="export-excel-button" title="Export">Export Team</button>
    </span>
    @endcan
    <button type="button" class="btn btn-new" style="color: orangered;" data-toggle="modal" data-target="#TeamModal">
        Add Team
    </button>
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

            <div class="card-body">
                <table class="table table-bordered yajra-datatable">
                    <thead style="background-color: #ff751a">
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Team Name</th>
                            <th scope="col">Team Leader</th>
                            <th scope="col">Description</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="TeamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('store.team') }}" method="POST">
                    {{method_field('patch')}}
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    Team Name<input type="text" name="team_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    Team Leader <select name="team_leader" class="form-control" required>
                                        <option value="">--Select--</option>
                                        @foreach($add_mem as $emp)
                                        <option value="{{$emp->emp_id}}">{{ $emp->subtitle }} {{ $emp->firstname }}
                                            {{ $emp->middlename }} {{ $emp->lastname }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label> Description</label>
                            </div>
                            <div>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                            @error('description')
                            <span class="text-danger">{{ $message  }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div>Add Members</div>
                            <div>
                                <?php $i = 1; ?>
                                <table>
                                    <tr>
                                        @foreach($add_mem as $add)
                                        <?php if ($i % 4 == 0) { ?>
                                            <td><input type="checkbox" name="emp_id[]" value="{{ $add->emp_code }}">
                                                {{$add->emp_code}} - {{ $add->subtitle }} {{ $add->firstname }}
                                                {{ $add->middlename }} {{ $add->lastname }}
                                            </td>
                                    </tr>
                                <?php } else { ?>
                                    <td><input type="checkbox" name="emp_id[]" value="{{ $add->emp_code }}">
                                        {{$add->emp_code}} - {{ $add->subtitle }} {{ $add->firstname }}
                                        {{ $add->middlename }} {{ $add->lastname }}
                                    </td>
                                    <td style="width: 50px"></td>

                                <?php }
                                        $i++; ?>
                                @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Edit Team</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('update.team')}}" method="POST">
                    {{method_field('patch')}}
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type='hidden' name='team_id' id='myteam_id'>
                                    Team Name<input type="text" name="team" class="form-control" id="myteam">
                                </div>
                                <div class="col-md-6">
                                    Team Leader <select name="team_leader" class="form-control" id='tl'></select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label> Description</label>
                            </div>
                            <div>
                                <textarea name="description" class="form-control" id="mydescription"></textarea>
                            </div>
                            @error('description')
                            <span class="text-danger">{{ $message  }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div>Add Members</div>
                            <div>
                                <?php $i = 1; ?>
                                <table>
                                    <tr>
                                        @foreach($add_mem as $add)
                                        <?php if ($i % 4 == 0) { ?>
                                            <td><input type="checkbox" name="emp_id[]" value="{{ $add->emp_code }}">{{$add->emp_code}}
                                                - {{ $add->subtitle }} {{ $add->firstname }} {{ $add->middlename }}
                                                {{ $add->lastname }}
                                            </td>
                                    </tr>
                                <?php } else { ?>
                                    <td><input type="checkbox" name="emp_id[]" value="{{ $add->emp_code }}">{{$add->emp_code}}
                                        - {{ $add->subtitle }} {{ $add->firstname }} {{ $add->middlename }}
                                        {{ $add->lastname }}
                                    </td>
                                    <td style="width: 50px"></td>
                                <?php }
                                        $i++; ?>
                                @endforeach
                                </table>
                            </div>
                        </div>
                        <!-- <div id="mem"></div> -->
                        <div class="form-group" id="addMembersSection">
                            <div>Existing Members</div>
                            <div>
                                <table id="mem">
                                    <!-- Existing table content goes here -->
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="members" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Team Members</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('delete.member')}}" method="POST">
                    {{method_field('patch')}}
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div>
                            <table id="members_detail" class="table table-bordered">

                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Remove Members</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script src="{{asset('js/app.js')}}"></script>
<script>
    // to list all the teams
    $(document).ready(function() {
        // $(function() {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            deferRender: true,
            // bLengthChange: false,
            info: false,
            pageLength: 10,
            scroller: {
                loadingIndicator: true
            },
            scrollCollapse: true,
            ajax: "{{ route('team.list') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'team_name',
                    name: 'team_name'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                //  {data:'tenure', name:'tenure'},
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true,
                    responsive: true,
                    processing: true,
                    deferRender: true,
                },
            ],
            language: {
                emptyTable: "No data available in the table"
            },
            initComplete: function() {
                if (table.rows().count() === 0) {
                    $('#export-excel-button').hide(); // Hide the export button
                }
            }
        });
        // export button
        $('#export-excel-button').click(function() {
            var url = "{{ route('export.teammember') }}";
            url += "?export=export";
            window.location.href = url;
        });

    });

    // to get the data for edit form
    $('#edit').on('show.bs.modal', function(event) {
        // console.log('Modal Opened');
        var button = $(event.relatedTarget) // Button that triggered the modal
        var team = button.data('myteam')
        var tl = button.data('mytl')
        var description = button.data('mydescription')
        var id = button.data('myid')
        var mem = button.data('mymem')
        var employ = button.data('myteamdetail')
        var modal = $(this)

        modal.find('.modal-body #myteam').val(team)
        modal.find('.modal-body #mydescription').val(description)
        modal.find('.modal-body #myteam_id').val(id)
        modal.find('.modal-body #mytl').val(employ)
        modal.find('.modal-body #mymem').val(mem)


    })

    // to fetch team members when click team
    $(document).ready(function() {
        $(document).on('click', '.members', function() {
            var id = $(this).data('id');
            // console.log(id);
            $('#members_detail').html(""); //to blank
            $.ajax({
                url: "{{url('api/fetch-detail')}}",
                type: "POST",
                data: {
                    id: id,
                    _token: '{{csrf_token()}}'
                },
                //  dataType:'json',
                success: function(data) {
                    console.log(data)
                    $('#members_detail').html("<tr><td><input type=hidden value=" + data[0]
                        .team_id + " name=team_id><input type=checkbox value=" + data[0]
                        .emp_code + " name=emp_id[]>" + data[0].emp_code + "  -  " + data[0]
                        .subtitle + " " + data[0].firstname + " " + data[0].lastname +
                        "</td>");
                    for (i = 1; i < data.length; i++) {
                        if (data[i].middlename == null) {
                            $('#members_detail').append("<td><input type=hidden value=" + data[
                                    i].team_id +
                                " name=team_id><input type=checkbox value=" + data[i].emp_code +
                                " name=emp_id[]>" + data[i].emp_code + "  -  " + data[i].subtitle + " " +
                                data[i].firstname + " " + data[i].lastname + "</td>");
                            // $('#members_detail').html("<tr><td>"+i +"."+ data[0].emp_code +"  -  " + data[0].subtitle + " " + data[0].firstname + " " + data[0].lastname + "</td><td><a href='{{ route('delete.member',["+ data[0].emp_code+"])}}'>Remove</a></td></tr>");
                        } else if (data[i].lastname == null) {
                            // $('#members_detail').html("<tr><td>" + i +"."+ data[0].emp_code +"  -  " + data[0].subtitle + " " + data[0].firstname + " " + data[0].middlename + "</td><td><a href=''>Remove</a></td></tr>");
                            $('#members_detail').append("<td><input type=hidden value=" + data[i].team_id +
                                " name=team_id><input type=checkbox value=" + data[i].emp_code +
                                " name=emp_id[]>" + data[i].emp_code + "  -  " + data[i].subtitle + " " +
                                data[i].firstname + " " + data[i].middlename + "</td>");
                        } else {
                            $('#members_detail').append("<td><input type=hidden value=" + data[i].team_id +
                                " name=team_id><input type=checkbox value=" + data[i].emp_code + " name=emp_id[]>" +
                                data[i].emp_code + "  -  " + data[i].subtitle + " " + data[i].firstname + " </td>");
                            //  $('#members_detail').html("<tr><td>" + i +"."+ data[0].emp_code +"  -  " + data[0].subtitle + " " + data[0].firstname  + "</td><td><a href=''>Remove</a></td></tr>");
                        }
                        if (i % 4 == 0) {
                            $('#members_detail').append("</tr><tr>");
                        }
                    }
                }
            });
        });
    });

    /* $(document).on('click', '.edit_members', function () {
    var id = $(this).data('myid');
    var team_leader = 0;
    $.ajax({
    url: "{{url('api/fetch-team')}}",
    type: "POST",
    data: {
    id: id,
    _token: '{{csrf_token()}}'
    },
    dataType: 'json',
    success: function (data) {
    console.log(data)
    $('#mem').html("<div class='form-group'><div class='row'><div>Team Name<input type='text' class='form-control' name='team' value='" + data[0].team_name + "'></div>");

    $('#mem').append("<div>Description<textarea class='form-control' name='description'>" + data[0].description + "</textarea>");
    team_leader = data[0]
    $.ajax({
    url: "{{url('api/fetch-employee-team')}}",
    type: "POST",
    dataType: 'json',
    success: function (response) {
    for (i = 0; i < response.length; i++) {
    $('#mem').append("<option>Seelct</option>");
    }
    }
    $('#mem').append("</select></div></div></div>");
    }
    });
    })
    ;*/


    // for update and fetch  the team tl and members
    $(document).ready(function() {
        $(document).on('click', '.edit_members', function() {
            var id = $(this).data('myid');
            var team_leader = $(this).data('mytl');
            console.log(id);
            console.log(team_leader);
            $.ajax({
                url: "{{url('api/fetch-employee-team')}}",
                type: "POST",
                data: {
                    id: id,
                    team_leader: team_leader,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#tl').html('');
                    $("#mem").html(''); //added
                    // Iterate the tl of the team
                    $.each(result.emp, function(key, value) {
                        if (value.middlename == null) {
                            name = value.emp_code + "-" + value.firstname + " " + value
                                .lastname;
                        } else if (value.lastname) {
                            name = value.emp_code + "-" + value.firstname + " " + value
                                .middlename;
                        } else {
                            name = value.emp_code + "-" + value.firstname + " " + value
                                .middlename + " " + value.lastname;
                        }
                        if (value.id == team_leader) {
                            $("#tl").append('<option value="' + value.id +
                                '" selected >' + name + '</option>');
                        } else {
                            $("#tl").append('<option value="' + value.id + '" >' +
                                name + '</option>');
                        }
                    });

                    // Iterate the existing emp in the team
                    $.each(result.emp_team_member, function(key, value2) {
                        var name = '';
                        if (value2.middlename == null) {
                            name = value2.emp_code + "-" + value2.firstname + " " +
                                value2.lastname;
                        } else if (value2.lastname) {
                            name = value2.emp_code + "-" + value2.firstname + " " +
                                value2.middlename;
                        } else {
                            name = value2.emp_code + "-" + value2.firstname + " " +
                                value2.middlename + " " + value2.lastname;
                        }
                        $("#mem").append('<tr><td>' + name + '</td></tr>');
                    });
                }
            });
        });
    });


    // to add the team
    $(document).ready(function() {
        $('.add').click(function() {
            const id = $(this).attr('data-id')
            console.log(id)
            $.ajax({
                url: "{{url('api/fetch-employee')}}",
                type: "POST",
                data: {
                    id: id,
                    _token: '{{csrf_token()}}'
                },
                //  dataType:'json',
                success: function(data) {
                    console.log(data)
                    $('#member_add').html("<tr><td><td><input type=hidden value=" + id +
                        " name=team_id><input type=checkbox value=" + data[0].emp_code +
                        " name=emp_id[]></td><td>" + data[0].emp_code + "  -  " + data[0]
                        .name + "</td></tr>");
                    for (i = 1; i < data.length; i++) {

                        //if (data[i].middlename == null) {
                        $('#member_add').append("<tr><td><td><input type=hidden value=" + id +
                            " name=team_id><input type=checkbox value=" + data[i].emp_code +
                            " name=emp_id[]></td><td>" + data[i].emp_code + "  -  " + data[
                                i].name + "</td></tr>");

                        /* } else if(data[i].lastname==null) {
                         
                         $('#member_add').html("<tr><td>"+i+".<input type=checkbox value="+data[i].emp_code+" name=emp_id[]></td><td>"+ data[0].emp_code +"  -  " + data[0].subtitle + " " + data[0].firstname + " " + data[0].middlename + "</td></tr>");
                         }else{
                         $('#member_add').html("<tr><td>"+i+".<input type=checkbox value="+data[i].emp_code+" name=emp_id[]></td><td>"+ data[0].emp_code +"  -  " + data[0].subtitle + " " + data[0].firstname + " </td></tr>");
                         */
                    }

                }
                //  }

            });
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



@endsection('admin')