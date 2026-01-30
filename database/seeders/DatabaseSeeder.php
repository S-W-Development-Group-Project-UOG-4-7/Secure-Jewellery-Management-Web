<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // Admin
    \App\Models\User::create([
        'name' => 'Super Admin',
        'username' => 'admin',
        'email' => 'admin@sjm.com',
        'role' => 'admin',
        'password' => bcrypt('password')
    ]);

    // Manager
    \App\Models\User::create([
        'name' => 'Store Manager',
        'username' => 'manager',
        'email' => 'manager@sjm.com',
        'role' => 'manager',
        'password' => bcrypt('password')
    ]);

    // Supplier
    \App\Models\User::create([
        'name' => 'Logistics Partner',
        'username' => 'supplier',
        'email' => 'supplier@sjm.com',
        'role' => 'supplier',
        'password' => bcrypt('password')
    ]);

    // Customer
    \App\Models\User::create([
        'name' => 'Valued Client',
        'username' => 'customer',
        'email' => 'client@gmail.com',
        'role' => 'customer',
        'password' => bcrypt('password')
    ]);
}
}
