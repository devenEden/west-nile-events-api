<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPurchase extends Model
{
    /** @use HasFactory<\Database\Factories\TicketPurchaseFactory> */
    use HasFactory;

    protected $fillable = [
        'event_id',
        'email',
        'phone',
        'surname',
        'other_names'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function purchased_tickets()
    {
        return $this->hasMany(TicketPurchaseTicket::class, 'ticket_purchase_id', 'id');
    }

    public function ticket_payment()
    {
        return $this->hasOne(TicketPayment::class, 'ticket_purchase_id', 'id');
    }
}
