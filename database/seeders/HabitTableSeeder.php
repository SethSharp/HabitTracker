<?php

namespace Database\Seeders;

use App\Models\Habit;
use App\Models\HabitSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class HabitTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $habit = Habit::factory()->create([
                'user_id' => $user->id
            ]);

            HabitSchedule::factory()->create([
                'habit_id' => $habit->id,
                'user_id' => $user->id
            ]);
        }
    }
}
