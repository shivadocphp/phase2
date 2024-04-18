@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Show Invoices</h3>
    <div class="row">
        <a href="{{ route('all.invoice') }}" class="btn btn-new">View</a>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="card">

            <div class="card-body">
                <table class="table-bordered" style="width: 100%">
                    <thead>
                        <tr>
                            <th colspan="4" style="text-align: center">INVOICE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width: 25%">Bank Name</td>
                            <td style="width: 25%">{{$company->bank}}</td>
                            <td style="width: 25%">Invoice No</td>
                            <td style="width: 25%">{{$invoice->invoice_no}}</td>
                        </tr>
                        <tr>
                            <td>Account Name</td>
                            <td>{{$company->account_name}}</td>
                            <td>Invoice Date</td>
                            <td>{{$invoice->invoice_date}}</td>
                        </tr>
                        <tr>
                            <td>Account No</td>
                            <td>{{$company->account_no}}</td>
                            <td>PAN No</td>
                            <td>{{$company->pan}}</td>
                        </tr>
                        <tr>
                            <td>IFSC Code</td>
                            <td>{{$company->ifsc}}</td>
                            <td>GST No</td>
                            <td>{{$company->gstin}}</td>
                        </tr>
                        <tr>
                            <td>Branch</td>
                            <td>{{$company->branch}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table-bordered" style="width: 100%">
                    <tbody>
                        <tr>
                            <td style="width: 50%">From</td>
                            <td style="width: 50%">To</td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                {{$company->company_name}}<br>
                                <?php $address = explode("\n", $company->company_address);
                                echo $address[0] . "<br>";
                                for ($i = 1; $i < count($address); $i++) {
                                    echo $address[$i] . "<br>";
                                }
                                ?>
                            </td>
                            <td style="width: 50%">{{$invoice->company_name}}<br>
                                {{ $invoice->city }}<br>
                                {{$invoice->state}}<br>
                                {{$invoice->country}}<br>
                                {{$invoice->pincode}}<br>GSTIN:{{$invoice->gst}}</td>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <table class="table-bordered" style="width: 100%">
                        <thead>
                            <tr style="text-align: center">
                                <th>S.No</th>
                                <th class="col-md-6">Description</th>
                                <th class="col-md-3">SAC Code</th>
                                <th class="col-md-3">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <?php $i = 0;?>
                            @foreach($invoice_detail as $detail)
                            <tr>
                                <td style="text-align: center">{{ ++$i }}</td>
                                <td>{{ $detail->description }}</td>
                                <td>{{ $detail->sac_code}}</td>
                                <td style="text-align: center;">{{ $detail->amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        {{-- </table>
                        <table class="table-bordered" style="width: 100%"> --}}
                        <tbody>
                            <tr>
                                <td class="col-md-9" style="text-align: right;" colspan="3"><b>TOTAL</b></td>
                                <td style="text-align: center;">{{ $invoice->total_amount }}</td>
                            </tr>
                            <tr>
                                <td class="col-md-9" style="text-align: right;" colspan="3">
                                    <b>CGST({{$company->cgst}}%)</b>
                                </td>
                                <td style="text-align: center;">
                                    {{ $invoice->cgst_amount }}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-9" style="text-align: right;" colspan="3">
                                    <b>SGST({{$company->sgst}}%)</b>
                                </td>
                                <td class="col-md-2" style="text-align: center;">
                                    {{ $invoice->sgst_amount }}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-9" style="text-align: right;" colspan="3">
                                    <b>IGST({{$company->igst}}%)</b>
                                </td>
                                <td class="col-md-2" style="text-align: center;">
                                    {{$invoice->igst_amount}}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-9" style="text-align: right;" colspan="3"><b>GRAND TOTAL</b></td>
                                <td class="col-md-2" style="text-align: center;">{{$invoice->grand_total}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <div class="row">
                        <!-- <div class="col-md-3">Paid Amount : {{ $invoice->paid_amount }}
                        </div>
                        <div class="col-md-3">Balance Amount : {{ $invoice->balance_amount }}
                        </div>
                        <div class="col-md-3">Payment Date : {{$invoice->payment_date}}
                        </div> -->
                        <div class="col-md-3">Status : {{$invoice->status }}
                        </div>
                    </div>
                </div>

                <h4>payment history</h4>
                <table class="table" style="background-color: #f2f2f2;">
                    <thead>
                        <tr>
                            <th>Paid Amount</th>
                            <th>Balance Amount</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $payment_history = json_decode($invoice->payment_history, true);
                        if (!empty($payment_history)){
                        foreach ($payment_history as $payment_record) {  ?>
                        <tr>
                            <td>{{ $payment_record['paid_amount'] }}</td>
                            <td>{{ $payment_record['balance_amount'] }}</td>
                            <td>{{ $payment_record['payment_date'] }}</td>
                        </tr>
                        <?php }  }?>
                    </tbody>
                </table>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12" align="center">
                            <a href="{{ route('pdf.invoice',[$invoice->id]) }}" class="btn btn-md btn-primary"
                                title="Download Invoice">Download Invoice</a>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection('admin')