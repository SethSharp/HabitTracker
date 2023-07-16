<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local', 'testing')) {
            Carbon::setTestNow(Carbon::parse('2023-07-2')); // Monday
        }

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
