<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventTicketPurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $purchaseTickets = $this->purchased_tickets;
        $tickets = [];

        foreach ($purchaseTickets as $purchasedTicket) {
            # code...
            $tickets[] = [
                'id' => $purchasedTicket->id,
                'ticket_type' => $purchasedTicket->ticket->type,
                'price' => $purchasedTicket->ticket->price,
                'total_cost' => $purchasedTicket->ticket->price *  $purchasedTicket->number_of_ticket,
                'ticket_reference' => $purchasedTicket->ticket_reference,
                'has_entered' => $purchasedTicket->has_entered,
                'file_path' => $purchasedTicket->file_path,
            ];
        }

        return [
            'id' => $this->id,
            'surname' => $this->surname,
            'other_names' => $this->other_names,
            'email' => $this->email,
            'phone' => $this->phone,
            'event_id' => $this->event_id,
            'number_of_tickets' => $this->purchased_tickets->count(),
            'tickets' =>  $tickets
        ];
    }
}
