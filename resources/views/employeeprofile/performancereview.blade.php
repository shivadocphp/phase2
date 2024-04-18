@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Performance Review</h3>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-14">
                 <div class="row">
                     <table class="table" style="width:100%">
                         <tr>
                            <td >Period : {{$financial_year}}</td>
                            <td >Company : {{ $company }}</td>
                            <td>Employee: {{ Auth::user()->emp_code}} - {{ Auth::user()->name }}</td> 
                            <td >D.O.J : {{ $joining_date }}</td>
                            <td>Designation: {{ $designation }}</td>
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
                  
                    <form method="post" action="{{route('employee.performancereview')}}">
                    @csrf
                    <input type="hidden" name="financial_year" value="{{$financial_year }}">
                    <input type="hidden" name="emp_id" value="{{$emp_id }}">

                    <div class="card-body" style="width:100%">
                        <table class="table"  style="width:100%">
                            <thead>
                            <tr style="background-color:#BAE1D0">
                                <th scope="col">No.</th>
                                <th scope="col" style="width:50%">Questions</th>
                                <th scope="col" style="width:100%">Answer</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1;$weightage = 0 ; ?>
                            @foreach($reviews as $ap)
                           
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td><input type="hidden" value="{{ $ap->id }}" name="review_id[]">{{ $ap->question }}</td>
                                    <td><textarea  name="answer[]" class="form-control" rows="2" cols="40" required></textarea></td>
                                   
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="form-group">
                       <div class="row">
                           <div class="col" width="90%">
                               Summary Comments <textarea name="comments" class="form-control" required></textarea>
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
  
@endsection('admin')
