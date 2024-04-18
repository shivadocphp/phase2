@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title">Employee Performance</h3>

</div>
<div class="container">
    <div class="row">

        <div class="col-md-14">
            <div class="tabset">
                <!-- Tab 1 -->
                <input type="radio" name="tabset" id="tab1" aria-controls="assessment" checked>
                <label for="tab1">Assessment</label>
                <!-- Tab 2 -->
                <input type="radio" name="tabset" id="tab2" aria-controls="review">
                <label for="tab2">Review</label>
                <div class="tab-panels">
                    <section id="assessment" class="tab-panel">

                        <div class="row">
                            <div class="col">

                                <form method="post" action="{{ route('employee.manager_employee_filter', [$emp_id])}}">
                                    @csrf
                                    <table class="table">
                                        <tr>
                                            <td> Period :<select name="financial" class="form-control" required>
                                                    <option value="">--Select Financial Year--</option>
                                                    @foreach($fn_year as $fyear)
                                                    <option rel="{{$financial_year}}" value="{{$fyear->financial_year}}" <?php  if ($financial_year == $fyear->financial_year) {
                                                    echo "selected";
                                                }?>>{{$fyear->financial_year}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><br><input type="submit" name="get" class="btn btn-primary" value="Get">
                                            </td>
                                        </tr>


                                    </table>
                                </form>
                            </div>
                            <div class="col">
                                <table class="table" style="width:100%">
                                    <tr>
                                        <td>Company:<br> {{ $company }}</td>
                                        <td>Employee:<br> {{ $name }}</td>
                                    </tr>
                                    <tr>
                                        <td>D.O.J:<br> {{ $joining_date }}</td>
                                        <td>Designation: <br>{{ $designation}}</td>
                                    </tr>

                                </table>
                            </div>

                        </div>
                        <?php if(!$assessment->isEmpty()){ ?>
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
                            <form method="post" action="{{route('employee.managerscore')}}"
                                onsubmit="return confirm('Do you really want to submit the form?No changes can be done after submission!!');">
                                @csrf
                                <?php $total_manager = 0;?>
                                <input type="hidden" name="emp_id" value="{{$emp_id }}">
                                <input type="hidden" name="financial_year" value="{{$financial_year }}">
                                <div class="card-body" style="width:100%">
                                    <table class="table" style="width:100%">
                                        <thead>
                                            <tr style="background-color:#BAE1D0">
                                                <th scope="col">No.</th>
                                                <th scope="col">Assessment Parameter</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Weightage</th>
                                                <th scope="col">Self Assessment Score</th>
                                                <th scope="col">Manager Assessment</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php $i = 1;$weightage = 0; $total_self = 0;?>
                                            @foreach($assessment as $ap)
                                            <?php $weightage += $ap->weightage;$total_self += $ap->self_score;$total_manager += $ap->manager_score;?>
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td><input type="hidden" value="{{ $ap->id }}"
                                                        name="assessment_id[]">{{ $ap->assessment_parameter }}
                                                </td>
                                                <td>{{ $ap->description }}</td>
                                                <td>{{ $ap->weightage }}</td>
                                                <td>{{ $ap->self_score}}</td>
                                                <td><?php if($total_manager>0){ ?>{{ $ap->manager_score }}
                                                    <?php }else{ ?><input type="number" name="manager_score[]" class="form-control" value="{{ $ap->manager_score}}"
                                                        required onkeyup="sum1()"><?php } ?></td>

                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>Total</td>
                                                <td>{{ $weightage }}</td>
                                                <td>{{ $total_self}}</td>
                                                <td><input type="text" class="form-control" name="manager_total" value="{{ $total_manager }}" id="manager_total" required readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col" width="90%">
                                                Comments by Appraiser in terms of Significant Achievements,
                                                Performance Gaps, Attitudinal observation and Areas of Improvements,
                                                Job Rotation and Training required, or any other recommendations, if
                                                any.<br>

                                            </div>


                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col">
                                                Employee Comment:
                                                <textarea
                                                    class="form-control">@isset($assessment_total){{ $assessment_total->self_comment}}@endisset</textarea>
                                            </div>
                                            <div class="col">
                                                Manager Comment:
                                                <textarea name="manager_comment"
                                                    class="form-control">@isset($assessment_total){{ $assessment_total->manager_comment}}@endisset</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col" style="text-align:center">
                                                <?php if($total_manager == 0 ){?><input type="submit" name="submit"
                                                    value="Submit" class="btn btn-primary"><?php } ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php } ?>
                    </section>

                    <section id="review" class="tab-panel">

                        <div class="row">
                            <div class="col">
                                <form method="post" action="{{ route('employee.manager_employee_filter', [$emp_id])}}">
                                    @csrf
                                    <table class="table">
                                        <tr>
                                            <td> Period :<select name="financial" class="form-control" required>
                                                    <option value="">--Select Financial Year--</option>
                                                    @foreach($fn_year as $fyear)
                                                    <option value="{{$fyear->financial_year}}" <?php  if ($financial_year == $fyear->financial_year) {
                                                    echo "selected";
                                                }?>>{{$fyear->financial_year}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><br>
                                            <input type="submit" name="get" class="btn btn-primary" value="Get">
                                            </td>
                                        </tr>


                                    </table>
                                </form>
                            </div>
                            <div class="col">
                                <table class="table" style="width:100%">
                                    <tr>
                                        <td>Company:<br> {{ $company }}</td>
                                        <td>Employee:<br> {{ $name }}</td>
                                    </tr>
                                    <tr>
                                        <td>D.O.J:<br> {{ $joining_date }}</td>
                                        <td>Designation: <br>{{ $designation}}</td>
                                    </tr>

                                </table>
                            </div>

                        </div>
                        <?php if(!$reviews->isEmpty()){ ?>
                        <div class="card">

                            <form method="post" action="{{route('employee.performancereview')}}"
                                onsubmit="return confirm('Do you really want to submit the form?No changes can be done after submission!!');">
                                @csrf
                                <input type="hidden" name="emp_id" value="{{$emp_id }}">
                                <input type="hidden" name="financial_year" value="{{$financial_year }}">


                                <div class="card-body" style="width:100%">
                                    <table class="table" style="width:100%">
                                        <thead>
                                            <tr style="background-color:#BAE1D0">
                                                <th scope="col">No.</th>
                                                <th scope="col" style="width:50%">Questions</th>
                                                <th scope="col" style="width:100%">Answer</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php $i = 1;$weightage = 0;$answer=0; ?>
                                            @foreach($reviews as $ap)
                                            <?php if($ap->answer!=null){$answer=1;}?>
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td><input type="hidden" value="{{ $ap->id }}"
                                                        name="question_id[]">{{ $ap->question }}</td>
                                                <td><textarea name="answer[]" class="form-control" rows="2"
                                                        cols="40">{{ $ap->answer }}</textarea></td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if($answer==0)
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col" style="text-align:center">
                                                <input type="submit" name="submit" value="Submit"
                                                    class="btn btn-primary">
                                            </div>

                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <?php } ?>

                    </section>
                </div>

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