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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\UiBlock::truncate();

        \App\Models\UiBlock::insert([
            [
                'title' => 'Hero banner',
                'type' => 'banner',
                'status' => true,
                'order' => 1,
                'config' => json_encode(['headline' => 'Welcome to our app', 'subtitle' => 'Dynamic UI controlled from admin', 'cta' => 'Explore now']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Top stats',
                'type' => 'stats',
                'status' => true,
                'order' => 2,
                'config' => json_encode(['items' => ['1200 Users', '24/7 Availability']]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Features list',
                'type' => 'list',
                'status' => true,
                'order' => 3,
                'config' => json_encode(['items' => ['Fast', 'Configurable', 'Extensible']]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Card section',
                'type' => 'card',
                'status' => true,
                'order' => 4,
                'config' => json_encode(['cards' => ['Easy config', 'Low code', 'Admin-first']]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
