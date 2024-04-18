@extends('admin.admin_master')
<style>
    .table {
    border-collapse: collapse;
    width: 100%;
}

.table th, .table td {
    border: 1px solid #ddd;
    padding: 8px;
}

.table th {
    background-color: #f2f2f2;
    text-align: left;
}

</style>
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Edit Invoices</h3>
    <div class="row">
        <a href="{{ route('all.invoice') }}" class="btn btn-primary">View</a>
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
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <div class="card-body">
                <form method="POST" action="{{route('update.invoice',$invoice->id)}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">Invoice No.
                                <input type="text" name="invoice_code" class="form-control"
                                    value="{{$invoice->invoice_no}}" required readonly>
                            </div>
                            <div class="col-md-4">Invoice Date
                                <input type="date" name="invoice_date" value="{{ $invoice->invoice_date }}"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-4">Invoice Type
                                <select class="form-control" name="invoice_type" id="invoice_type" required>
                                    <option value="">--Select Invoice type</option>
                                    <option value="GST" <?php
                                            if($invoice->invoice_type=="GST") echo "selected";
                                            ?>>GST</option>
                                    <option value="Without GST" <?php
                                            if($invoice->invoice_type=="Without GST") echo "selected";
                                            ?>>Without GST</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">Client
                                <select class="form-control" name="client_id" id="client_id" required>
                                    <option value="">--Select Client</option>
                                    @foreach($clients as $client)
                                    <option value="{{$client->id}}" <?php
                                                    if($invoice->client_id == $client->id){ echo "selected";}?>>
                                        {{ $client->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">Address
                                <select class="form-control" name="location" id="location" required>
                                    <option value="">--Select Address</option>
                                    <?php if ($client_address != null) { ?>
                                    @foreach($client_address as $ca)
                                    <option value="{{$ca->id}}"
                                        <?php  if($invoice->client_address_id==$ca->id){ echo "selected";}?>>
                                        {{ $ca->address.",".$ca->city.",".$ca->state }}</option>
                                    @endforeach
                                    <?php  } ?>
                                </select>
                            </div>

                            <div id="state_id">
                                <input type="hidden" value="{{$state_id}}" id="state" name="state">
                            </div>
                        </div>
                    </div>
                    <div>
                        <table class="table-bordered">
                            <thead>
                                <tr style="text-align: center">
                                    <th>S.No</th>
                                    <th class="col-md-6">Description</th>
                                    <th>SAC Code</th>
                                    <th>Amount</th>
                                    <th><a href="#" class="btn addRow">
                                            <i class="fa fa-plus" style="color: green"></i></a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <?php $i=0;?>
                                @foreach($invoice_detail as $detail)
                                <tr>
                                    <td style="text-align: center;">{{ ++$i }}<input type="hidden" name="sno[]" id="sno"
                                            value="1"></td>
                                    <td><input type="text" name="description[]" id="description"
                                            value="{{ $detail->description }}" class="form-control" required></td>
                                    <td><input type="text" name="sac_code[]" id="sac_code"
                                            value="{{ $detail->sac_code }}" class="form-control" required></td>

                                    <td><input type="text" name="amount[]" id="amount" value="{{ $detail->amount }}"
                                            class="form-control numberonly" onkeyup="sum()" required></td>
                                    <td style="text-align: center"><a href="#" class="btn remove"><i
                                                class="fa  fa-trash" style="color:red"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                <!-- <tr>
                                    <td style="text-align: center">{{++$i}}<input type="hidden" name="sno[]" id="sno"
                                            value="1"></td>
                                    <td><input type="text" name="description[]" id="description" class="form-control"
                                            required></td>
                                    <td><input type="text" name="sac_code[]" id="sac_code"
                                            value="{{ $detail->sac_code }}" class="form-control" required></td>

                                    <td><input type="number" name="amount[]" id="amount" class="form-control"
                                            onkeyup="sum()" required></td>
                                    <td style="text-align: center"><a href="#" class="btn remove"><i
                                                class="fa  fa-trash" style="color:red"></i></a>
                                    </td>
                                </tr> -->
                            </tbody>
                            {{-- </table>
                            <table class="table-bordered">
                                <tbody> --}}
                            <tr>
                                <td class="col-md-9" style="text-align: right;" colspan="3"><b>TOTAL</b></td>
                                <td><input type="text" name="total_amount" id="total_amount" class="form-control"
                                        value="{{ $invoice->total_amount }}"></td>
                                <td class="col-md-1"></td>
                            </tr>
                            <tr>
                                <td class="col-md-9" style="text-align: right;" colspan="3">
                                    <b>CGST({{$company->cgst}}%)</b>
                                </td>
                                <td>
                                    <input type="hidden" name="cgst_percentage" id="cgst_percentage"
                                        value="{{$company->cgst}}">
                                    <input type="text" name="cgst_amount" id="cgst_amount" class="form-control"
                                        value="{{ $invoice->cgst_amount }}">
                                </td>
                                <td class="col-md-1"></td>
                            </tr>
                            <tr>
                                <td class="col-md-9" style="text-align: right;" colspan="3">
                                    <b>SGST({{$company->sgst}}%)</b>
                                </td>
                                <td>
                                    <input type="hidden" name="sgst_percentage" id="sgst_percentage"
                                        value="{{$company->sgst}}">
                                    <input type="text" name="sgst_amount" id="sgst_amount" class="form-control"
                                        value="{{ $invoice->sgst_amount }}">
                                </td>
                                <td class="col-md-1"></td>
                            </tr>
                            <tr>
                                <td class="col-md-9" style="text-align: right;" colspan="3">
                                    <b>IGST({{$company->igst}}%)</b>
                                </td>
                                <td>
                                    <input type="hidden" name="igst_percentage" id="igst_percentage"
                                        value="{{$company->igst}}">
                                    <input type="text" name="igst_amount" id="igst_amount" class="form-control"
                                        value="{{$invoice->igst_amount}}">
                                </td>
                                <td class="col-md-1"></td>
                            </tr>
                            <tr>
                                <td class="col-md-9" style="text-align: right;" colspan="3"><b>GRAND TOTAL</b></td>
                                <td><input type="text" name="grand_total" id="grand_total" class="form-control"
                                        value="{{$invoice->grand_total}}" readonly></td>
                                <td class="col-md-1"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">Paid Amount
                                <input type="number" class="form-control" name="paid_amount">
                            </div>
                            <div class="col-md-3">Balance Amount
                                <input type="number" class="form-control" name="balance_amount">
                            </div>
                            <div class="col-md-3">Payment Date
                                <input type="date" class="form-control" name="payment_date">
                            </div>
                            <div class="col-md-3">
                                Status
                                <select name="status" class="form-control">
                                    <option value="Unpaid" <?php  if($invoice->status=="Unpaid"){ echo "selected";}?>>
                                        Unpaid</option>
                                    <option value="Paid" <?php  if($invoice->status=="Paid"){ echo "selected";}?>>Paid
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12" align="center">
                                <button type="submit" name="update_invoice" value="update"
                                    class="btn btn-md btn-primary">Update Invoice</button>
                                <button type="submit" name="update_history" value="update2"
                                    class="btn btn-md btn-primary">Add Payment history</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
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
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$('.addRow').on('click', function() {
    addRow();
});

function addRow() {
    var no = $('input[id="amount"]').length + 1
    var tr = "<tr>" +
        "<td style='text-align: center'>" + no + "<input type='hidden' name='sno[]' value='" + no + "'></td>" +
        "<td><input type='text' name='description[]' id='description' class='form-control' required></td>" +
        "<td><input type='text' name='sac_code[]'' id='sac_code' class='form-control' ></td>" +
        "<td><input type='text' name='amount[]' id='amount' onkeyup='sum()' class='form-control numberonly' required></td>" +
        "<td style='text-align: center'><a href='#' class='btn remove'><i class='fa  fa-trash' style='color:red'></i></a></td>" +
        "</tr>";
    $('#tbody').append(tr);
}

$('tbody').on('click', '.remove', function() {
    $(this).parent().parent().remove();
});

// jQuery button click event to add a row.

// function sum() {
//     /*var no = $('input[name="amount[]"]').length + 1*/
//     var arr = document.getElementsByName('amount[]');

//     var tot = 0;
//     for (var i = 0; i < arr.length; i++) {
//         if (parseInt(arr[i].value))
//             tot += parseInt(arr[i].value);
//     }
//     var sgst_percentage = document.getElementById('sgst_percentage').value;
//     var cgst_percentage = document.getElementById('cgst_percentage').value;
//     var igst_percentage = document.getElementById('igst_percentage').value;
//     var sgst = tot * (parseInt(sgst_percentage) / 100);
//     var cgst = tot * (parseInt(cgst_percentage) / 100);
//     var igst = tot * (parseInt(igst_percentage) / 100);
//     document.getElementById('total_amount').value = tot;
//     var state = document.getElementById('state').value;
//     var invoice_type = document.getElementById('invoice_type').value;
//     if (invoice_type == "GST") {
//         if (state == "Tamil Nadu") {
//             document.getElementById('cgst_amount').value = cgst;
//             document.getElementById('sgst_amount').value = sgst;
//             document.getElementById('igst_amount').value = 0;
//             var grand_total = tot + cgst + sgst;
//             document.getElementById('grand_total').value = grand_total;
//         } else {
//             document.getElementById('cgst_amount').value = 0;
//             document.getElementById('sgst_amount').value = 0;
//             document.getElementById('igst_amount').value = igst;
//             var grand_total = tot + igst;
//             document.getElementById('grand_total').value = grand_total;
//         }
//     } else {
//         document.getElementById('cgst_amount').value = 0;
//         document.getElementById('sgst_amount').value = 0;
//         document.getElementById('igst_amount').value = 0;
//         document.getElementById('grand_total').value = tot;
//     }
// }


//in jquery
function sum() {
    console.log('roll');
    var arr = $('input[name="amount[]"]');

    var tot = 0;
    arr.each(function() {
        if (parseInt($(this).val()))
            tot += parseInt($(this).val());
    });

    var sgst_percentage = $('#sgst_percentage').val();
    var cgst_percentage = $('#cgst_percentage').val();
    var igst_percentage = $('#igst_percentage').val();
    var sgst = tot * (parseInt(sgst_percentage) / 100);
    var cgst = tot * (parseInt(cgst_percentage) / 100);
    var igst = tot * (parseInt(igst_percentage) / 100);

    $('#total_amount').val(tot);
    var state = $('#state').val();
    var invoice_type = $('#invoice_type').val();

    if (invoice_type == "GST") {
        if (state == "Tamil Nadu") {
            $('#cgst_amount').val(cgst);
            $('#sgst_amount').val(sgst);
            $('#igst_amount').val(0);
            var grand_total = tot + cgst + sgst;
            $('#grand_total').val(grand_total);
        } else {
            $('#cgst_amount').val(0);
            $('#sgst_amount').val(0);
            $('#igst_amount').val(igst);
            var grand_total = tot + igst;
            $('#grand_total').val(grand_total);
        }
    } else {
        $('#cgst_amount').val(0);
        $('#sgst_amount').val(0);
        $('#igst_amount').val(0);
        $('#grand_total').val(tot);
    }
}



$(document).ready(function() {
    $('#client_id').on('change', function(e) {
        e.preventDefault();
        var idlevel = this.value;
        $("#location").html('');
        $.ajax({
            url: "{{url('api/fetch-clientlocation')}}",
            type: "POST",
            data: {
                client_id: idlevel,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                // $('#location').html('<option value="">Select location</option>');
                // $.each(result.location, function(key, value) {
                //     $("#location").append('<option value="' + value
                //         .id + '">' + value.address + ',' + value.state + ',' +
                //         value.city + '</option>');
                // });

                var selectedLocationId =
                    <?php echo json_encode($invoice->client_address_id); ?>;
                $('#location').html('<option value="">Select location</option>');
                $.each(result.location, function(key, value) {
                    var selectedAttribute = value.id == selectedLocationId ?
                        'selected' : '';
                    // alert(selectedAttribute);
                    $("#location").append('<option value="' + value.id + '" rel="' +
                        value.id + '" ' +
                        selectedAttribute + '>' +
                        value.address + ',' + value.state + ',' + value.city +
                        '</option>');

                    console.log(value.state);
                    $('#state_id').html('<input type="hidden" value="' + value
                        .state +
                        '" id="state" name="state">');
                });
            }
        });
    });
});

// $(document).ready(function() {
$('#location').on('change', function(e) {
    e.preventDefault();
    var idlevel = this.value;
    // var idlevel = <?php 
    // echo json_encode($invoice->client_address_id); 
    ?>
    $("#state_id").html('');
    $.ajax({
        url: "{{url('api/fetch-invoicelocation')}}",
        type: "POST",
        data: {
            address_id: idlevel,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function(result) {
            console.log(result.state);
            $('#state_id').html('<input type="hidden" value="' + result.state +
                '" id="state" name="state">');
        }
    });
});
// });

// added for the calculation

$('#invoice_type').change(function() {
    sum();
    var selectedOption = $(this).val();
    if (selectedOption === "GST") {
        $('#cgst_amount').prop('disabled', false);
        $('#sgst_amount').prop('disabled', false);
        $('#igst_amount').prop('disabled', false);
    } else {
        $('#cgst_amount').prop('disabled', true);
        $('#sgst_amount').prop('disabled', true);
        $('#igst_amount').prop('disabled', true);
    }
});

$('#location').change(function() {
    $('#invoice_type').val('');
});


$(document).on('input', '.numberonly', function() {
    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
});
</script>
@endsection('admin')