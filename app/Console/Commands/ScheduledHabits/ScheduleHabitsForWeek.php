<?php

namespace App\Console\Commands\ScheduledHabits;

use Carbon\Carbon;
use App\Models\User;
use App\Models\HabitSchedule;
use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;

class ScheduleHabitsForWeek extends Command
{
    use ScheduledHabits;
    protected $signature = 'habits:schedule-habits';
    protected $description = 'Set habits up for the week based on the occurrence days of the habit';

    public function handle()
    {
        // TODO: Add a check to ensure the command is run on a monday
        $users = User::all();
        $users->map(function ($user) {
            $habits = $user->habits()->get();

            $habits->map(function ($habit) use ($user) {

                $freq = $habit->frequency;
                $occurrences = json_decode($habit->occurrence_days);

                foreach ($occurrences as $occurrence) {
                    HabitSchedule::create([
                        'habit_id' => $habit->id,
                        'user_id' => $user->id,
                        'scheduled_completion' => $this->determineDateForHabitCompletion($freq, $occurrence, Carbon::now())
                    ]);
                }
            });
        });
    }
}
