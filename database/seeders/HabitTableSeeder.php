<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Habit;
use App\Enums\Frequency;
use Illuminate\Support\Arr;
use App\Models\HabitSchedule;
use Illuminate\Database\Seeder;
use App\Http\Controllers\Traits\ScheduledHabits;

class HabitTableSeeder extends Seeder
{
    use ScheduledHabits;

    public function run(): void
    {
        Carbon::setTestNow(Carbon::parse($this->getDateXDaysAgo(7)));

        $users = User::all();
        $colour = ['#1E90FF', '#90EE90', '#FF8C00', '#00BABD'];

        foreach ($users as $user) {
            $rndFreq = Arr::random(Frequency::cases());

            $habit = Habit::factory()->create([
                'user_id' => $user->id,
                'frequency' => $rndFreq->value,
                'colour' => $colour[array_rand($colour)]
            ]);

            $freq = $habit->frequency;

            $habit->occurrence_days = match ($freq) {
                Frequency::DAILY => '[1,2,3]',
                Frequency::WEEKLY => '[4]',
                Frequency::MONTHLY => '["2023-07-13"]',
                default => '[]',
            };

            $habit->save();

            $occurrences = json_decode($habit->occurrence_days);

            foreach ($occurrences as $occurrence) {
                HabitSchedule::factory()->create([
                    'habit_id' => $habit->id,
                    'user_id' => $user->id,
                    'scheduled_completion' => $this->determineDateForHabitCompletion($freq, $occurrence, Carbon::today())
                ]);
            }
        }
    }
}
