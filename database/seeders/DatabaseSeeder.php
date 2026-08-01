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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'admin User',
        //     'email' => 'admin@user.com',
        //     'password' => Hash::make('Admin@2026!'),
        //     'email_verified_at' => now(),
        // ]);
        User::firstOrCreate(
            ['email' => 'admin@user.com'], // 🔍 Condição de busca
            [                              // 📝 Dados para criar se NÃO encontrar
                'name' => 'admin User',
                'password' => Hash::make('Admin@2026!'),
                'email_verified_at' => now(),
            ]
        );
        $this->call([
            CursosSeeder::class,
            NiveisSeeder::class,
            ProfissoesSeeder::class
        ]);
    }
}
