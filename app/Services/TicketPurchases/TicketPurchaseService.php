<?php

namespace App\Services\TicketPurchases;

use App\Http\Requests\StoreTicketPurchaseRequest;
use App\Http\Resources\TicketPurchaseResource;
use App\Mail\TicketMail;
use App\Models\Event;
use App\Models\TicketPayment;
use App\Models\TicketPurchase;
use App\Models\TicketPurchaseTicket;
use App\Traits\ApiResponse;
use App\Traits\PaymentTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketPurchaseService
{
    use ApiResponse, PaymentTrait;


    public function createTicketPurchase(StoreTicketPurchaseRequest $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $event = Event::find($request->event_id);
            $now = date('Y-m-d');

            if ($now > $event->date) {
                return $this->badRequest('The event date has already passed');
            }

            if (!$event->is_published) {
                return $this->badRequest('The event is not yet published');
            }

            $ticketPurchase = TicketPurchase::create([
                'event_id' => $event->id,
                'email' => $request->email,
                'surname' => $request->surname,
                'other_names' => $request->other_names,
                'phone' => $request->phone,
            ]);

            $eventTickets = $event->tickets()->pluck('id');


            foreach ($request->tickets as $ticket) {
                if (!in_array($ticket['ticket_id'], $eventTickets->toArray())) {
                    return $this->badRequest('Invalid ticket provided');
                }
            }

            $purchasedTickets = $this->generateTicketReference($event, $request->tickets);

            $ticketPurchase->purchased_tickets()->createMany($purchasedTickets);

            $totalPurchase = $this->calculateTicketPurchaseTotal($request->tickets, $event->tickets());

            $payment = $this->initiatePaymentCollection($request->phone, $totalPurchase, [
                'event_name' => $event->name,
                'ticket_purchase_id' =>  $ticketPurchase->id
            ]);

            if ($payment['status'] == 'success') {
                $ticketPayment = TicketPayment::create([
                    'ticket_purchase_id' => $ticketPurchase->id,
                    'amount_paid' =>  $totalPurchase,
                    'system_reference_number' => $payment['payload']['external_reference'],
                    'msisdn' => $request->phone,
                    'transaction_number' => $payment['data']['transactionReference'],
                    'narration' => $payment['payload']['narration']
                ]);

                DB::commit();
                $this->sendTicketEmail($request->email, $event, $purchasedTickets, $ticketPayment);
                return $this->success('Payment Successful');
            } else {
                DB::rollBack();
                return $this->badRequest($payment['message']);
            }
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
            report($th);
            return $this->badRequest($th->getMessage());
        }
    }

    public function calculateTicketPurchaseTotal($purchasedTickets, $eventTickets): Float
    {

        $totalPurchase = 0;
        $eventTickets = $eventTickets->get()->toArray();

        foreach ($purchasedTickets as $purchasedTicket) {
            $ticket = $this->findTicket($purchasedTicket['ticket_id'],  $eventTickets);
            $totalPurchase += $ticket['price'] * $purchasedTicket['number_of_tickets'];
        }

        return $totalPurchase;
    }

    public function findTicket($id, $tickets)
    {
        $foundTicket = [];

        foreach ($tickets as $ticket) {
            # code...
            if ($ticket['id'] == $id)
                return $ticket;
        }

        return $foundTicket;
    }

    public function generateTicketReference($event, $purchasedTickets)
    {
        $tickets = [];
        $eventTickets = $event->tickets()->get()->toArray();

        $ticketsDirectory = public_path('tickets');
        if (!File::isDirectory($ticketsDirectory)) {
            File::makeDirectory($ticketsDirectory, 0755, true);
        }
        foreach ($purchasedTickets as $purchasedTicket) {
            # code...
            $ticket = $this->findTicket($purchasedTicket['ticket_id'],  $eventTickets);
            for ($number = 0; $number < $purchasedTicket['number_of_tickets']; $number++) {

                $ticketReference = strtoupper(bin2hex(random_bytes(4))) . '-' . $purchasedTicket['ticket_id'] . '-' . $number;
                $fileName = $ticketReference . '.pdf';
                $ticketsData = [
                    'ticket_id' => $purchasedTicket['ticket_id'],
                    'number_of_tickets' => 1,
                    'ticket_reference' => $ticketReference,
                    'file_path' =>  $fileName,
                    'date' => $event->date,
                    'price' => $ticket['price'],
                    'event_name' => $event->name,
                    'type' => $ticket['type'],
                    'location' => $event->location,
                ];
                $tickets[] = [
                    'ticket_id' => $purchasedTicket['ticket_id'],
                    'number_of_tickets' => 1,
                    'file_path' =>  $fileName,
                    'ticket_reference' => $ticketReference,
                ];
                $appPath = config('app.url') . '/api/events/tickets/verify/' . $ticketReference;
                $qrCode = base64_encode(QrCode::size(80)->style('round')->generate($appPath));

                Pdf::loadView('tickets.ticket', ['ticket' => $ticketsData, 'qrCode' => $qrCode])
                    ->setPaper('a5', 'landscape')
                    ->save(public_path('tickets/' . $fileName));
            }
        }

        return $tickets;
    }


    public function sendTicketEmail($email, $event, $purchasedTickets, $ticketPayment)
    {

        $tickets = [];
        $eventTickets = $event->tickets()->get()->toArray();

        foreach ($purchasedTickets as $purchasedTicket) {
            # code...
            $ticket = $this->findTicket($purchasedTicket['ticket_id'],  $eventTickets);

            $tickets[] = [
                'number' => $purchasedTicket['number_of_tickets'],
                'price' => $ticket['price'],
                'type' => $ticket['type'],
                'is_free' => false,
                'file_path' => $purchasedTicket['file_path'],
                'ticket_reference' => $purchasedTicket['ticket_reference'],
            ];
        }

        Mail::to($email)->send(new TicketMail([
            'event' => $event,
            'email' => $email,
            'tickets' => $tickets,
            'payment' => $ticketPayment
        ]));
    }

    public function verifyTicket(Request $request)
    {
        $ticketPurchaseTicket = TicketPurchaseTicket::where('ticket_reference', $request->ticket_reference)->first();

        if (!$ticketPurchaseTicket) {
            return $this->badRequest('Invalid ticket');
        }

        $ticketPurchaseTicket->has_entered = true;

        $ticketPurchaseTicket->save();

        return $this->success('Successfully verified ticket', (new TicketPurchaseResource($ticketPurchaseTicket))->toArray($request));
    }
}
