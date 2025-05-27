<?php

namespace App\Services\Tickets;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketPurchaseResource;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketPurchaseTicket;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TicketService
{
    use ApiResponse;

    public function bulkCreate(StoreTicketRequest $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $event = Event::find($request->event_id);

            if (!$event) {
                return $this->badRequest('Event not found');
            }

            if ($event->created_by_id != $request->user()->id) {
                return $this->badRequest('Invalid event provided');
            }

            $tickets = [];
            foreach ($request->tickets as $ticket) {
                # code...
                $tickets[] = [
                    'type' => $ticket['type'],
                    'event_id' => $event->id,
                    'price' => $ticket['price'],
                    'capacity' => $ticket['capacity'],
                    'is_free' => $ticket['is_free'],
                    'created_by_id' => $request->user()->id,
                ];
            }


            Ticket::createMany($tickets);

            DB::commit();

            return $this->success('Successfully created tickets', $tickets);
        } catch (\Exception $error) {
            report($error);
            //throw $th;
            DB::rollBack();
            return $this->badRequest($error->getMessage());
        }
    }


    public function updateTicket(UpdateTicketRequest $request, Ticket $ticket)
    {
        try {
            //code...
            if (!$ticket) {
                return $this->badRequest('Ticket not found');
            }

            $ticket->is_free = $request->is_free;
            $ticket->type = $request->type;
            $ticket->capacity = $request->capacity;
            $ticket->price = $request->price;

            $ticket->save();

            return $this->success('Successfully updated tickets', $ticket->toArray());
        } catch (\Exception $error) {
            report($error);
            //throw $th;
            return $this->badRequest($error->getMessage());
        }
    }

    public function deleteTicket(Ticket $ticket)
    {
        try {
            //code...
            if (!$ticket) {
                return $this->badRequest('Ticket not found');
            }

            $ticket->delete();

            return $this->success('Successfully delete tickets');
        } catch (\Exception $error) {
            report($error);
            //throw $th;
            return $this->badRequest($error->getMessage());
        }
    }
}
