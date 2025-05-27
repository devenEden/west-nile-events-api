<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketPurchaseRequest;
use App\Services\TicketPurchases\TicketPurchaseService;
use Illuminate\Http\Request;

class TicketPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(private TicketPurchaseService $ticketPurchaseService) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketPurchaseRequest $request)
    {
        //
        return $this->ticketPurchaseService->createTicketPurchase($request);
    }

    public function verifyTicket(Request $request)
    {
        return $this->ticketPurchaseService->verifyTicket($request);
    }
}
