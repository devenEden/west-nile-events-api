<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $password = Hash::make('Dassword@123');

        User::firstOrCreate([
            'name' => 'Feta Deven',
            'organizer_name' => 'Eden Events',
            'email' => 'devenfeta19@gmail.com',
            'phone' => '256771403535',
            'email_verified_at' => now(),
            'password' => $password,
            'gender' => 'MALE',
        ]);
    }
}
