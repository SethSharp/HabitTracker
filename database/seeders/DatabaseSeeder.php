<?php

namespace Database\Seeders;

use App\Http\Controllers\Traits\ScheduledHabits;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use ScheduledHabits;

    public function run(): void
    {
        if (app()->environment('local', 'testing')) {
             Carbon::setTestNow(Carbon::parse($this->getMonday()));
        }

        $user = User::factory()->create([
            'name' => 'Testing Account',
            'email' => 'user@habittracker.test',
            'email_verified_at' => now(),
            'password' => Hash::make('123456')
        ]);

        $this->call(UserTableSeeder::class);
        $this->call(HabitTableSeeder::class);
        $this->call(TestUserSeeder::class, false, ['user' => $user]);
    }
}
