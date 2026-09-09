<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::insertOrIgnore([
            ['name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pembimbing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Internship', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
