<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPurchaseTicket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketPurchaseTicketFactory> */
    use HasFactory;

    protected $fillable = [
        'ticket_purchase_id',
        'ticket_id',
        'number_of_tickets',
        'ticket_reference',
        'has_entered',
        'file_path'
    ];

    protected function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    protected function ticket_purchase()
    {
        return $this->belongsTo(TicketPurchase::class, 'ticket_purchase_id');
    }
}
