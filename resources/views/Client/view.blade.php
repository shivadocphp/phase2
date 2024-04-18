@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title"> Client Detail </h3>
        <div class="col-md-4" align="right">
            <a href="{{ route('all.client') }}" class="btn btn-med btn-success">View All Clients</a>
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
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th colspan="3">
                                <center>
                                    <h4>Company Name: {{$client->company_name}}
                                    </h4>
                                </center>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Company Email ID : {{ $client->company_emailID }}</td>
                            <td>Company Contact No.:{{$client->company_contact_no}}</td>
                            <td>Industry Type : @isset($client->industry){{ $client->industry->industrytype }}@endisset</td>
                        </tr>
                        <tr>
                            <td>CEO or Founder Name : {{ $client->ceo  }}</td>
                            <td>CEO Contact no : {{ $client->ceo_contact }}</td>
                            <td>CEO Email ID : {{$client->ceo_emailID }}</td>
                        </tr>
                        <tr>
                            <td>Company HR SPOC : {{ $client->hr_spoc  }}</td>
                            <td>HR Designation : @isset($client->hr_designation){{ $client->hr_designation->designation }}@endisset</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-weight: bold;">Finance/Accounts :
                            </td>

                        </tr>
                        <tr>
                            <td >SPOC: {{ $client->fspoc }}</td>
                            <td >Designation: @isset($client->fsop_designation_nm){{ $client->fsop_designation_nm->designation }}@endisset</td>
                            <td >Email ID: {{ $client->fspoc_email }}</td>
                        </tr>
                        <tr>
                            <td >Contact No: {{ $client->fspoc_contact }}</td>
                            <td >Status: {{ $client->client_status }}</td>
                            <td >Website URL: {{ $client->website }}</td>
                        </tr>
                        <tr>
                            <td colspan="3">Comments: {{ $client->comments }}</td>
                        </tr>
                        @if($address_ext == 1)
                            @foreach($client_address as $client_add)
                                <tr>
                                    <td colspan="3" style="font-weight: bold;">Address : {{ $client_add->address_type }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Address : {{ $client_add->address }}</td> 
                                    <td>State: @isset($client_add->state){{ $client_add->state->state }}@endisset</td>
                                    <td>City: @isset($client_add->city){{ $client_add->city->city }}@endisset, Country: @isset($client_add->country){{ $client_add->country->country }}@endisset</td>
                                </tr>
                                <tr>
                                    <td>Pincode: {{ $client_add->pincode }}</td>
                                    <td>Started Year & Month: {{ $client_add->start_mon_year }}</td>
                                    <td>GST:{{ $client_add->gst }}</td>
                                </tr>
                            @endforeach 
                        @endif   
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>

@endsection
