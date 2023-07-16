<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserTableSeeder::class);
        $this->call(HabitTableSeeder::class);

        User::factory()->create([
            'name' => 'Testing Account',
            'email' => 'user@habittracker.test',
            'email_verified_at' => now(),
            'password' => Hash::make('123456')
        ]);
    }
}
