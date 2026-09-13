<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1 Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@festloop.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 3 Coordinators
        $coordinators = collect();
        for ($i = 1; $i <= 3; $i++) {
            $coordinators->push(User::create([
                'name' => "Coordinator $i",
                'email' => "coordinator{$i}@festloop.com",
                'password' => Hash::make('password'),
                'role' => 'coordinator',
            ]));
        }

        // 5 Attendees
        $attendees = collect();
        for ($i = 1; $i <= 5; $i++) {
            $attendees->push(User::create([
                'name' => "Attendee $i",
                'email' => "attendee{$i}@festloop.com",
                'password' => Hash::make('password'),
                'role' => 'attendee',
            ]));
        }

        // 10 Events
        $categories = ['cultural', 'technical', 'sports', 'workshop'];
        $venues = ['Main Auditorium', 'Tech Lab', 'Sports Ground', 'Seminar Hall', 'Open Air Theatre'];
        $events = collect();

        for ($i = 1; $i <= 10; $i++) {
            $startTime = Carbon::now()->addDays(rand(-14, 90));
            
            $events->push(Event::create([
                'coordinator_id' => $coordinators->random()->id,
                'title' => "Fest Event $i",
                'description' => "This is a detailed description for Fest Event $i. It promises to be an exciting event.",
                'category' => $categories[array_rand($categories)],
                'venue' => $venues[array_rand($venues)],
                'start_time' => $startTime,
                'end_time' => (clone $startTime)->addHours(rand(2, 6)),
                'registration_deadline' => (clone $startTime)->subDays(rand(1, 3)),
                'capacity_limit' => rand(50, 200),
                'status' => 'published',
            ]));
        }

        // 20 Registrations
        $statuses = ['confirmed', 'confirmed', 'confirmed', 'pending', 'cancelled'];
        
        for ($i = 1; $i <= 20; $i++) {
            $attendee = $attendees->random();
            $event = $events->random();

            // Check if registration already exists to avoid unique constraint violation
            if (!Registration::where('user_id', $attendee->id)->where('event_id', $event->id)->exists()) {
                Registration::create([
                    'user_id' => $attendee->id,
                    'event_id' => $event->id,
                    'status' => $statuses[array_rand($statuses)],
                    'registered_at' => Carbon::now()->subDays(rand(1, 10)),
                ]);
            }
        }
    }
}
