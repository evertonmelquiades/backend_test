<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $userData = [
            'name' => 'Test User',
            'username' => 'testUser',
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->post('/api/register', $userData);

        $response->assertNoContent();

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        Auth::login($user);

        $this->assertAuthenticated();
    }
}
