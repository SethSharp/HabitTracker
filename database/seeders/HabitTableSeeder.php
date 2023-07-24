<?php

namespace Database\Seeders;

use App\Enums\Frequency;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Models\Habit;
use App\Models\HabitSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class HabitTableSeeder extends Seeder
{
    use ScheduledHabits;

    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $rndFreq = Arr::random(Frequency::cases());

            $habit = Habit::factory()->create([
                'user_id' => $user->id,
                'frequency' => $rndFreq->value,
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
                    'scheduled_completion' => $this->determineDateForHabitCompletion($freq, $occurrence)
                ]);
            }
        }
    }

    private function determineDateForHabitCompletion($freq, $day): string
    {
        return match ($freq) {
            Frequency::DAILY => Carbon::parse($this->getMonday())->addDays($day - 1),
            Frequency::WEEKLY => Carbon::parse($this->getMonday())->copy()->addDays(4)->format('Y-m-d'),
            Frequency::MONTHLY => date('Y-m-d', strtotime(date('Y-m') . '-' . $day)),
            default => now(),
        };
    }
}
