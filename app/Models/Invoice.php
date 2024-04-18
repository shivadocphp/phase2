<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_no', 'invoice_date',
        'invoice_type', 'client_id', 'client_address_id',
        'total_amount', 'sgst_amount', 'cgst_amount', 'igst_amount',
        'grand_total', 'status', 'paid_amount', 'balance_amount', 'payment_date', 'added_by', 'updated_by', 'deleted_by',
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function empaddedBy()
    {
        return $this->belongsTo('App\Models\User', 'added_by');
    }
}
