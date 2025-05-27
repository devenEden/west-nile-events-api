<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketPurchase;
use App\Models\TicketPurchaseTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EventTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected $user;
    protected $table_name = 'events';
    protected $route = '/api/events';
    protected $request_structure = [
        'status',
        'statusCode',
        'message'
    ];

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
    protected function authenticate_user($user)
    {
        return Sanctum::actingAs($user, ['*']);
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


    public function test_create_event(): void
    {
        $response = $this->post(
            $this->route,
            [
                'name' => 'New event',
                'description' => 'description',
                'location' => 'Location',
                'date' => '2025-05-10',
                'start_time' => '10:00',
                'end_time' => '12:00',
            ]
        );

        $this->assertDatabaseHas($this->table_name, [
            'name' => 'New event',
            'description' => 'description',
            'location' => 'Location',
            'date' => '2025-05-10',
            'start_time' => '10:00',
            'end_time' => '12:00',
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->request_structure);
    }

    public function test_view_events(): void
    {
        $this->create_event();
        $response = $this->get($this->route);

        $response->assertStatus(200)->assertJsonStructure($this->request_structure);
    }

    public function test_view_event(): void
    {
        $event = $this->create_event();
        $response = $this->get("{$this->route}/{$event->id}");

        $response->assertStatus(200)->assertJsonStructure($this->request_structure);
    }


    public function test_update_event_successfully()
    {
        // Arrange: Create an event
        $event = $this->create_event(1);
        // Act: Send a PUT JSON request to update the event
        $response = $this->put("{$this->route}/{$event->id}", [
            'name' => 'Updated Event Name',
            'description' => 'Updated description',
            'location' => 'Updated location',
            'date' => '2025-05-10',
            'start_time' => '10:00',
            'end_time' => '12:00',
        ]);

        // Assert: The response status and structure
        $response->assertStatus(200)->assertJsonStructure($this->request_structure);
        // Assert: The event is updated in the database
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'name' => 'Updated Event Name',
            'description' => 'Updated description',
            'location' => 'Updated location',
            'date' => '2025-05-10',
            'start_time' => '10:00',
            'end_time' => '12:00',
        ]);
    }

    public function test_delete_event_successfully()
    {
        // Arrange: Create an event
        $event = $this->create_event(1);
        // Act: Send a PUT JSON request to update the event
        $response = $this->delete("{$this->route}/{$event->id}");

        // Assert: The response status and structure
        $response->assertStatus(200)->assertJsonStructure($this->request_structure);
        // Assert: The event is updated in the database
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
            'name' => 'New event 0',
            'description' => 'Updated description',
            'location' => 'Updated location',
            'date' => '2025-05-10',
            'start_time' => '10:00',
            'end_time' => '12:00',
        ]);
    }

    public function test_user_can_only_update_events_created_by_them()
    {
        // Arrange: Create an event
        $event = $this->create_event(1);
        $newUser = $this->create_user();
        $authenticatedNewUser  = $this->authenticate_user($newUser);
        // Act: Send a PUT JSON request to update the event
        $response = $this->actingAs($authenticatedNewUser)->put("{$this->route}/{$event->id}", [
            'name' => 'Updated Event Name',
            'description' => 'Updated description',
            'location' => 'Updated location',
            'date' => '2025-05-10',
            'start_time' => '10:00',
            'end_time' => '12:00',
        ]);

        // Assert: The response status and structure
        $response->assertStatus(403);
    }

    public function test_user_can_only_delete_events_created_by_them()
    {
        // Arrange: Create an event
        $event = $this->create_event(1);
        $newUser = $this->create_user();
        $authenticatedNewUser  = $this->authenticate_user($newUser);
        // Act: Send a PUT JSON request to update the event
        $response = $this->actingAs($authenticatedNewUser)->delete("{$this->route}/{$event->id}");

        // Assert: The response status and structure
        $response->assertStatus(403);
    }

    public function test_get_passed_events_returns_success()
    {
        $this->create_event();

        $response = $this->getJson('/api/public-events/passed');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'statusCode',
                'message',
            ]);
    }

    public function test_authenticated_user_can_get_event_ticket_statistics()
    {
        $event = $this->create_event();
        $ticket = Ticket::factory()->create([
            'type' => 'Ordinary',
            'price' => 10000,
            'capacity' => 50,
            'is_free' => false,
            'event_id' => $event->id,
            'created_by_id' => $this->user->id
        ]);
        $ticketPurchase = TicketPurchase::factory()->create([
            'event_id' => $event->id,
            'email' => $this->user->name,
            'surname' => $this->user->name,
            'other_names' => $this->user->name,
            'phone' => $this->user->phone,
        ]);
        $ticketReference = strtoupper(bin2hex(random_bytes(4))) . '-' . $ticket->id . '-' . '0';
        $fileName = $ticketReference . '.pdf';
        TicketPurchaseTicket::factory()->create([
            'ticket_id' => $ticket->id,
            'number_of_tickets' => 1,
            'file_path' =>  $fileName,
            'ticket_reference' => $ticketReference,
            'ticket_purchase_id' => $ticketPurchase->id
        ]);



        $response = $this->getJson($this->route . "/my-events/statistics/{$event->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'statusCode',
                'message',
            ]);
    }

    public function test_user_can_upload_image_to_event()
    {
        Storage::fake('public');

        $event = $this->create_event();
        $file = UploadedFile::fake()->image('event.jpg');

        $response = $this->postJson($this->route . "/my-events/upload-image/{$event->id}", [
            'image' => $file
        ]);

        $response->assertStatus(200);

        // Optional: Verify that the event DB record is updated
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'cover_image' => 'uploads/' . $file->hashName()
        ]);
    }
}
