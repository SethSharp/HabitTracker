<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Habit;
use App\Enums\Frequency;
use App\Models\HabitSchedule;
use Illuminate\Database\Seeder;
use App\Http\Controllers\Traits\ScheduledHabits;

class TestUserSeeder extends Seeder
{
    use ScheduledHabits;

    public function run($user): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'frequency' => Frequency::DAILY->value,
        ]);

        $habit->occurrence_days = '[1,2,3,4,5,6,7]';

        $habit->save();

        $occurrences = json_decode($habit->occurrence_days);

        foreach ($occurrences as $occurrence) {
            HabitSchedule::factory()->create([
                'habit_id' => $habit->id,
                'user_id' => $user->id,
                'scheduled_completion' => $this->getDates($occurrence)
            ]);
        }
    }

    private function getDates($day): string
    {
        return Carbon::parse($this->getMonday())->addDays($day - 1);
    }
}
