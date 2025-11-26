<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    public function definition(): array
    {
        $startTime = fake()->dateTimeBetween('-1 month', '+2 months');
        
        $endTime = (clone $startTime)->modify('+' . fake()->numberBetween(1, 5) . ' hours');

        return [
            'title' => fake()->catchPhrase() . '活动', 
            'description' => fake()->realText(200),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'location' => fake()->address(),
            'capacity' => fake()->numberBetween(10, 100),
            'status' => fake()->randomElement(['draft', 'published', 'in_progress', 'completed']),
            'created_by' => User::factory(), 
        ];
    }
}