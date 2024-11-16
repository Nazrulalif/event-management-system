<?php

namespace Database\Seeders;

use App\Models\Agent as ModelsAgent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class agent extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsAgent::factory()->count(5)->create();
    }
}
