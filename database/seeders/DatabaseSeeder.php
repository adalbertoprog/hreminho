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
        $defaultPassword = env('DEFAULT_ADMIN_PASSWORD');
        if (empty($defaultPassword)) {
            throw new \RuntimeException('DEFAULT_ADMIN_PASSWORD não está definida no .env. Defina-a antes de correr o seeder.');
        }

        // Default admin user
        User::firstOrCreate(
            ['email' => 'admin@hreminho.com'],
            [
                'name'                => 'Admin',
                'password'            => Hash::make($defaultPassword),
                'role'                => 'admin',
                'must_change_password' => true,
            ]
        );

        // Default HR user
        User::firstOrCreate(
            ['email' => 'hr@hreminho.com'],
            [
                'name'                => 'HR Manager',
                'password'            => Hash::make($defaultPassword),
                'role'                => 'hr',
                'must_change_password' => true,
            ]
        );

        $this->call([
            RealDataSeeder::class,
        ]);
    }
}
