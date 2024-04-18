@extends('admin.admin_master')
@section('admin')
    <div class="page-header">
        <h3 class="page-title">Backend/Company </h3>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @if(session('success'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>{{ session('success') }}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="card-header">
                        <span>Company Details</span>
                    </div>
                    <div class="card-body">
                        <table class="table-bordered" width="100%">
                            <thead>
                            <tr style="text-align: center">
                                <th scope="col-md-2">Company Name</th>
                                <th scope="col-md-4">Address</th>
                                <th scope="col">GSTIN</th>
                                <th scope="col">CGST</th>
                                <th scope="col">SGST</th>
                                <th scope="col">IGST</th>
                                <th scope="col">PAN</th>
                                <th scope="col-md-2">Bank Details</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            @foreach($company as $c)
                                <tr>
                                    <td>{{ $c->company_name }}</td>
                                    <td><?php  $address = explode(",",$c->company_address);
                                    for($i=0;$i<count($address);$i++){?>
                                    {{ $address[$i] }},
<?php  } ?>
                                    Contact: {{$c->landline}}/{{$c->mobile_no}}<br>
                                    Email : {{$c->email_id}}
                                    </td>
                                    <td style="text-align:center;">{{ $c->gstin }}</td>
                                    <td style="text-align:center;">{{ $c->cgst }}</td>
                                    <td style="text-align:center;">{{ $c->sgst }}</td>
                                    <td style="text-align:center;">{{ $c->igst }}</td>

                                    <td>{{$c->pan}}</td>
                                    <td>Bank:{{$c->bank}}<br>
                                    Account_name:{{$c->account_name}}<br>
                                    Account_no:{{$c->accoutn_no}}<br>
                                        Ifsc:{{$c->ifsc}}
                                        Branch:{{$c->branch}}
                                    </td>
                                    <td><a href="" class="" data-company_name="{{$c->company_name }}"
                                           data-id="{{ $c->id }}" data-email_id="{{ $c->email_id}}"
                                           data-company_address="{{$c->company_address}}" data-gstin="{{$c->gstin}}"
                                           data-cgst="{{$c->cgst}}" data-sgst="{{$c->sgst}}" data-igst="{{$c->igst}}"
                                           data-landline_no="{{$c->landline_no}}" data-mobile_no="{{ $c->mobile_no}}"
                                           data-pan="{{$c->pan}}" data-bank="{{ $c->bank }}" data-account_name="{{$c->account_name}}"
                                           data-account_no="{{$c->account_no}}"
                                           data-ifsc="{{$c->ifsc}}" data-branch="{{$c->branch}}"
                                           data-toggle="modal" data-target="#edit"><i class="fa fa-edit green" ></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span>ADD </span>
                    </div>
                    <div class="card-body">
                        <form action="{{route('store.company')}}" method="POST">

                            {{csrf_field()}}
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Company Name</label>
                                        <input type="text" name="company_name" class="form-control"
                                               id="company_name">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Email Id</label>
                                        <input type="email" name="email_id" class="form-control"
                                               id="email_id">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Address</label>
                                        <textarea name="company_address" class="form-control"
                                                  id="company_address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Landline</label>
                                        <input type="text" name="landline_no" class="form-control" >
                                    </div>
                                    <div class="col-md-2">
                                        <label>Mobile No.</label>
                                        <input type="text" name="mobile_no" class="form-control" >
                                    </div>
                                    <div class="col-md-2">
                                        <label>PAN</label>
                                        <input type="text" name="pan" class="form-control" >
                                    </div>
                                    <div class="col-md-2">
                                        <label>GSTIN</label>
                                        <input type="text" name="gstin" class="form-control" >
                                    </div>
                                    <div class="col-md-1">
                                        <label>CGST(%)</label>
                                        <input type="number" name="cgst" class="form-control" >
                                    </div>
                                    <div class="col-md-1">
                                        <label>SGST(%)</label>
                                        <input type="number" name="sgst" class="form-control" >
                                    </div>
                                    <div class="col-md-1">
                                        <label>IGST(%)</label>
                                        <input type="number" name="igst" class="form-control" >
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Bank Name</label>
                                        <input type="text" name="bank" class="form-control" >
                                    </div>
                                    <div class="col-md-6">
                                        <label>Account Name</label>
                                        <input type="text" name="account_name" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Account No</label>
                                        <input type="number" name="account_no" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>IFSC Code</label>
                                        <input type="text" name="ifsc" class="form-control" >
                                    </div>
                                    <div class="col-md-3">
                                        <label>Branch </label>
                                        <input type="text" name="branch" class="form-control" >
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col" align="center">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>

                            </div>


                        </form>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h4 class="modal-title" id="myModalLabel">Edit Company</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('update.company')}}" method="POST">
                            {{method_field('patch')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input type="hidden" name="id" value="" id="id">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Company Name</label>
                                            <input type="text" name="company_name" class="form-control"
                                                   id="company_name">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Email Id</label>
                                            <input type="email" name="email_id" class="form-control"
                                                      id="email_id"></textarea>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Address</label>
                                            <textarea name="company_address" class="form-control"
                                                      id="company_address"></textarea>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Pan</label>
                                            <input type="text" name="pan" class="form-control"
                                                   id="pan">
                                        </div>
                                    </div>

                                </div>


                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>GSTIN</label>
                                            <input type="text" name="gstin" class="form-control" id="gstin">
                                        </div>
                                        <div class="col-md-1">
                                            <label>CGST(%)</label>
                                            <input type="text" name="cgst" class="form-control" id="cgst">
                                        </div>
                                        <div class="col-md-1">
                                            <label>SGST(%)</label>
                                            <input type="text" name="sgst" class="form-control" id="sgst">
                                        </div>
                                        <div class="col-md-1">
                                            <label>IGST(%)</label>
                                            <input type="text" name="igst" class="form-control" id="igst">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Landline</label>
                                            <input type="text" name="landline_no" class="form-control" id="landline_no">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Mobile No.</label>
                                            <input type="text" name="mobile_no" class="form-control" id="mobile_no">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Bank Name</label>
                                            <input type="text" name="bank" class="form-control" id="bank">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Account Name</label>
                                            <input type="text" name="account_name" class="form-control" id="account_name">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Account No</label>
                                            <input type="number" name="account_no" class="form-control" id="account_no">
                                        </div>
                                        <div class="col-md-3">
                                            <label>IFSC Code</label>
                                            <input type="text" name="ifsc" class="form-control" id="ifsc">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Branch </label>
                                            <input type="text" name="branch" class="form-control" id="branch">
                                        </div>

                                    </div>
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>


    </div>

    <script src="{{asset('js/app.js')}}"></script>
    <script>
        $('#edit').on('show.bs.modal', function (event) {
            // console.log('Modal Opened');
            var button = $(event.relatedTarget) // Button that triggered the modal
            var company_name = button.data('company_name')
            var company_address = button.data('company_address')
            var gstin = button.data('gstin')
            var cgst = button.data('cgst')
            var sgst = button.data('sgst')
            var igst = button.data('igst')
            var email_id = button.data('email_id')
            var landline_no = button.data('landline_no')
            var mobile_no = button.data('mobile_no')
            var id = button.data('id')
            var pan = button.data('pan')
            var ifsc=button.data('ifsc')
            var branch=button.data('branch')
            var bank = button.data('bank')
            var account_name=button.data('account_name')
            var account_no = button.data('account_no')
            var modal = $(this)

            modal.find('.modal-body #company_name').val(company_name)
            modal.find('.modal-body #id').val(id)
            modal.find('.modal-body #company_address').val(company_address)
            modal.find('.modal-body #gstin').val(gstin)
            modal.find('.modal-body #cgst').val(cgst)
            modal.find('.modal-body #sgst').val(sgst)
            modal.find('.modal-body #igst').val(igst)
            modal.find('.modal-body #email_id').val(email_id)
            modal.find('.modal-body #landline_no').val(landline_no)
            modal.find('.modal-body #mobile_no').val(mobile_no)
            modal.find('.modal-body #pan').val(pan)
            modal.find('.modal-body #ifsc').val(ifsc)
            modal.find('.modal-body #branch').val(branch)
            modal.find('.modal-body #bank').val(bank)
            modal.find('.modal-body #account_name').val(account_name)
            modal.find('.modal-body #account_no').val(account_no)
        })
    </script>

    <style>
        @media screen and (min-width: 676px) {
            .modal-dialog {
                max-width: 1000px; /* New width for default modal */
            }
        }
    </style>
@endsection('admin')

