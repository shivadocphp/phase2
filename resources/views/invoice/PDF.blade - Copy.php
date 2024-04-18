<!DOCTYPE html>

<html>
<head>
    <title></title>

</head>

<body>

<table  style="width: 100%; border-collapse: collapse;">
    <thead  border="0">
        <tr><th colspan="4" style="text-align: center">

            <img src="{{ public_path('jsc_logo.png') }}">
        </th></tr>
    <tr>
        <th colspan="4" style="text-align: center">INVOICE</th>
    </tr>
    </thead>
    <tbody style="border-color:black">
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
</table><br>
<table  style="width: 100%; border-collapse: collapse;" border="1">
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
<br>

<div>
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
        <tr style="text-align: center">
            <th class="col-md-1">S.No</th>
            <th class="col-md-6">Description</th>
             <th class="col-md-">SAC Code</th>
            <th class="col-md-2">Amount</th>

        </tr>
        </thead>
        <tbody id="tbody">
        <?php $i = 0;?>
        @foreach($invoice_detail as $detail)
            <tr>
                <td style="text-align: center">{{ ++$i }}</td>
                <td>{{ $detail->description }}</td>
                <td style="text-align: center;">{{ $detail->amount }}</td>


            </tr>
        @endforeach
        </tbody>
    </table><br>
    <table border="1" style="width: 100%; border-collapse: collapse;" >
        <tbody>
        <tr>
            <td class="col-md-9" style="text-align: right;"><b>TOTAL</b></td>
            <td class="col-md-2" style="text-align: center;">{{ $invoice->total_amount }}</td>

        </tr>

        <tr>
            <td class="col-md-9" style="text-align: right;"><b>CGST({{$company->cgst}}%)</b>
            /td>
            <td class="col-md-2" style="text-align: center;">
                {{ $invoice->cgst_amount }}
            </td>

        </tr>
        <tr>
            <td class="col-md-9" style="text-align: right;"><b>SGST({{$company->sgst}}%)</b>
            </td>
            <td class="col-md-2"  style="text-align: center;">
                {{ $invoice->sgst_amount }}
            </td>

        </tr>
        <tr>
            <td class="col-md-9" style="text-align: right;"><b>IGST({{$company->igst}}%)</b>
            </td>
            <td class="col-md-2" style="text-align: center;">
                {{$invoice->igst_amount}}
            </td>

        </tr>
        <tr>
            <td class="col-md-9" style="text-align: right;"><b>GRAND TOTAL</b></td>
            <td class="col-md-2" style="text-align: center;">{{$invoice->grand_total}}</td>

        </tr>

        </tbody>

    </table>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-3">Paid Amount : {{ $invoice->paid_amount }}
        </div>
        <div class="col-md-3">Balance Amount : {{ $invoice->balance_amount }}
        </div>
        <div class="col-md-3">Payment Date : {{$invoice->payment_date}}
        </div>
        <div class="col-md-3">
            Status : {{$invoice->status }}

        </div>

    </div>
</div>
</body>
</html>
