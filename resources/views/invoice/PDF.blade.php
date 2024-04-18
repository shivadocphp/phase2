<!DOCTYPE html>

<html>
<head>
    <title></title>
 <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
            }

            header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                

                /** Extra personal styles **/
                
                
                text-align: center;
                
            }

            footer {
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
              
                    font-size: smaller;
                /** Extra personal styles **/
                
                
                text-align: center;
                
            }

table {
    border-left: 0.01em solid #ccc;
    border-right: 0;
    border-top: 0.01em solid #ccc;
    border-bottom: 0;
    border-collapse: collapse;
}
table td,
table th {
    border-left: 0;
    border-right: 0.01em solid #ccc;
    border-top: 0;
    border-bottom: 0.01em solid #ccc;
}
        </style>
</head>

<body>

<header>
            <img src="{{ public_path('jsc_logo.png') }}"><hr>
        INVOICE<br></header>


<br><br><br><br>
      <!-- Invoice -->
<main><p><table  style="width: 100%; "  cellspacing="4" cellpadding="4">
    <tbody >
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
        <td rowspan="2">GST No</td>
        <td rowspan="2">{{$company->gstin}}</td>
    </tr>
    <tr>
        <td >Branch</td>
        <td>{{$company->branch}}</td>


    </tr>

    
    </tbody>
</table><br>
<table  style="width: 100%;"  cellspacing="4" cellpadding="4">
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
    <table style="width: 100%;" cellspacing="4" cellpadding="4">
        <thead>
        <tr style="text-align: center">
            <th class="col-md-1">S.No</th>
            <th class="col-md-5">Description</th>
             <th class="col-md-3">SAC Code</th>
            <th class="col-md-2">Amount</th>

        </tr>
        </thead>
        <tbody id="tbody">
        <?php $i = 0;?>
        @foreach($invoice_detail as $detail)
            <tr>
                <td style="text-align: center">{{ ++$i }}</td>
                <td>{{ $detail->description }}</td>
                <td>{{ $detail->sac_code }}</td>
                <td style="text-align: center;">{{ $detail->amount }}</td>


            </tr>
        @endforeach
        <tr>
            <td colspan="3"  style="text-align: right;">Sub Total</td>
            <td  style="text-align: center;">{{ $invoice->total_amount }}</td>

        </tr>

        <tr>
            <td colspan="3" style="text-align: right;">CGST ({{$company->cgst}}%)
            </td>
            <td  style="text-align: center;">
                {{ $invoice->cgst_amount }}
            </td>

        </tr>
        <tr>
            <td colspan="3" style="text-align: right;">SGST ({{$company->sgst}}%)
            </td>
            <td   style="text-align: center;">
                {{ $invoice->sgst_amount }}
            </td>

        </tr>
        <tr>
            <td colspan="3" style="text-align: right;">IGST ({{$company->igst}}%)
            </td>
            <td  style="text-align: center;">
                {{$invoice->igst_amount}}
            </td>

        </tr>
        <tr>
            <td colspan="3" style="text-align: right;"><b>GRAND TOTAL<br></b> (In words: {{$grand_total}} Only)</td>
            <td  style="text-align: center;"><b><p><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{$invoice->grand_total}}</p></b></td>

        </tr>

        </tbody>

    </table>
</div>
<!--<div class="form-group">
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
</div>-->
<br>
<div class="form-group">
    <div class="row">
        <b>For,<br>
         Job Store Consulting.</b>

    </div>
</div></main></p>
<footer>
           <hr> Address : 
            <?php $address = explode("\n", $company->company_address);
            echo $address[0] . ", ";
            for ($i = 1; $i < count($address); $i++) {
                echo $address[$i] . ", ";
            }
            ?><br>
Phone Number : {{$company->landline_no }} / Email : info@jobstorec.com
        </footer>
 
</body>
</html>
