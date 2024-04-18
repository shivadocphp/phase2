<?php

namespace App\Http\Controllers;

use App\Models\Client_address;
use App\Models\Client_basic_details;
use App\Models\company;
use App\Models\Emp_code;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables;
use PDF;
// use Mail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Notifications\NewInvoiceNotification;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client_basic_details::where('client_status', 'Active')
            ->orderBy('id')
            ->get();
        return view('invoice.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $invoice = Emp_code::where('type', 'invoice_code')->first();
        $count = $invoice->emp_code + 1;
        $current_month = date('m');
        $year = 0;
        $year1 = 0;
        if ($current_month > 3) {
            $year = date('Y');
            $year1 = date('y') + 1;
        } else {
            $year = date('Y') - 1;
            $year1 = date('y');
        }

        ($count > 9) ? $decimal = '0' : $decimal = '00';
        ($count > 99) ? $decimal = '' : '';
        // $data['invoice_no'] = 'JSC/20-21/'.$decimal.''.($inNo + 1);


        $invoice_code = $invoice->prefix . "/" . $year . "-" . $year1 . "/" . $decimal . "" . $count;
        $company = company::where('id', 1)->first();
        $clients = Client_basic_details::all();
        return view('invoice.create', compact('clients', 'invoice_code', 'company', 'count'));
    }


    public function location(Request $request)
    {
        $data = Client_address::where('id', $request->address_id)->first();
        // print_r($data);exit();
        $data1['state'] = State::where("id", $data->state_id)->pluck('state');
        return response()->json($data1);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // print_r($request->all());exit();
        try {
            DB::beginTransaction();
            $input['invoice_no'] = $request['invoice_code'];
            $input['invoice_date'] = $request['invoice_date'];
            $input['invoice_type'] = $request['invoice_type'];
            $input['client_id'] = $request['client_id'];
            $input['client_address_id'] = $request['location'];
            //$input['state'] = $request['state'];
            $description = $request['description'];
            $sac_code = $request['sac_code'];
            $amount = $request['amount'];
            $input['total_amount'] = $request['total_amount'];
            $input['cgst_amount'] = $request['cgst_amount'];
            $input['sgst_amount'] = $request['sgst_amount'];
            $input['igst_amount'] = $request['igst_amount'];
            $input['grand_total'] = $request['grand_total'];
            $description = $request['description'];
            $amount = $request['amount'];
            $code = $request['code'];
            $input['added_by'] = Auth::user()->id;
            $input['created_at'] = Carbon::now();
            $input['status'] = "Unpaid";

            //  $input = $request->except(['_token', 'description', 'amount', 'code', 'cgst_percentage', 'sgst_percentage', 'igst+percentage', 'state']);
            $i = Invoice::insertGetId($input);
            $k = 0;
            $l = 0;
            if ($i > 0) {


                // candidate notification
                $users = $this->getUsersWithPermission('Invoice Notification');
                if ($users) {
                    $get_invoice = Invoice::find($i);
                    // print_r($get_client);exit();
                    // return $users;
                    foreach ($users as $user) {
                        $notification = new NewInvoiceNotification($get_invoice);
                        $user->notify($notification);
                    }
                }


                $description_count = count($description);
                $amount_count = count($amount);
                if ($description_count == $amount_count) {
                    $in['invoice_id'] = $i;
                    for ($j = 0; $j < $description_count; $j++) {
                        $in['description'] = $description[$j];
                        $in['sac_code'] = $sac_code[$j];
                        $in['amount'] = $amount[$j];
                        $in['added_by'] = Auth::user()->id;
                        $in['created_at'] = Carbon::now();
                        $k = InvoiceDetail::insertGetId($in);
                    }
                    if ($k > 0) {
                        $updateDetails = ['emp_code' => $code];
                        //print_r($updateDetails);
                        $l = Emp_code::where('id', 3)->update($updateDetails);

                        if ($l > 0) {
                            DB::commit();
                            //  $clients = Client_basic_details::all();
                            return redirect()->route('all.invoice')->with('success', 'Invoice generated successfully');
                            // return view('invoice.index', compact('clients'))->with('success', 'Invoice generated successfully');
                        } else {
                            DB::rollBack();
                            return Redirect()->back()->with('error', 'Invoice generated unsuccessfully');
                        }
                    } else {
                        DB::rollBack();
                        return Redirect()->back()->with('error', 'Invoice generated unsuccessfully');
                    }
                } else {
                    DB::rollBack();
                    return Redirect()->back()->with('error', 'Enter description and its corresponding amount');
                }
            } else {
                DB::rollBack();
                return Redirect()->back()->with('error', 'Invoice generation failedi');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('error', 'Invoice generation failed' . $e);
        }
    }
    function number_to_word($amount)
    {

        $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
        // Check if there is any number after decimal
        $amt_hundred = null;
        $count_length = strlen($num);
        $x = 0;
        $string = array();
        $change_words = array(
            0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
        );
        $here_digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($x < $count_length) {
            $get_divider = ($x == 2) ? 10 : 100;
            $amount = floor($num % $get_divider);
            $num = floor($num / $get_divider);
            $x += $get_divider == 10 ? 1 : 2;
            if ($amount) {
                $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
                $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
                $string[] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . '
            ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . '
            ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
            } else $string[] = null;
        }
        $implode_to_Rupees = implode('', array_reverse($string));
        $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . "
        " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
        return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
    }

    public function getInvoices(Request $request)
    {
        $client_id = null;
        $status = null;
        $from_date = null;
        $to_date = null;
        $invoices = Invoice::join('client_basic_details', 'client_basic_details.id', 'invoices.client_id')
            ->join('client_addresses', 'client_addresses.id', 'invoices.client_address_id')
            ->select('client_basic_details.company_name', 'client_basic_details.client_code', 'invoices.*')
            ->orderBy('id', 'DESC')
            ->get();

        if (request()->ajax()) {
            $client_id = $request->client_id;
            $status = $request->status;
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            if ($client_id != null) {
                $invoices = Invoice::join('client_basic_details', 'client_basic_details.id', 'invoices.client_id')
                    ->join('client_addresses', 'client_addresses.id', 'invoices.client_address_id')
                    ->select('client_basic_details.company_name', 'client_basic_details.client_code', 'invoices.*');
                if ($client_id != "all") {
                    $invoices = $invoices->where('invoices.client_id', $client_id);
                }
                if ($status != "all") {
                    $invoices = $invoices->where('invoices.status', $status);
                }
                $invoices = $invoices->where('invoices.invoice_date', '>=', $from_date)
                    ->where('invoices.invoice_date', '<=', $to_date)
                    ->orderBy('invoices.id', 'DESC')
                    ->get();
            }
        }
        $each_invoice = new Collection();
        foreach ($invoices as $key => $value) {
            $user = User::find(Auth::user()->id);
            $action = null;
            if (Auth::user()->id == 1 || $user->hasPermissionTo('Edit Client')) {
                $action = '<a href="' . route('show.invoice', [$value->id]) . '" title="View"><i class="fa fa-eye" style="color: black"></i></a>
                                <a href="' . route('edit.invoice', [$value->id]) . '" title="Edit"><i class="fa fa-edit" style="color: green"></i></a>
                                <div class="btn-group dropright"><a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-share-alt"></i></a>
                                <div class="dropdown-menu">
                                <p align="center">Share Via</p>
                                    <div class="dropdown-divider"></div>
                                    <a href="' . route('mail.invoice', [$value->id]) . '" title="Share"><center><i class="fa fa-envelope-square fa-2x" style="color: darkred"></i>Gmail</center></a><br>
                                    <a href="' . route('whatsapp.invoice', [$value->id]) . '" title="Share"><center><i class="fab fa-whatsapp fa-2x" style=" color:#fff;background:linear-gradient(#25d366,#25d366) 
                                    14% 84%/16% 16% no-repeat,radial-gradient(#25d366 60%,transparent 0);"></i>Whatsapp</center></a>
                                </div>
                                </div>';
            } else {
                $action = '<a href="' . route('show.invoice', [$value->id]) . '" title="View"><i class="fa fa-eye" style="color: black"></i></a>';
            }
            $invoice_no = '<a href="' . route('pdf.invoice', [$value->id]) . '" title="Download Invoice">' . $value->invoice_no . '</a>';

            $gst =  $value->cgst_amount + $value->sgst_amount;
            if ($gst == 0) {
                $gst = $value->igst_amount;
            }
            $each_invoice->push([

                'invoice_no' => $invoice_no,
                'invoice_date' => $value->invoice_date,
                'company_name' => $value->company_name,
                'gst_no' => $gst,
                /* 'cgst_amount' => $value->cgst_amount,
                 'sgst_amount' => $value->sgst_amount,
                 'igst_amount' => $value->igst_amount,*/
                'total_amount' => $value->grand_total,
                'status' => $value->status,
                'payment_date' => $value->payment_date,
                'paid_amount' => $value->paid_amount,
                'balance_amount' => $value->balance_amount,
                'action' => $action
            ]);
        }
        return DataTables::of($each_invoice)->addIndexColumn()->rawColumns(['action', 'invoice_no'])->make(true);
    }

    public function generateInvoicePDF($id)
    {
        $invoice = Invoice::join('client_basic_details', 'client_basic_details.id', 'invoices.client_id')
            ->join('client_addresses', 'client_addresses.id', 'invoices.client_address_id')
            ->join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->join('countries', 'countries.id', 'client_addresses.country_id')
            ->select(
                'client_basic_details.company_name',
                'client_basic_details.client_code',
                'invoices.*',
                'client_addresses.address',
                'states.state',
                'cities.city',
                'countries.country',
                'client_addresses.pincode',
                'client_addresses.gst'
            )
            ->where('invoices.id', $id)
            ->first();
        $total = InvoiceController::number_to_word($invoice->grand_total);
        $invoice_detail = InvoiceDetail::where('invoice_id', $invoice->id)->get();
        $company = company::find(1);
        $data = [
            'company' => $company,
            'invoice' => $invoice,
            'grand_total' => $total,
            'invoice_detail' => $invoice_detail
        ];

        $pdf = PDF::loadView('invoice.PDF', $data);

        return $pdf->download('Invoice_' . $invoice->invoice_no . '.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::join('client_basic_details', 'client_basic_details.id', 'invoices.client_id')
            ->join('client_addresses', 'client_addresses.id', 'invoices.client_address_id')
            ->join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->join('countries', 'countries.id', 'client_addresses.country_id')
            ->select(
                'client_basic_details.company_name',
                'client_basic_details.client_code',
                'invoices.*',
                'client_addresses.address',
                'states.state',
                'cities.city',
                'countries.country',
                'client_addresses.pincode',
                'client_addresses.gst'
            )
            ->where('invoices.id', $id)
            ->first();
        $invoice_detail = InvoiceDetail::where('invoice_id', $invoice->id)->get();
        $company = company::find(1);
        return view('invoice.show', compact('invoice', 'invoice_detail', 'company'));
    }

    public function mailInvoice($id)
    {
        $invoice = Invoice::join('client_basic_details', 'client_basic_details.id', 'invoices.client_id')
            ->join('client_addresses', 'client_addresses.id', 'invoices.client_address_id')
            ->join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->join('countries', 'countries.id', 'client_addresses.country_id')
            ->select(
                'client_basic_details.company_name',
                'client_basic_details.client_code',
                'invoices.*',
                'client_addresses.address',
                'states.state',
                'cities.city',
                'countries.country',
                'client_addresses.pincode',
                'client_addresses.gst'
            )
            ->where('invoices.id', $id)
            ->first();
        $total = InvoiceController::number_to_word($invoice->grand_total);
        $invoice_detail = InvoiceDetail::where('invoice_id', $invoice->id)->get();
        $company = company::find(1);
        $data = [
            'company' => $company,
            'invoice' => $invoice,
            'grand_total' => $total,
            'invoice_detail' => $invoice_detail,
        ];

        $pdf = PDF::loadView('invoice.PDF', $data);
        $data1 = [
            'company' => $company,
            'invoice' => $invoice,
            'grand_total' => $total,
            'invoice_detail' => $invoice_detail,
            'body' => "Hi,Please find the attached recruitment service invoice no: " . $invoice->invoice_no
        ];
        Mail::send([], $data1, function ($message) use ($data1, $pdf) {
            // Mail::send('invoice.mail', $data1, function($message)use($data1, $pdf) {
            $message->to("phpdeveloper2.docllp@gmail.com")
                ->from('phpdeveloper2.docllp@gmail.com', 'Name')
                ->subject("Invoice")
                ->attachData($pdf->output(), "invoice.pdf");
        });

        //  $clients = Client_basic_details::all();
        //  return view('invoice.index', compact('clients'))->with('success',"Invoice mailed successfully");
        return Redirect::route('all.invoice')->with('success', "Invoice mailed successfully to the client");
    }
    public function whatsappInvoice($id)
    {
        // echo "whatsapp";exit();

        $invoice = Invoice::join('client_basic_details', 'client_basic_details.id', 'invoices.client_id')
            ->join('client_addresses', 'client_addresses.id', 'invoices.client_address_id')
            ->join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->join('countries', 'countries.id', 'client_addresses.country_id')
            ->select(
                'client_basic_details.company_name',
                'client_basic_details.client_code',
                'invoices.*',
                'client_addresses.address',
                'states.state',
                'cities.city',
                'countries.country',
                'client_addresses.pincode',
                'client_addresses.gst'
            )
            ->where('invoices.id', $id)
            ->first();
        $total = InvoiceController::number_to_word($invoice->grand_total);
        $invoice_detail = InvoiceDetail::where('invoice_id', $invoice->id)->get();
        $company = company::find(1);
        $data = [
            'company' => $company,
            'invoice' => $invoice,
            'grand_total' => $total,
            'invoice_detail' => $invoice_detail,

        ];

        $pdf = PDF::loadView('invoice.PDF', $data);

        $invoice_name = str_replace('/', '_', $invoice->invoice_no);
        $fileName = 'Invoice_' . $invoice_name . '.pdf';
        $folderpath = 'pdf/invoice';
        $filePath = $folderpath . '/' . $fileName;
        Storage::makeDirectory('public/' . $folderpath);
        $pdf->save(storage_path('app/public/' . $filePath));
        $pdfUrl = asset('storage/' . $filePath);
        $url = 'https://wa.me/?text=Download Invoice: ' . urlencode($pdfUrl);
        return redirect()->away($url);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::find($id);
        $client_address = Client_address::join('states', 'states.id', 'client_addresses.state_id')
            ->join('cities', 'cities.id', 'client_addresses.city_id')
            ->select('client_addresses.id', 'client_addresses.address', 'states.state', 'cities.city')
            ->where('client_addresses.client_id', $invoice->client_id)
            ->get();
        $state = Client_address::where('id', $invoice->client_address_id)->first();
        $state_id = $state->state_id;
        $invoice_detail = InvoiceDetail::where('invoice_id', $invoice->id)->get();
        $company = company::where('id', 1)->first();
        $clients = Client_basic_details::all();
        // print_r($invoice);exit();
        return view('invoice.edit', compact('state_id', 'clients', 'invoice', 'company', 'client_address', 'invoice_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request['update_history'] == "update2") {
            if ($request['paid_amount'] == '' || $request['balance_amount'] == '' || $request['payment_date'] == '') {
                return Redirect()->back()->with('error', 'Payment history not updated,make sure all field to be filled');
            }

            // Get the existing JSON data from the database
            $invoice = Invoice::where('id', $id)->first();
            $existing_payment_history = json_decode($invoice->payment_history, true);
            $new_payment_data = array(
                "paid_amount" => $request['paid_amount'],
                "balance_amount" => $request['balance_amount'],
                "payment_date" => $request['payment_date']
            );
            $existing_payment_history[] = $new_payment_data;
            $json_data = json_encode($existing_payment_history);
            Invoice::where('id', $id)->update(['payment_history' => $json_data]);

            // print_r($json_data);exit();
            return Redirect()->back()->with('success', 'Payment History Updated successfully');
        }

        try {
            DB::beginTransaction();
            $input['invoice_no'] = $request['invoice_code'];
            $input['invoice_date'] = $request['invoice_date'];
            $input['invoice_type'] = $request['invoice_type'];
            $input['client_id'] = $request['client_id'];
            $input['client_address_id'] = $request['location'];
            //$input['state'] = $request['state'];
            $description = $request['description'];
            $amount = $request['amount'];
            $input['total_amount'] = $request['total_amount'];
            $input['cgst_amount'] = $request['cgst_amount'];
            $input['sgst_amount'] = $request['sgst_amount'];
            $input['igst_amount'] = $request['igst_amount'];
            $input['grand_total'] = $request['grand_total'];
            $description = $request['description'];
            $sac_code = $request['sac_code'];
            $amount = $request['amount'];
            // $code = $request['code'];
            // $input['added_by'] = Auth::user()->id;
            // $input['created_at'] = Carbon::now();
            $input['updated_by'] = Auth::user()->id;
            $input['updated_at'] = Carbon::now();
            $input['status'] = $request['status'];

            //  $input = $request->except(['_token', 'description', 'amount', 'code', 'cgst_percentage', 'sgst_percentage', 'igst+percentage', 'state']);
            $i = Invoice::where('id', $id)->update($input);

            $k = 0;
            $l = 0;
            if ($i > 0) {
                $description_count = count($description);
                $sac_count = count($sac_code);
                $amount_count = count($amount);
                if ($description_count == $amount_count) {
                    $in['invoice_id'] = $id;
                    $h =  InvoiceDetail::where('invoice_id', $id)->get();
                    if ($h) {
                        InvoiceDetail::where('invoice_id', $id)->delete();
                    }
                    for ($j = 0; $j < $description_count; $j++) {
                        $in['description'] = $description[$j];
                        $in['sac_code'] = $sac_code[$j];
                        $in['amount'] = $amount[$j];
                        $in['added_by'] = Auth::user()->id;
                        $in['created_at'] = Carbon::now();
                        $in['updated_by'] = Auth::user()->id;
                        $in['updated_at'] = Carbon::now();
                        $k = InvoiceDetail::insertGetId($in);
                    }
                    if ($k > 0) {
                        // $updateDetails = ['emp_code' => $code];
                        // $l = DB::table('emp_codes')
                        //     ->where('id', 3)
                        //     ->update($updateDetails);
                        // if ($l > 0) {
                        DB::commit();
                        //  $clients = Client_basic_details::all();
                        //  edit.invoice
                        //  return view('invoice.index', compact('clients'))->with('success', 'Invoice generated successfully');
                        return Redirect()->back()->with('success', 'Invoice Updated successfully');
                        // return redirect()->route('edit.invoice')->with('success', 'Invoice Updated successfully');
                        // } else {
                        //     DB::rollBack();
                        //     return Redirect()->back()->with('error', 'Invoice generated unsuccessfully1');
                        // }
                    } else {
                        DB::rollBack();
                        return Redirect()->back()->with('error', 'Invoice generated unsuccessfully2');
                    }
                } else {
                    DB::rollBack();
                    return Redirect()->back()->with('error', 'Enter description and its corresponding amount');
                }
            } else {
                DB::rollBack();
                return Redirect()->back()->with('error', 'Invoice generation failedi');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect()->back()->with('error', 'Invoice generation failed' . $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getUsersWithPermission($permissionName)
    {
        // Retrieve users with a specific permission
        $usersWithPermission = User::permission($permissionName)->get();

        return $usersWithPermission;
    }
}
