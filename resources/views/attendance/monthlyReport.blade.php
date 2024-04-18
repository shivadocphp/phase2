@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Attendance </h3>

</div>

<!-- <div class="container" style="min-width: max-content;">
<form action="{{ route('generateReport')}}" method="post">
@csrf

<label for="start">Select month:</label>
<input type="month" id="start" name="start" min="2018-03" required>     
 <button type="submit" class="btn-primary btn-mini" formtarget="_blank">Get Employee Attendance Details</button>
</form>
</div> -->



<!-- <a href="{{ route('generateExcel')}}"><button type="submit" class="btn-primary btn-mini" formtarget="_blank">Generate Excel</button></a> -->
<form action="{{ route('generateExcel') }}" method="GET" target="_blank">
    <input type="hidden" name="month" id="month" class="form-control" value="{{ $search['month'] ?? '' }}">
    <input type="hidden" name="year" id="year" class="form-control" value="{{ $search['year'] ?? '' }}">
    <!-- <button type="submit" class="btn btn-primary">Generate Excel</button> -->
    <button type="submit" class="btn btn-primary" @if($search['month'] === null || $search['year'] === null) disabled @endif>Generate Excel</button>
</form>
<a href="{{ route('all.weekoff')}}" target="_blank"><button type="submit" class="btn-primary btn-mini" formtarget="_blank">Mark Week Off</button></a>

<div class="container">
    <form method="post" action="{{ route('attendances.monthly')}}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control js-example-basic-single" name="year" id="year">
                        <option value="">--Select Year</option>
                        <option @isset($search['year']) @if($search['year']==date('Y')-1) selected="" @endif @endisset value="<?php echo date('Y') - 1; ?>"><?php echo date('Y') - 1; ?></option>
                        <option @isset($search['year']) @if($search['year']==date('Y')) selected="" @endif @endisset value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>

                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control js-example-basic-single" name="month" id="month">
                        <option @isset($search['month']) @if($search['month']=='01' ) selected="" @endif @endisset value="01">January</option>
                        <option @isset($search['month']) @if($search['month']=='02' ) selected="" @endif @endisset value="02">February</option>
                        <option @isset($search['month']) @if($search['month']=='03' ) selected="" @endif @endisset value="03">March</option>
                        <option @isset($search['month']) @if($search['month']=='04' ) selected="" @endif @endisset value="04">April</option>
                        <option @isset($search['month']) @if($search['month']=='05' ) selected="" @endif @endisset value="05">May</option>
                        <option @isset($search['month']) @if($search['month']=='06' ) selected="" @endif @endisset value="06">June</option>
                        <option @isset($search['month']) @if($search['month']=='07' ) selected="" @endif @endisset value="07">July</option>
                        <option @isset($search['month']) @if($search['month']=='08' ) selected="" @endif @endisset value="08">August</option>
                        <option @isset($search['month']) @if($search['month']=='09' ) selected="" @endif @endisset value="09">September</option>
                        <option @isset($search['month']) @if($search['month']=='10' ) selected="" @endif @endisset value="10">October</option>
                        <option @isset($search['month']) @if($search['month']=='11' ) selected="" @endif @endisset value="11">November</option>
                        <option @isset($search['month']) @if($search['month']=='12' ) selected="" @endif @endisset value="12">December</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary" id="search">Monthly attendance</button>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col ">
            <div class="table-responsive">
                <div id="tableDiv"></div>
                <!-- <table class="table  table-bordered yajra-datatable" >
                  <thead>    
                   <tr>
                    <td>Sl.No</td>
                       <td>Employee</td>
                       @for($i=1;$i<=31;$i++)
                        <td><?php
                            // echo $i;     
                            ?></td>
                       @endfor
                   </tr>
                 </thead>
                 <tbody></tbody>
               </table>  -->
            </div>
        </div>
    </div>


    <!-- only for the localhost not for server-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
        // $(function() {
        /*$.getJSON("{{-- route('attendances.monthly.cols') --}}", function(columnsData) {

          $(".yajra-datatable").DataTable({
              processing: true,
              serverSide: true,
              deferRender: true,
              ajax: "{{-- route('attendances.monthly.list') --}}",
              "columns": columnsData
          });
        });*/

        /*var table = $('.yajra-datatable').DataTable({
            "responsive": true,
            "autoWidth": false,
            processing: true,
            serverSide: true,
            deferRender: true,
            ajax: "{{-- route('attendances.monthly.list') --}}",
            //"columns": columnsData
            //"columns": getColumns()
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data:'emp_code',name:'emp_code'},
                {data:'2022-07-25',name:'2022-07-25'},
                
                {
                  data: 'action',
                  name: 'action',
                  orderable: true,
                  searchable: true
                },
            ]
        });*/
        // });
        /*function drawTable(data,columns) {
            console.log(data);
           $('#displayTable').DataTable( {
                "processing" : true,
                "dataSrc": data,
                "columns": columns
            } );
        }*/

        $(function() {
            var year = $('#year').val();
            var month = $('#month').val();

            $.ajax({
                "url": "{{ route('attendances.monthly.list') }}",
                data: {
                    'year': year,
                    'month': month
                },
                success: function(result) {
                    var tableHeaders = '';
                    $.each(result.columns_header, function(i, val) {
                        tableHeaders += "<th>" + val + "</th>";
                    });
                    // console.log(tableHeaders);

                    $("#tableDiv").empty();
                    $("#tableDiv").append('<table id="displayTable" class=" table table-bordered"     cellspacing="0" width="100%"><thead style="background-color:#ff751a;"><tr>' + tableHeaders + '</tr></thead><tbody></tbody></table>');

                    var columns = [];
                    $.each(result.columns, function(i, value) {
                        var obj = {
                            data: value,
                            name: value
                        };
                        columns.push(obj);
                    });
                    console.log(columns);
                    //drawTable(result.data,columns);
                    // console.log("Before DataTable Initialization");
                    $('#displayTable').DataTable({
                        responsive: true,
                        processing: true,
                        deferRender: true,
                        //serverSide: true,
                        data: result.data,
                        columns: columns,
                    });
                    // console.log("After DataTable Initialization");
                },
                "dataType": "json"
            });
        });
    </script>
    @endsection