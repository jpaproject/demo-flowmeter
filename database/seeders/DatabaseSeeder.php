<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      $this->call([
            RoleSeeder::class, // Jalankan ini terlebih dahulu
            UserSeeder::class, // Kemudian jalankan ini
            // ... seeder lain jika ada
        ]);
    }
}
