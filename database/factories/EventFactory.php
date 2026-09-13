<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('+1 week', '+3 months');
        $endTime = (clone $startTime)->modify('+3 hours');
        $deadline = (clone $startTime)->modify('-2 days');

        return [
            'coordinator_id' => User::factory()->state(['role' => 'coordinator']),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(3, true),
            'category' => $this->faker->randomElement(['cultural', 'technical', 'sports', 'workshop', 'seminar', 'other']),
            'venue' => $this->faker->company() . ' Hall',
            'start_time' => $startTime,
            'end_time' => $endTime,
            'registration_deadline' => $deadline,
            'capacity_limit' => $this->faker->numberBetween(20, 500),
            'banner_image' => null,
            'status' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'draft']);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'cancelled']);
    }
}
