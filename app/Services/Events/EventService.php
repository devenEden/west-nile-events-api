<?php

namespace App\Services\Events;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventService
{

    use ApiResponse;

    public function createEvent(StoreEventRequest $request)
    {
        return $request->user()->events()->create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
    }

    public function updateEvent(UpdateEventRequest $request, $event)
    {
        if (!$event) {
            $this->badRequest('Event not found');
        }

        $event->name = $request->name;
        $event->description = $request->description;
        $event->location = $request->location;
        $event->date = $request->date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->save();

        return $event;
    }

    public function getEventTicketPurchases(Request $request)
    {
        $event = Event::find($request->event_id);

        if (!$event) {
            $this->badRequest('Event not found');
        }

        $eventTicketPurchases = $event->ticketPurchases();

        $ticketData = [];

        foreach ($eventTicketPurchases as $purchase) {
            # code...
            $ticketData[] = [
                'surname' => $purchase->surname,
                'other_names' => $purchase->other_names,
                'email' => $purchase->email,
                'phone' => $purchase->phone,
                'number_of_tickets' => $purchase->purchased_tickets()->count(),
                'tickets' => $purchase->purchased_tickets()
            ];
        }

        return $this->success('Successfully loaded ticket purchases', $ticketData);
    }

    public function getTicketPurchaseStatistics(Request $request)
    {
        $event = Event::find($request->event_id);

        if (!$event) {
            $this->badRequest('Event not found');
        }

        $tickets = $event->tickets();

        $ticketStatistics = [];

        foreach ($tickets as $ticket) {
            # code...
            $totalPurchases = $ticket->ticket_purchased_tickets()->sum('number_of_tickets');
            $totalCollected = $ticket->price * $totalPurchases;

            $ticketStatistics[] = [
                'type' => $ticket->type,
                'price' => $ticket->price,
                'capacity' => $ticket->capacity,
                'total_purchases' => $totalPurchases,
                'total_collected' => $totalCollected
            ];
        }


        return $ticketStatistics;
    }

    public function uploadImage(Event $event, Request $request)
    {

        $path = $request->file('image')->store('uploads', 'public');
        $event->cover_image = $path;
        $event->save();
        return $this->success('Successfully uploaded image',);
    }


    public function verifyUserOwnsEvent(Request $request)
    {
        $event = Event::find($request->event_id);

        if (!$event) {
            return $this->badRequest('No Event Found');
        }

        Gate::authorize('userOwnsEvent', $event);

        return $event;
    }
}
