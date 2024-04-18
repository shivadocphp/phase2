@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Performance Assessment</h3>

    </div>
    <div class="container">
        <div class="row">
            
            <div class="col-md-14">
                <div class="row">
                     <table class="table" style="width:100%">
                         <tr>
                            
                             <td >Period : {{$financial_year}}</td>
                             <td >Company: {{ $company }}</td>
                             <td>Employee: {{ $name }}</td> 
                             <td >D.O.J: {{ $joining_date }}</td>
                             <td>Designation: {{ $designation}}</td>
                             <td>Employee Id: {{ $emp_id}}</td>
                         </tr>
                     </table>
                     
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

                    <form method="post" action="{{route('employee.performanceassessmentscore')}}" onsubmit="return confirm('Do you really want to submit the form?');">
                    @csrf
                    <input type="hidden" name="financial_year" value="{{$financial_year }}">
                    <input type="hidden" name="emp_id" value="{{$emp_id }}">
                    <div class="card-body" style="width:100%">
                        <table class="table"  style="width:100%">
                            <thead>
                            <tr style="background-color:#BAE1D0">
                                <th scope="col">No.</th>
                                <th scope="col">Assessment Parameter</th>
                                <th scope="col">Description</th>
                                <th scope="col">Weightage</th>
                                <th scope="col" >Self Assessment Score</th>
                                <!-- <th scope="col" >Manager Assessment</th> -->
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1;$weightage = 0 ; ?>
                            @foreach($assessment as $ap)
                            <?php $weightage +=$ap->weightage ;?>
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td><input type="hidden" value="{{ $ap->id }}" name="assessment_id[]">{{ $ap->assessment_parameter }}</td>
                                    <td>{{ $ap->description }}</td>
                                    <td>{{ $ap->weightage }}</td>
                                    <td><input type="number" name="self_score[]" class="form-control" required onkeyup="sum()" ></td>
                                    <!-- <td><input type="number" name="manager_score[]" class="form-control" onkeyup="sum1()" disabled></td> -->
                                   
                                </tr>
                            @endforeach
                                
                            <tr>
                                    <td></td><td></td><td>Total</td><td>{{ $weightage }}</td>
                                <td>
                                    <input type="text" class="form-control" name="self_total" id="self_total" required readonly>
                                </td>
                                <!-- <td>
                                    <input type="text" class="form-control" name="manager_total" id="manager_total" readonly>
                                </td> -->
                            </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                       <div class="row">
                           <div class="col" width="90%">
                               Comments by Appraiser in terms of Significant Achievements, Performance Gaps, Attitudinal observation and Areas of Improvements, Job Rotation and Training required, or any other recommendations, if any.
                               <textarea name="self_comment" class="form-control"></textarea>
                           </div>
                           
                       </div></div>
                       <div class="form-group">
                       <div class="row">
                           <div class="col" style="text-align:center">
                              <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                           </div>
                           
                       </div></div>
                    </div>
                    </form>
                </div>
            </div>

           
        </div>


    </div>




    <script src="{{asset('js/app.js')}}"></script>
    <script>
        function sum() {
           var arr = document.getElementsByName('self_score[]');

            var tot = 0;
            for (var i = 0; i < arr.length; i++) {
                if (parseInt(arr[i].value))
                    tot += parseInt(arr[i].value);
            }
            document.getElementById('self_total').value = tot;
        }
        function sum1() {
           var arr = document.getElementsByName('manager_score[]');

            var tot = 0;
            for (var i = 0; i < arr.length; i++) {
                if (parseInt(arr[i].value))
                    tot += parseInt(arr[i].value);
            }
            document.getElementById('manager_total').value = tot;
        }
    </script>
@endsection('admin')
