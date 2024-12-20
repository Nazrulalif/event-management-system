<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\agent>
 */
class agentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'channel' => $this->faker->randomElement(['Rovers', 'Agents (UCA/UCP)', 'Nextstar']),
            'password' => bcrypt('123'),
            'phone_number' => $this->faker->phoneNumber(),
        ];
    }
}
