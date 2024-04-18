@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Add Client : Basic Details </h3>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('create.client') }}" class="btn btn-med btn-new-full" disabled
                style="color: orangered;">Basic Details</a>
            <a href="" class="btn btn-med btn-primary">Address Details</a>
            <a href="" class="btn btn-med btn-primary">Official Details</a>
            <a href="" class="btn btn-med btn-primary">Agreement</a>
            <a href="" class="btn btn-med btn-primary">Requirements</a>
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
            <form action="{{ route('store.client') }}" method="POST">
                @csrf
                <table class="table table-striped">
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-md-4'>
                                        Company Name
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="company_name" required>
                                        @error('company_name')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-4'>
                                        Company Email ID
                                        <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="company_emailID" required>
                                        @error('company_emailID')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Company Contact No.
                                        <font color="red" size="3">*</font>
                                        <input type="number" class="form-control" name="company_contact_no" required
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        @error('company_contact_no')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Industry Type
                                        <font color="red" size="3">*</font>
                                        <select class="form-control" required name="industry_type_id">
                                            <option value="">--Select--</option>
                                            @foreach($industry_type as $it)
                                            <option value="{{ $it->id }}">{{ $it->industrytype }}</option>
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
                                        <input type="text" name="ceo" class="form-control" required>
                                        @error('ceo')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        CEO Contact no.<font color="red" size="3">*</font>
                                        <input type="number" class="form-control" name="ceo_contact"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        @error('ceo_contact')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        CEO Email ID <font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="ceo_emailID" required>
                                        @error('ceo_emailID')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        Company HR SPOC<font color="red" size="3">*</font>
                                        <input type="text" class="form-control" name="hr_spoc" required>
                                        @error('hr_spoc')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        HR Designation <font color="red" size="3">*</font>
                                        <select class="form-control" name="hr_desig" required>
                                            <option value="">--Select--</option>
                                            @foreach($designations as $d)
                                            <option value="{{ $d->id }}">{{ $d->designation }}</option>
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
                                        <input class="form-control" type="text" name="fspoc" required>
                                        @error('fspoc')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Designation <font color="red" size="3">*</font>
                                        <select name="fspoc_designation" class="form-control" required>
                                            <option value="">--Select--</option>
                                            @foreach($designations as $d)
                                            <option value="{{ $d->id }}">{{ $d->designation }}</option>
                                            @endforeach
                                        </select>
                                        @error('fspoc_designation')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-4'>
                                        Email ID <font color="red" size="3">*</font>
                                        <input type="email" class="form-control" name="fspoc_email" required>
                                        @error('fspoc_email')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>

                                    <div class='col-md-2'>
                                        Contact No. <font color="red" size="3">*</font>
                                        <input type="number" class="form-control" name="fspoc_contact" 
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10" required>
                                        @error('fspoc_contact')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-2'>
                                        Status <font color="red" size="3">*</font>
                                        <select name="client_status" class="form-control" required>
                                            <option value="">--Select--</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="Prospect">Prospect</option>
                                            <option value="Blacklisted">Blacklisted</option>
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
                                        <input type="url" class="form-control" name="website" required>
                                        @error('website')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                    <div class='col-md-6'>
                                        Comments
                                        <input type="text" class="form-control" name="comments">
                                        @error('comments')
                                        <span class="text-danger">{{ $message  }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="btn btn-primary" value="save_draft_next"
                                            name="save" title="Save and move to next step">SAVE
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