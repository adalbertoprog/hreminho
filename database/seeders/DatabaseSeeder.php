<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default admin user
        User::firstOrCreate(
            ['email' => 'admin@hreminho.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make(env('DEFAULT_ADMIN_PASSWORD')),
                'role'     => 'admin',
            ]
        );

        // Default HR user
        User::firstOrCreate(
            ['email' => 'hr@hreminho.com'],
            [
                'name'     => 'HR Manager',
                'password' => Hash::make(env('DEFAULT_ADMIN_PASSWORD')),
                'role'     => 'hr',
            ]
        );

        $this->call([
            RealDataSeeder::class,
        ]);
    }
}
