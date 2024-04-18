@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title"> {{ $allocates->client_code ." - ". $allocates->company_name}} Allocated to
            : {{ $allocates->team }} </h3>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <a href="" class="btn btn-med btn-primary" disabled
                   style="color: orangered;">Allocated Requirements</a>


            </div>
            <div class="col-md-4" align="right"><a href="{{ route('allocate_task.allocation',$id) }}"
                                                   class="btn btn-med btn-success">Edit Alocation</a>
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
        <div class="col-md-12">

            <div class="form-group">
                <table width="100%" class="table table-bordered" style="background-color: white">
                    <thead>
                    <tr style="text-align: center" >
                        <th>No</th>
                        <th colspan="3">Requirement</th>
                        <th>Allocated to</th>
                        <th>Allocated No</th>
                        <th>Status</th>

                    </tr>
                    </thead>
                    <tbody>
                        @if($tasks !=null)
<?php  $i=1;?>
                            @foreach($tasks as$task)
                                <tr align="center">
                                    <td>{{ $i++ }}</td>
                                    <?php
                                    $re = DB::table('client_requirements')
                                        ->join('designations', 'designations.id', 'client_requirements.position')
                                        ->join('client_addresses', 'client_addresses.id', 'client_requirements.location')
                                        ->join('states', 'states.id', 'client_addresses.state_id')
                                        ->join('cities', 'cities.id', 'client_addresses.city_id')
                                        ->select('client_requirements.id', 'designations.designation', 'client_requirements.total_position',
                                            'states.state', 'cities.city', 'client_addresses.address','client_requirements.requirement_status')
                                        ->where('client_requirements.id', $task->requirement_id)
                                        //->where('client_requirements.requirement_status','Active')
                                        ->first();
                                    ?>
                                    <td colspan="3">

                                           {{ $re->designation."(". $re->total_position .")".",".$re->address.",".$re->city.",".$re->state }}</option>

                                    </td>
                                    <td>    @foreach($team_mem as $tm)
                                                <?php if ($task->employee_id == $tm->id) {

                                                echo $tm->emp_code ." - ".$tm->firstname." ".$tm->middlename." ".$tm->lastname ; } ?>
                                            @endforeach
                                       </td>
                                    <td>{{$task->allocated_no}}</td>
                                    <td>{{ $re->requirement_status }}</td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>

                <table class="table table-bordered yajra-datatable">
                    <thead style="background-color: #ff751a">
                    <tr style="text-align: center" >
                        <th>No</th>
                        <th colspan="3">Requirement</th>
                        <th>Allocated to</th>
                        <th>Allocated No</th>
                        <th>Status</th>

                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>


        </div>
    </div>
    <script type="text/javascript">
        $(function () {

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('getTask.list') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'client_code', name: 'client_code'},
                    {data:'team',name:'team'},

                    //   { data:'added_by',name:'added_by'},


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
@endsection
