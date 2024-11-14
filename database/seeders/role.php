<?php

namespace Database\Seeders;

use App\Models\Role as ModelsRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class role extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles with descriptions
        $roles = [
            'Administrator',
            'Staff',
        ];

        // IDs assigned to each role
        $ids = [1, 2];

        // Insert roles into the database
        foreach ($roles as $roleName) {
            ModelsRole::create([
                'id' => array_shift($ids),            // Assign the ID from the $ids array
                'role_name' => $roleName,             // Set role name
            ]);
        }
    }
}
