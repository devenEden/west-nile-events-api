<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventTicketStatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalPurchases = $this->ticket_purchased_tickets->count();
        $ticketsHasEntered = $this->ticket_purchased_tickets->where('has_entered', true)->count();
        return [
            'id' => $this->id,
            'type' => $this->type,
            'price' => $this->price,
            'capacity' => $this->capacity,
            'is_free' => $this->is_free,
            'total_purchases' =>  $totalPurchases,
            'total_collected' => $this->price * $totalPurchases,
            'number_attended' => $ticketsHasEntered
        ];
    }
}
