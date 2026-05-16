<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Database\Seeders\LocationSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin FloraMart',
            'email' => 'admin@floramart.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $categories = [
            'Buket Wisuda',
            'Buket Pernikahan',
            'Bunga Papan Duka Cita',
            'Bunga Papan Ucapan',
            'Dekorasi Ruangan',
            'Tanaman Hias'
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat)
            ]);
        }
        
        $this->call([
            LocationSeeder::class,
        ]);
    }
}