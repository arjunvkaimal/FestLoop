<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function createOpenEvent(User $coordinator): Event
    {
        return Event::factory()->create([
            'coordinator_id' => $coordinator->id,
            'status' => 'published',
            'capacity_limit' => 50,
            'start_time' => now()->addDays(30),
            'end_time' => now()->addDays(30)->addHours(3),
            'registration_deadline' => now()->addDays(25),
        ]);
    }

    public function test_authenticated_user_can_register_for_event(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = $this->createOpenEvent($coordinator);

        $response = $this->actingAs($attendee)->post("/events/{$event->id}/register");

        $response->assertRedirect();
        $this->assertDatabaseHas('registrations', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_guest_cannot_register_for_event(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $event = $this->createOpenEvent($coordinator);

        $response = $this->post("/events/{$event->id}/register");
        $response->assertRedirect('/login');
    }

    public function test_user_cannot_register_twice(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = $this->createOpenEvent($coordinator);

        Registration::create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'registered_at' => now(),
        ]);

        $response = $this->actingAs($attendee)->post("/events/{$event->id}/register");
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_registration_fails_when_event_is_full(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $event = Event::factory()->create([
            'coordinator_id' => $coordinator->id,
            'status' => 'published',
            'capacity_limit' => 1,
            'start_time' => now()->addDays(30),
            'end_time' => now()->addDays(30)->addHours(3),
            'registration_deadline' => now()->addDays(25),
        ]);

        // Fill the event
        $user1 = User::factory()->create(['role' => 'attendee']);
        Registration::create([
            'user_id' => $user1->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'registered_at' => now(),
        ]);

        $attendee = User::factory()->create(['role' => 'attendee']);
        $response = $this->actingAs($attendee)->post("/events/{$event->id}/register");
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_user_can_cancel_registration(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $attendee = User::factory()->create(['role' => 'attendee']);
        $event = $this->createOpenEvent($coordinator);

        $registration = Registration::create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'registered_at' => now(),
        ]);

        $response = $this->actingAs($attendee)->delete("/registrations/{$registration->id}");
        $response->assertRedirect();
        $this->assertDatabaseHas('registrations', [
            'id' => $registration->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_user_cannot_cancel_others_registration(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $attendee1 = User::factory()->create(['role' => 'attendee']);
        $attendee2 = User::factory()->create(['role' => 'attendee']);
        $event = $this->createOpenEvent($coordinator);

        $registration = Registration::create([
            'user_id' => $attendee1->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'registered_at' => now(),
        ]);

        $response = $this->actingAs($attendee2)->delete("/registrations/{$registration->id}");
        $response->assertStatus(403);
    }

    public function test_registration_past_deadline_fails(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $event = Event::factory()->create([
            'coordinator_id' => $coordinator->id,
            'status' => 'published',
            'capacity_limit' => 50,
            'start_time' => now()->addDays(2),
            'end_time' => now()->addDays(2)->addHours(3),
            'registration_deadline' => now()->subDay(),
        ]);

        $attendee = User::factory()->create(['role' => 'attendee']);
        $response = $this->actingAs($attendee)->post("/events/{$event->id}/register");
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
