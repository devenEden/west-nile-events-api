<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    protected $fillable = [
        'event_id',
        'type',
        'price',
        'capacity',
        'is_free',
        'created_by_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function ticket_purchased_tickets()
    {
        return $this->hasMany(TicketPurchaseTicket::class, 'ticket_id');
    }
}
