<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'Team' => $this->faker->randomElement(['Sales Planning (SP)', 'Sales Operation MKS (SO MKS)', 'Sales Operation MKU (SO MKU)']),
            'role_guid' => '2', // Default as staff
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'is_approve' => 'Y',
            'is_active' => 'Y',
            'email_verified_at' => now(),
            'password' => bcrypt('123'),
            'remember_token' => Str::random(10),
        ];
    }

    // Create admin user
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'email' => 'admin@yahoo.com',
                'role_guid' => '1',
                'gender' => 'Male',
            ];
        });
    }

    // Create staff user
    public function staff()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_guid' => '2',
            ];
        });
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
