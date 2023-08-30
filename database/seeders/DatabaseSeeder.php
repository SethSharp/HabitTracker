<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Traits\ScheduledHabits;

class DatabaseSeeder extends Seeder
{
    use ScheduledHabits;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Seth Sharp',
            'email' => 'seth@habit-tracker.test',
            'email_verified_at' => now(),
            'password' => Hash::make('123456')
        ]);

        User::factory()->create([
            'name' => 'Testing Account',
            'email' => 'testing@habit-tracker.test',
            'email_verified_at' => now(),
            'password' => Hash::make('123456')
        ]);

        $this->call(UserTableSeeder::class);
        $this->call(HabitTableSeeder::class);
    }
}
