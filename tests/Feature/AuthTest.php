<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+79991234567',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->post('/register', $userData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->withRole('user')->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/logout');

        $response->assertStatus(302);
        $this->assertGuest();
    }
}
