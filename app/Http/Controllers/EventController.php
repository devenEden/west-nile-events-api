<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventTicketPurchaseResource;
use App\Http\Resources\EventTicketStatisticsResource;
use App\Models\Event;
use App\Services\Events\EventService;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use ApiResponse;
    // protected EventService $eventService;

    public function __construct(private EventService $eventService)
    {
        // $this->eventService = new EventService();
    }


    public function index(Request $request)
    {
        //
        $events = Event::where('date', '>', Carbon::today())->get();
        return  $this->success('Events fetched successfully', EventResource::collection($events)->toArray($request));
    }

    public function myEvents(Request $request)
    {
        //
        $events = $request->user()->events()->get();
        return  $this->success('Events fetched successfully', EventResource::collection($events)->toArray($request));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $event = $this->eventService->createEvent($request);
        return $this->success('Event created successfully', (new EventResource($event))->toArray($request));
    }

    // /**
    //  * Display the specified resource.
    //  */
    public function show(Request $request, Event $event)
    {
        //
        if (!$event) {
            return $this->badRequest('No Event found');
        }
        return $this->success('Event fetched successfully', (new EventResource($event))->toArray($request));
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    public function update(UpdateEventRequest $request, Event $event)
    {
        //
        Gate::authorize('update', $event);
        $event = $this->eventService->updateEvent($request, $event);
        return $this->success('Event updated successfully', (new EventResource($event))->toArray($request));
    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(Event $event)
    {
        //
        if (!$event) {
            return $this->badRequest('No Event found');
        }
        Gate::authorize('delete', $event);

        $event->delete();

        return $this->success('Event deleted successfully');
    }

    public function getPassedEvents(Request $request)
    {
        $events = Event::where('date', '<', Carbon::today())->get();
        return  $this->success('Events fetched successfully', EventResource::collection($events)->toArray($request));
    }

    public function getEventTicketPurchases(Request $request)
    {
        $event = $this->eventService->verifyUserOwnsEvent($request);

        $ticketPurchases = $event->ticketPurchases()->get();

        return $this->success('Event Ticket Purchases fetched successfully', EventTicketPurchaseResource::collection($ticketPurchases)->toArray($request));
    }

    public function getEventTicketStatistics(Request $request)
    {
        $event = $this->eventService->verifyUserOwnsEvent($request);

        $tickets = $event->tickets()->get();

        return $this->success('Event Ticket  fetched successfully', EventTicketStatisticsResource::collection($tickets)->toArray($request));
    }

    public function uploadImage(Request $request)
    {
        $event = $this->eventService->verifyUserOwnsEvent($request);

        return $this->eventService->uploadImage($event, $request);
    }
}
