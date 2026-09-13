<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_page_can_be_rendered(): void
    {
        $response = $this->get('/events');
        $response->assertStatus(200);
    }

    public function test_event_detail_can_be_viewed(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $event = Event::factory()->create(['coordinator_id' => $coordinator->id, 'status' => 'published']);

        $response = $this->get("/events/{$event->id}");
        $response->assertStatus(200);
        $response->assertSee($event->title);
    }

    public function test_coordinator_can_create_event(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);

        $response = $this->actingAs($coordinator)->get('/coordinator/events/create');
        $response->assertStatus(200);

        $eventData = [
            'title' => 'Test Festival Event',
            'description' => 'A wonderful test event for the festival.',
            'category' => 'cultural',
            'venue' => 'Main Auditorium',
            'start_time' => now()->addDays(30)->format('Y-m-d\TH:i'),
            'end_time' => now()->addDays(30)->addHours(3)->format('Y-m-d\TH:i'),
            'registration_deadline' => now()->addDays(25)->format('Y-m-d\TH:i'),
            'capacity_limit' => 100,
            'status' => 'published',
        ];

        $response = $this->actingAs($coordinator)->post('/coordinator/events', $eventData);
        $response->assertRedirect(route('coordinator.events.index'));

        $this->assertDatabaseHas('events', [
            'title' => 'Test Festival Event',
            'coordinator_id' => $coordinator->id,
        ]);
    }

    public function test_attendee_cannot_create_event(): void
    {
        $attendee = User::factory()->create(['role' => 'attendee']);

        $response = $this->actingAs($attendee)->get('/coordinator/events/create');
        $response->assertStatus(403);
    }

    public function test_coordinator_can_update_own_event(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $event = Event::factory()->create(['coordinator_id' => $coordinator->id]);

        $response = $this->actingAs($coordinator)->put("/coordinator/events/{$event->id}", [
            'title' => 'Updated Event Title',
            'description' => $event->description,
            'category' => $event->category,
            'venue' => $event->venue,
            'start_time' => $event->start_time->format('Y-m-d\TH:i'),
            'end_time' => $event->end_time->format('Y-m-d\TH:i'),
            'registration_deadline' => $event->registration_deadline->format('Y-m-d\TH:i'),
            'capacity_limit' => $event->capacity_limit,
            'status' => $event->status,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('events', ['id' => $event->id, 'title' => 'Updated Event Title']);
    }

    public function test_coordinator_cannot_update_others_event(): void
    {
        $coordinator1 = User::factory()->create(['role' => 'coordinator']);
        $coordinator2 = User::factory()->create(['role' => 'coordinator']);
        $event = Event::factory()->create(['coordinator_id' => $coordinator1->id]);

        $response = $this->actingAs($coordinator2)->put("/coordinator/events/{$event->id}", [
            'title' => 'Hacked Title',
            'description' => 'Hacked',
            'category' => 'cultural',
            'venue' => 'Hacked Venue',
            'start_time' => now()->addDays(30)->format('Y-m-d\TH:i'),
            'end_time' => now()->addDays(30)->addHours(3)->format('Y-m-d\TH:i'),
            'registration_deadline' => now()->addDays(25)->format('Y-m-d\TH:i'),
            'capacity_limit' => 100,
            'status' => 'published',
        ]);

        $response->assertStatus(403);
    }

    public function test_coordinator_can_delete_own_event(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        $event = Event::factory()->create(['coordinator_id' => $coordinator->id]);

        $response = $this->actingAs($coordinator)->delete("/coordinator/events/{$event->id}");
        $response->assertRedirect(route('coordinator.events.index'));
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_events_search_returns_json(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator']);
        Event::factory()->create([
            'coordinator_id' => $coordinator->id,
            'title' => 'Cultural Night 2024',
            'status' => 'published',
        ]);

        $response = $this->getJson('/api/events/search?search=Cultural');
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Cultural Night 2024']);
    }
}
