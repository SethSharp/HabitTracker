<?php

namespace Database\Seeders;

use App\Models\Habit;
use App\Models\User;
use Illuminate\Database\Seeder;

class HabitTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Habit::factory()->create([
                'user_id' => $user->id
            ]);
        }
    }
}
