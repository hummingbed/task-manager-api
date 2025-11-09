<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    /** @test */
    public function it_can_register_a_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret123',
        ];

        $result = $this->authService->signup($data);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals('John Doe', $result['user']->name);
    }

    /** @test */
    public function it_can_login_a_registered_user()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $result = $this->authService->login($data);

        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user->id, $result['user']->id);
    }

    /** @test */
    public function it_throws_exception_for_invalid_login()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'email' => 'fake@example.com',
            'password' => 'wrongpassword',
        ];

        $this->authService->login($data);
    }
}