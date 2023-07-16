<?php

namespace Database\Seeders;

use App\Enums\Frequency;
use App\Models\Habit;
use App\Models\HabitSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;

class HabitTableSeeder extends Seeder
{
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
                Frequency::MONTHLY => '[16]',
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
        switch ($freq) {
            case Frequency::DAILY:
                return now()->addDay();
            case Frequency::WEEKLY:
                return now()->addWeek();
            case Frequency::MONTHLY:
                return date('Y-m-d', strtotime(date('Y-m') . '-' . $day));

        }
        return now();
    }
}
