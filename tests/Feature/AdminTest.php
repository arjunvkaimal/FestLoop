<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendee_cannot_access_admin_portal(): void
    {
        $attendee = User::factory()->create(['role' => 'attendee']);

        $response = $this->actingAs($attendee)->get('/admin/dashboard');
        $response->assertStatus(403);

        $response = $this->actingAs($attendee)->get('/admin/users');
        $response->assertStatus(403);

        $response = $this->actingAs($attendee)->get('/admin/events');
        $response->assertStatus(403);
    }

    public function test_coordinator_cannot_access_admin_portal(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);

        $response = $this->actingAs($coordinator)->get('/admin/dashboard');
        $response->assertStatus(403);

        $response = $this->actingAs($coordinator)->get('/admin/users');
        $response->assertStatus(403);

        $response = $this->actingAs($coordinator)->get('/admin/events');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_portal_views(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Admin Control Center');

        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('User & Role Management');

        $response = $this->actingAs($admin)->get('/admin/events');
        $response->assertStatus(200);
        $response->assertSee('Global Event Moderation');
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_update_user_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'attendee']);

        $response = $this->actingAs($admin)->patch("/admin/users/{$user->id}/role", [
            'role' => 'coordinator',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'coordinator',
        ]);
    }

    public function test_admin_cannot_demote_self(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->patch("/admin/users/{$admin->id}/role", [
            'role' => 'attendee',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'attendee']);

        $response = $this->actingAs($admin)->delete("/admin/users/{$user->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

    public function test_admin_can_moderate_event_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $event = Event::factory()->create([
            'coordinator_id' => $coordinator->id,
            'status' => 'published',
        ]);

        $response = $this->actingAs($admin)->patch("/admin/events/{$event->id}/status", [
            'status' => 'cancelled',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_admin_can_delete_any_event(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $event = Event::factory()->create([
            'coordinator_id' => $coordinator->id,
        ]);

        $response = $this->actingAs($admin)->delete("/admin/events/{$event->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
    }
}
