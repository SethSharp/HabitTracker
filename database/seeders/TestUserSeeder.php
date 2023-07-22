<?php

namespace Database\Seeders;

use App\Enums\Frequency;
use App\Models\Habit;
use App\Models\HabitSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class TestUserSeeder extends Seeder
{
    public function run($user): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'frequency' => Frequency::DAILY->value,
        ]);

        $habit->occurrence_days = '[1,2,3]';

        $habit->save();

        $occurrences = json_decode($habit->occurrence_days);

        foreach ($occurrences as $occurrence) {
            HabitSchedule::factory()->create([
                'habit_id' => $habit->id,
                'user_id' => $user->id,
                'scheduled_completion' => $this->determineDateForHabitCompletion(Frequency::DAILY->value, $occurrence)
            ]);
        }
    }

    // TODO: Will eventually be moved to a trait I think (To be used in a command)
    private function determineDateForHabitCompletion($freq, $day): string
    {
        // With knowledge that this is run on a monday
        return match ($freq) {
            Frequency::DAILY => now()->addDays($day - 1),
            Frequency::WEEKLY => Carbon::parse('2023-07-3')->copy()->addDays(4)->format('Y-m-d'),
            Frequency::MONTHLY => date('Y-m-d', strtotime(date('Y-m') . '-' . $day)),
            default => now(),
        };
    }
}
