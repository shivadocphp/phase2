@extends('admin.admin_master')
@section('admin')
<div class="page-header">
    <h3 class="page-title"> Add Invoices</h3>
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
                <form method="POST" action="{{route('store.invoice')}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">Invoice No.
                                <input type="text" name="invoice_code" class="form-control" value="{{$invoice_code}}"
                                    required readonly>
                                <input type="hidden" name="code" value="{{$count}}">
                            </div>
                            <div class="col-md-4">Invoice Date
                                <input type="date" name="invoice_date" class="form-control" required>
                            </div>
                            <div class="col-md-4">Invoice Type
                                <select class="form-control" name="invoice_type" id="invoice_type" required>
                                    <option value="">--Select Invoice type</option>
                                    <option value="GST">GST</option>
                                    <option value="Without GST">Without GST</option>
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
                                    <option value="{{$client->id}}">{{ $client->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">Address
                                <select class="form-control" name="location" id="location" required>
                                    <option value="">--Select Address</option>

                                </select>
                            </div>
                            <div id="state_id">
                            </div>
                        </div>
                    </div>
                    <div>
                        <table class="table-bordered">
                            <thead>
                                <tr style="text-align: center">
                                    <th class="col-md-1">S.No</th>
                                    <th class="col-md-5">Description</th>
                                    <th class="col-md-3">SAC Code</th>
                                    <th class="col-md-2">Amount</th>
                                    <th class="col-md-1"><a href="#" class="btn addRow"><i class="fa fa-plus"
                                                style="color: green"></i></a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <tr>
                                    <td style="text-align: center">1.<input type="hidden" name="sno[]" id="sno"
                                            value="1"></td>
                                    <td><input type="text" name="description[]" id="description" class="form-control"
                                            required></td>
                                    <td><input type="text" name="sac_code[]" id="sac_code" class="form-control"></td>
                                    <td><input type="text" name="amount[]" id="amount" class="form-control numberonly"
                                            onkeyup="sum()" required></td>
                                    <td style="text-align: center"><a href="#" class="btn remove"><i
                                                class="fa  fa-trash" style="color:red"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table-bordered">
                            <tbody>
                                <tr>
                                    <td class="col-md-9" style="text-align: right;"><b>TOTAL</b></td>
                                    <td class="col-md-2"><input type="text" name="total_amount" id="total_amount"
                                            class="form-control"></td>
                                    <td class="col-md-1"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-9" style="text-align: right;"><b>CGST({{$company->cgst}}%)</b>
                                    </td>
                                    <td class="col-md-2">
                                        <input type="hidden" name="cgst_percentage" id="cgst_percentage"
                                            value="{{$company->cgst}}">
                                        <input type="text" name="cgst_amount" id="cgst_amount" class="form-control">
                                    </td>
                                    <td class="col-md-1"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-9" style="text-align: right;"><b>SGST({{$company->sgst}}%)</b>
                                    </td>
                                    <td class="col-md-2">
                                        <input type="hidden" name="sgst_percentage" id="sgst_percentage"
                                            value="{{$company->sgst}}">
                                        <input type="text" name="sgst_amount" id="sgst_amount" class="form-control">
                                    </td>
                                    <td class="col-md-1"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-9" style="text-align: right;"><b>IGST({{$company->igst}}%)</b>
                                    </td>
                                    <td class="col-md-2">
                                        <input type="hidden" name="igst_percentage" id="igst_percentage"
                                            value="{{$company->igst}}">
                                        <input type="text" name="igst_amount" id="igst_amount" class="form-control">
                                    </td>
                                    <td class="col-md-1"></td>
                                </tr>
                                <tr>
                                    <td class="col-md-9" style="text-align: right;"><b>GRAND TOTAL</b></td>
                                    <td class="col-md-2"><input type="text" name="grand_total" id="grand_total"
                                            class="form-control" readonly></td>  
                                    <td class="col-md-1"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12" align="center">
                                <button type="submit" class="btn btn-md btn-primary">Generate Invoice</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </form>
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

//jQuery button click event to add a row.
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
function sum(){
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
    $('#client_id').on('change', function() {
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
                $('#location').html('<option value="">Select location</option>');
                $.each(result.location, function(key, value) {
                    $("#location").append('<option value="' + value
                        .id + '">' + value.address + ',' + value.state + ',' +
                        value.city + '</option>');
                });
            }
        });
    });
});

$(document).ready(function() {
    $('#location').on('change', function() {
        var idlevel = this.value;
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
});

// added for the calculation
$(document).ready(function() {
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
});



// to get the input field number
// $(document).ready(function () {
//     $('.numberonly').keypress(function (e) {
//         var charCode = (e.which) ? e.which : event.keyCode
//         if (String.fromCharCode(charCode).match(/[^0-9]/g))
//             return false;
//     });
// });
$(document).on('input', '.numberonly', function() {
    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
});
</script>
@endsection('admin')