<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_signup_via_api()
    {
        $payload = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/signup', $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'user' => ['id', 'name', 'email'],
                         'token'
                     ]
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    }

    /** @test */
    public function user_can_login_via_api()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $payload = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'User logged in successfully.',
                 ]);
    }

    /** @test */
    public function login_fails_with_invalid_credentials()
    {
        $payload = [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpass',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(422);
    }
}