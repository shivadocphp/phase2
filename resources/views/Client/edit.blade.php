@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Edit Client {{ $client->client_code }}: Basic Details </h3>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('edit.client',$client->id) }}" class="btn btn-med btn-new-full" disabled style="color: orangered;">Basic Details</a>
            <a href="{{ route('edit.clientaddress',$client->id) }}" class="btn btn-med btn-primary">Address Details</a>
            <a href="{{ route('edit.clientofficial',$client->id) }}" class="btn btn-med btn-primary">Official Details</a>
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
        <div class="col-md-12">
            <form action="{{ route('update.client',$client->id) }}" method="POST">
                {{method_field('patch')}}
                @csrf
                <table class="table table-striped" style="width: 100%;">
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-4'>
                                        Company Name <font color="red" size="3">*</font>
                                        <input type="hidden" class="form-control" name="id" value="{{$client->id}}">
                                        <input type="text" class="form-control" name="company_name" value="{{$client->company_name}}" required>
                                        @error('company_name')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-4'>
                                        Company Email ID <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="company_emailID" value="{{$client->company_emailID}}" required>
                                        @error('company_emailID')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Company Contact No. <font color="red" size="3">*</font>
                                        <input type="number" class="form-control" name="company_contact_no" required value="{{$client->company_contact_no}}"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        @error('company_contact_no')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Industry Type <font color="red" size="3">*</font>
                                        <select class="form-control" required name="industry_type_id">
                                            <option value="">--Select--</option>
                                            @foreach($industry_type as $it)
                                            <option value="{{ $it->id }}"
                                                <?php if($client->industry_type_id == $it->id ){ echo "selected";}?>>
                                                {{ $it->industrytype }}</option>
                                            @endforeach
                                        </select>
                                        @error('industry_type_id')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        CEO or Founder Name <font color="red" size="3">*</font>
                                        <input type="text" name="ceo" class="form-control" value="{{$client->ceo}}" required>
                                        @error('ceo')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        CEO Contact no.<font color="red" size="3">*</font>
                                        <input type="number" class="form-control" name="ceo_contact" value="{{$client->ceo_contact}}"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        @error('ceo_contact')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        CEO Email ID <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="ceo_emailID" value="{{ $client-> ceo_emailID}}" required>
                                        @error('ceo_emailID')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        Company HR SPOC<font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="hr_spoc" value="{{ $client->hr_spoc }}" required>
                                        @error('hr_spoc')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        HR Designation <font color="red" size="3">*</font>
                                        <select class="form-control" name="hr_desig" required>
                                            <option value="">--Select--</option>
                                            @foreach($designations as $d)
                                            <option value="{{ $d->id }}"
                                                <?php if($client->hr_desig == $d->id){ echo "selected";}?>>
                                                {{ $d->designation }}</option>
                                            @endforeach
                                        </select>
                                        @error('hr_desig')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <b><u> Finance/Accounts</u></b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class='col-md-2'>
                                        SPOC <font color="red" size="3">*</font>
                                        <input class="form-control" type="text" name="fspoc" value="{{ $client->fspoc }}" required>
                                        @error('fspoc')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Designation <font color="red" size="3">*</font>
                                        <select name="fspoc_designation" class="form-control" required>
                                            <option value="">--Select--</option>
                                            @foreach($designations as $d)
                                            <option value="{{ $d->id }}"
                                                <?php if($client->fspoc_designation == $d->id){ echo "selected";}?>>
                                                {{ $d->designation }}</option>
                                            @endforeach
                                        </select>
                                        @error('fspoc_designation')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-4'>
                                        Email ID <font color="red" size="3">*</font>
                                        <input type="email" class="form-control" name="fspoc_email" value="{{ $client->fspoc_email }}" required>
                                        @error('fspoc_email')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Contact No. <font color="red" size="3">*</font>
                                        <input type="number" class="form-control" name="fspoc_contact" value="{{ $client-> fspoc_contact}}"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        @error('fspoc_contact')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Status <font color="red" size="3">*</font>
                                        <select name="client_status" class="form-control" required>
                                            <option value="">--Select--</option>
                                            <option value="Active"
                                                <?php if($client->client_status == "Active"){ echo "selected";}?>>Active
                                            </option>
                                            <option value="Inactive"
                                                <?php if($client->client_status == "Inactive"){ echo "selected";}?>>
                                                Inactive</option>
                                            <option value="Prospect"
                                                <?php if($client->client_status == "Prospect"){ echo "selected";}?>>
                                                Prospect</option>
                                            <option value="Blacklisted"
                                                <?php if($client->client_status == "Blacklisted"){ echo "selected";}?>>
                                                Blacklisted</option>
                                        </select>
                                        @error('client_status')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-6'>
                                        Website URL <font color="red" size="3">*</font>
                                        <input type="url" class="form-control" name="website" value="{{ $client->website }}" required>
                                        @error('website')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-6'>
                                        Comments
                                        <input type="text" class="form-control" name="comments" value="{{ $client->comments }}">
                                        @error('comments')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <?php if(false){ ?>
                            <!-- <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Comment</th>
                                                        <th>Added By</th>
                                                        <th>Added at</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($all_comments as $key => $row)
                                                    <tr>
                                                        <td> {{ $key + $all_comments->firstItem()}}</td>
                                                        <td> {{ $row->comment }}</td>
                                                        <td> @isset($row->addedBy) {{ $row->addedBy->name }} @endisset
                                                        </td>
                                                        <td> {{ $row->created_at }}</td>

                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <th scope="row" colspan="10">No Data To List . . . </th>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>

                                            {!! $all_comments->links() !!}
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <?php } ?>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="btn btn-primary" value="save_draft_next"
                                            name="save" title="Save and move to next step">UPDATE
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