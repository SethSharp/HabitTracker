<?php

namespace Database\Seeders;

use App\Domain\Iam\Models\User;
use Illuminate\Database\Seeder;
use App\Console\Commands\Bootstrap;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Traits\ScheduledHabits;

class DatabaseSeeder extends Seeder
{
    use ScheduledHabits;

    public function run(): void
    {
        Artisan::call(Bootstrap::class);

        User::factory()->admin()->create([
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
