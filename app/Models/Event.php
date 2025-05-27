<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'cover_image',
        'location',
        'description',
        'date',
        'created_by_id',
        'start_time',
        'end_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event_id');
    }

    public function ticketPurchases()
    {
        return $this->hasMany(TicketPurchase::class, 'event_id');
    }
}
