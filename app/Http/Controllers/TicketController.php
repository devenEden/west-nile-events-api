<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use App\Services\Tickets\TicketService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

// use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;

    public function __construct(protected TicketService $ticketService) {}
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        //
        return $this->ticketService->bulkCreate($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
        return $this->success('Ticket fetched successfully', $ticket->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
        Gate::authorize('update', $ticket);
        return $this->ticketService->updateTicket($request, $ticket);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
        Gate::authorize('delete', $ticket);
        return $this->ticketService->deleteTicket($ticket);
    }
}
