<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketPurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->ticket->type,
            'price' => $this->ticket->price,
            'is_free' => $this->ticket->is_free,
            'ticket_reference' => $this->ticket_reference,
            'surname' => $this->ticket_purchase->surname,
            'other_names' => $this->ticket_purchase->other_names,
            'phone' => $this->ticket_purchase->phone,
            'event' => $this->ticket_purchase->event->name,
            'location' => $this->ticket_purchase->event->location,
            'date' => $this->ticket_purchase->event->date,
            'has_entered' => $this->has_entered,
        ];
    }
}
