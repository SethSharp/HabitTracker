<?php

namespace Database\Seeders;

use App\Enums\Frequency;
use App\Models\Habit;
use App\Models\HabitSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class HabitTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {

            $frequencies = Frequency::cases();
            $randomFrequency = Arr::random($frequencies);

            $habit = Habit::factory()->create([
                'user_id' => $user->id,
                'frequency' => $randomFrequency->value
            ]);

            $freq = $habit->frequency;

            HabitSchedule::factory()->create([
                'habit_id' => $habit->id,
                'user_id' => $user->id,
                'scheduled_completion' => function() use ($freq) {
                    switch ($freq) {
                        case Frequency::DAILY:
                            return now()->addDay();
                        case Frequency::WEEKLY:
                            return now()->addWeek();
                        case Frequency::MONTHLY:
                            return now()->addMonth();
                    }
                }
            ]);
        }
    }
}
