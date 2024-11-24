<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Seeder;

class user extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        ModelsUser::factory()->admin()->create();

        // Create staff users
        ModelsUser::factory()->staff()->count(50)->create();
    }
}
