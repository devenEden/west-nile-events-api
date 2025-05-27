<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Container\Attributes\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log as FacadesLog;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TicketTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    protected User $user;
    protected $route = '/api/events/tickets';
    protected Event $event;
    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate the user
        $this->user = User::factory()->create([
            'name' => 'Hillary Trump',
            'email' => 'hillarytrump@gmail.com',
            'password' => Hash::make('secret124'),
            'phone' => '0773565665',
            'gender' => 'FEMALE'
        ]);



        Sanctum::actingAs($this->user, ['*']);
    }

    protected function create_user(String $email = 'laraveluser@gmail.com', String $phone = '0772232323')
    {
        $user = User::factory()->create([
            'name' => 'Test' . $email,
            'phone' => $phone,
            'email' => $email,
            'gender' => 'FEMALE',
            'password' => Hash::make('secret123'),
        ]);

        return $user;
    }

    protected function authenticate_user($user)
    {
        return Sanctum::actingAs($user, ['*']);
    }


    public function create_event(Int $eventNumber = 0): Event
    {
        return  Event::factory()->create([
            'name' => 'New event ' . $eventNumber,
            'description' => 'description',
            'location' => 'Location',
            'date' => '2025-05-10',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'created_by_id' => $this->user->id
        ]);
    }

    public function test_user_can_create_tickets()
    {
        $event = $this->create_event();
        $response = $this->post($this->route, [
            'event_id' => $event->id,
            'tickets' => [
                [
                    'type' => 'Ordinary',
                    'event_id' => $event->id,
                    'price' => 10000,
                    'capacity' => 20,
                    'is_free' => false,
                ],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Successfully created tickets',
            ])
            ->assertJsonFragment([
                'type' => 'Ordinary',
                'event_id' => 1,
                'price' => 10000,
                'capacity' => 20,
                'is_free' => false,
                'created_by_id' => $this->user->id,
            ]);
    }


    public function test_user_can_update_a_ticket()
    {

        $event = $this->create_event(5);

        $ticket = Ticket::factory()->create([
            'type' => 'Ordinary',
            'price' => 10000,
            'capacity' => 50,
            'is_free' => false,
            'event_id' => $event->id,
            'created_by_id' => $this->user->id
        ]);

        $payload = [
            'type' => 'VIP',
            'price' => 20000,
            'event_id' => $event->id,
            'capacity' => 30,
            'is_free' => false,
        ];

        $response = $this->put("{$this->route}/{$ticket->id}", $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'type' => 'VIP',
            'price' => 20000,
            'capacity' => 30,
            'is_free' => false,
        ]);
    }

    public function test_user_can_delete_a_ticket()
    {
        $event = $this->create_event(5);

        $ticket = Ticket::factory()->create([
            'type' => 'Ordinary',
            'price' => 10000,
            'capacity' => 50,
            'is_free' => false,
            'event_id' => $event->id,
            'created_by_id' => $this->user->id
        ]);

        $response = $this->delete("{$this->route}/{$ticket->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Successfully delete tickets',
            ]);

        $this->assertDatabaseMissing('tickets', [
            'id' => $ticket->id,
        ]);
    }

    public function test_user_cannot_update_ticket_they_do_not_own()
    {
        $event = $this->create_event(5);
        $ticket = Ticket::factory()->create([
            'type' => 'Ordinary',
            'price' => 10000,
            'capacity' => 50,
            'is_free' => false,
            'event_id' => $event->id,
            'created_by_id' => $this->user->id
        ]);

        $otherUser = $this->authenticate_user($this->create_user());

        $payload = [
            'type' => 'VIP',
            'price' => 20000,
            'event_id' => $event->id,
            'capacity' => 30,
            'is_free' => false,
        ];

        $response = $this->actingAs($otherUser)->put("{$this->route}/{$ticket->id}", $payload);

        $response->assertStatus(403); // Forbidden
    }

    public function test_user_cannot_delete_ticket_they_do_not_own()
    {
        $event = $this->create_event(5);

        $otherUser = $this->create_user();

        $ticket =  Ticket::factory()->create([
            'type' => 'Ordinary',
            'price' => 10000,
            'capacity' => 50,
            'is_free' => false,
            'event_id' => $event->id,
            'created_by_id' => $otherUser->id
        ]);

        $response = $this->delete("{$this->route}/{$ticket->id}");

        $response->assertStatus(403); // Forbidden

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id]);
    }
}
