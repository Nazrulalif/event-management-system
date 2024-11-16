<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\event>
 */
class eventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_title' => fake()->sentence(),
            'platform' => fake()->randomElement(['Landed', 'Highrise']),
            'poster_path' => fake()->word(),
            'period' => fake()->randomNumber(1, false), // Single-digit number, adjust as needed
            'state' => 'Melaka',
            'segment' => 'Consumer', // Fixed spelling
            'objective' => fake()->paragraph(), // Use a single paragraph instead of sentences()
            'status' => fake()->randomElement(['Approve', 'Draft', 'Reject', 'Cancelled', 'Pending']), // Use a broader range of status values if needed
            'start_date' => $startDate = Carbon::now()->startOfMonth()->addDays(fake()->numberBetween(0, Carbon::now()->daysInMonth - 1))->toDateString(), // Random day in current month
            'end_date' => Carbon::parse($startDate)->addDays(fake()->numberBetween(1, 4))->toDateString(), // 1 to 4 days after start_date
            'start_time' => Carbon::createFromTime(fake()->numberBetween(0, 23), fake()->numberBetween(0, 59))->toTimeString(), // Random time
            'end_time' => Carbon::createFromTime(fake()->numberBetween(0, 23), fake()->numberBetween(0, 59))->toTimeString(), // Random time
            'created_by' => User::inRandomOrder()->first()->id, // Retrieve a random user ID
        ];
    }
}
