<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketPayment extends Model
{
    //

    protected $fillable  = [
        'ticket_purchase_id',
        'amount_paid',
        'system_reference_number',
        'msisdn',
        'transaction_number',
        'narration'
    ];


    public function ticket_purchase()
    {
        return $this->belongsTo(TicketPurchase::class, 'ticket_purchase_id');
    }
}
