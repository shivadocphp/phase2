@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title"> Requirements Detail </h3>
        <div class="row">
            <a href="{{ route('edit.requirement',$id) }}" class="btn btn-sm btn-primary"
               style="color: orangered;">Edit</a>
        </div>
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
                <div class="card-body">
                    @isset($require)
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th colspan="3">
                                <center>
                                    <h4>Company : {{ $require->client_code.'-'.$require->company_name }}
                                        <?php if($require->requirement_status == "Active"){?>
                                        <button class="button">{{$require->requirement_status}}</button>
                                        <?php  }else if($require->requirement_status == "Hold") {?>
                                        <button class="button2">{{$require->requirement_status}}</button>
                                        <?php } else if($require->requirement_status == "Prospect"){?>
                                        <button class="button1">{{$require->requirement_status}}</button>
                                        <?php }else if($require->requirement_status == "Closed by Client"){?>
                                        <button class="button3">{{$require->requirement_status}}</button>
                                        <?php }else if($require->requirement_status == "Closed by Company"){ ?>
                                        <button class="button3">{{$require->requirement_status}}</button>
                                        <?php }?>
                                    </h4>
                                </center>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Position : {{ $require->designation }}</td>
                            <td>Vacancy:{{ $require->total_position }}</td>
                            <td>Location: {{ $require->address.",".$require->city.",".$require->state }}</td>
                        </tr>
                        <tr>
                            <td>Experience : {{ $require->min_years. ' - '.$require->max_years. 'Years'  }}</td>
                            <td>Salary : {{ $require->salary_min .' - '.$require->salary_max }}</td>
                            <td>Qualification : {{$require->matriculation}}, {{ $require->plustwo }}
                                , {{ $require->qualificationlevel. ' - '.$require->qualification }}</td>
                        </tr>
                        <tr>
                            <td colspan="3">Skills :
                                <?php $skills = null;
                                if ($require->skills != null) {
                                    $sk = json_decode($require->skills);
                                    if ($sk != null) {
                                        for ($i = 0; $i < count($sk); $i++) {
                                            $getskill = \App\Models\Skill::find($sk[$i])->skill;
                                            // print_r($getskill);
                                            if ($skills != null) {
                                                $skills = $skills . "," . $getskill;
                                            } else {
                                                $skills = $getskill;
                                            }
                                        }
                                    }
                                }?>
                                {{$skills}}</td>

                        </tr>
                        <tr>
                            <td colspan="3">JD: {{$require->jd}}</td>
                        </tr>
                        <tr>
                            <td>Interview Rounds : {{$require->interview_rounds}}</td>
                            <td>Bond : {{ $require->bond }}<?php  if($require->bond == "Y"){?> Years
                                : {{ $require->bond_years }} <?php }?></td>
                            <td>Open Till : {{ $require->open_till }}</td>
                        </tr>
                        <tr>
                            <td>Targeted Companies : {{$require->targeted_companies}}</td>
                            <td>Non Patch Companies:{{ $require->nonpatch_companies }}</td>
                            <td>Cab Facility : {{$require->cab_facility}} , Hiring Radius
                                : {{ $require->hiring_radius }}</td>
                        </tr>
                        <tr>
                            <td>Domain : {{ $require->domain }}</td>
                            <td>Role type:{{ $require->role_type }}</td>
                            <td>Employement Type:{{ $require-> employement_type}}</td>
                        </tr>
                        <tr>
                            <td>No of consultant working on: {{ $require->no_consultant }}</td>
                            <td>TAT : {{ $require->tat }}</td>
                        </tr>

                        </tbody>
                    </table>
                    @endisset

                </div>
            </div>
        </div>
    </div>

@endsection('admin')
