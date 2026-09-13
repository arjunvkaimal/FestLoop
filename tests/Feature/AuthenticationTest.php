<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_new_attendee_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Attendee',
            'email' => 'attendee@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'attendee',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'attendee@test.com')->first();
        $this->assertEquals('attendee', $user->role);
    }

    public function test_new_coordinator_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Coordinator',
            'email' => 'coordinator@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'coordinator',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('coordinator.dashboard', absolute: false));

        $user = User::where('email', 'coordinator@test.com')->first();
        $this->assertEquals('coordinator', $user->role);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_attendee_can_login(): void
    {
        $user = User::factory()->create([
            'role' => 'attendee',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_coordinator_can_login_and_redirect(): void
    {
        $user = User::factory()->create([
            'role' => 'coordinator',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('coordinator.dashboard'));
    }

    public function test_attendee_cannot_access_coordinator_routes(): void
    {
        $user = User::factory()->create(['role' => 'attendee']);

        $response = $this->actingAs($user)->get('/coordinator/dashboard');

        $response->assertStatus(403);
    }

    public function test_coordinator_can_access_coordinator_routes(): void
    {
        $user = User::factory()->create(['role' => 'coordinator']);

        $response = $this->actingAs($user)->get('/coordinator/dashboard');

        $response->assertStatus(200);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
