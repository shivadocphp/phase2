@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Edit Client {{ $client->client_code }}: Official Details</h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('edit.client',$client->id) }}" class="btn btn-med btn-primary" disabled>Basic Details</a>
            <a href="{{ route('edit.clientaddress',$client->id) }}" class="btn btn-med btn-primary">Address
                Details</a>
            <a href="{{ route('edit.clientofficial',$client->id) }}" class="btn btn-med btn-new-full"
                style="color: orangered;">Official Details</a>
            <a href="{{ route('edit.clientagreement',$client->id) }}" class="btn btn-med btn-primary">Agreement</a>
            <a href="{{ route('edit.clientrequirement',$client->id) }}" class="btn btn-med btn-primary">Requirements</a>
        </div>
        <div class="col-md-4" align="right"><a href="{{ route('all.client') }}" class="btn btn-med btn-primary">View
                Clients</a>
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
    <div class="row">
        <div class="col-md-14">
            <form action="{{ route('update.client_official',$client->id) }}" method="POST" enctype="multipart/form-data">
                {{method_field('patch')}}
                @csrf
                <table class="table table-striped">
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-3'>
                                        Service Type
                                        <select name="service_type" class="form-control">
                                            <option value="">--Select--</option>
                                            @foreach($service_type as $st)
                                            <option value="{{$st->id}}">{{$st->servicetype}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class='col-md-3'>
                                        Date of Empanelment <font color="red" size="3">*</font>
                                        <input type="date" class="form-control" name="date_empanelment"
                                            value="{{ $client_off-> date_empanelment}}" required>
                                        @error('date_empanelment')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-3'>
                                        Date of Renewal <font color="red" size="3">*</font>
                                        <input type="date" class="form-control" name="date_renewal"
                                            value="{{ $client_off->date_renewal }}" required>
                                        @error('date_renewal')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-3'>
                                        Freezing Period <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="freezing_period"
                                            value="{{ $client_off-> freezing_period}}" required>
                                        @error('freezing_period')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-3'>
                                        Rehire Policy <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="rehire_policy"
                                            value="{{ $client_off->rehire_policy }}" required>
                                        @error('rehire_policy')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-3'>
                                        Profile Validity <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="profile_validity"
                                            value="{{ $client_off->profile_validity }}" required>
                                        @error('profile_validity')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-3'>
                                        Call Back Date
                                        <input type="date" class="form-control" name="callback_date"
                                            value="{{ $client_off->callback_date }}" required>
                                        @error('callback_date')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-3'>
                                        Call Back Time
                                        <input type="time" class="form-control" name="callback_time"
                                            value="{{ $client_off->callback_time }}" required>
                                        @error('callback_time')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <center>Agreed Payout T & C</center>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="agreed1"
                                            value="{{ $client_off->agreed1 }}" placeholder="1">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="agreed2"
                                            value="{{ $client_off->agreed2 }}" placeholder="2">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="agreed3"
                                            value="{{ $client_off->agreed3 }}" placeholder="3">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="agreed4"
                                            value="{{ $client_off->agreed3 }}" placeholder="4">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <center>Payment T & C</center>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="payment1"
                                            value="{{ $client_off->payment1 }}" placeholder="1">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="payment2"
                                            value="{{ $client_off-> payment2}}" placeholder="2">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="payment3"
                                            value="{{ $client_off->payment3 }}" placeholder="3">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="payment4"
                                            value="{{ $client_off->payment4 }}" placeholder="4">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <center>Replacement T & C</center>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="replacement1"
                                            value="{{ $client_off->replacement1 }}" placeholder="1">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="replacement2"
                                            value="{{ $client_off->replacement2 }}" placeholder="2">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="replacement3"
                                            value="{{ $client_off->replacement3 }}" placeholder="3">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="replacement4"
                                            value="{{ $client_off->replacement4 }}" placeholder="4">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="btn btn-primary" value="save_draft_next" name="save_draft" title="Save">SAVE
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
@endsection