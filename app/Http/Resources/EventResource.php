<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $event =  [
            'id' => $this->id,
            'name' => $this?->name,
            'cover_image' => $this->cover_image,
            'description' => $this->description,
            'location' => $this->location,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'created_by_id' => $this->created_by_id,
            'organizedBy' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone
            ],
            'tickets' => $this->tickets
        ];

        return $event;
    }
}
