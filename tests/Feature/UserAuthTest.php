<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected $route = '/api/auth';
    protected $response_structure = [
        'status',
        'statusCode',
        'message'
    ];

    protected $user;

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


    protected function authenticate($user)
    {
        return Sanctum::actingAs($user, ['*']);
    }


    public function test_user_can_login_with_correct_credentials(): void
    {

        $response = $this->post($this->route . '/login', [
            'email' => $this->user->email,
            'password' => 'secret124',
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->response_structure);
    }

    public function test_user_cannot_login_with_incorrect_credentials(): void
    {

        // $user = $this->create_user();
        $response = $this->post($this->route . '/login', [
            'email' => $this->user->email,
            'password' => 'secret123',
        ]);

        $response->assertStatus(401)->assertJsonStructure($this->response_structure);
    }

    public function test_user_cannot_login_with_incorrect_json_object(): void
    {

        // $user = $this->create_user();
        $response = $this->post($this->route . '/login', [
            'email' => $this->user->email,
            'password1' => 'secret123',
        ]);

        $response->assertStatus(302);
    }

    public function test_user_can_get_profile(): void
    {

        // $user = $this->create_user();
        $response = $this->get($this->route . '/user');
        $response->assertStatus(200)->assertJson([
            'status' => 'success',
            'statusCode' => 200,
            'message' => 'Profile loaded successfully',
            'data' => [
                'name' => 'Hillary Trump',
                'email' => 'hillarytrump@gmail.com',
                'phone' => '0773565665',
                'gender' => 'FEMALE',
            ]
        ]);
    }
}
