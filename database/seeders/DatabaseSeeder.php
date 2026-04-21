<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun Admin MDMC Wilayah Kalbar
        User::create([
            'name' => 'MDMC Wilayah Kalbar',
            'email' => 'mdmckalbar@gmail.com',
            'password' => Hash::make('adminkalbar123'),
            'role' => 'admin',

        ]);

        $this->call(PetaBencanaSeeder::class);
    }
}