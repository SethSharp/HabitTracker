<?php

namespace App\Console\Commands\ScheduledHabits;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\Frequency;
use App\Models\HabitSchedule;
use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Http\Controllers\Traits\HabitStorageTrait;

class ScheduleHabitsForMonth extends Command
{
    use ScheduledHabits;
    use HabitStorageTrait;

    protected $signature = 'habits:schedule-habits';
    protected $description = 'Set habits up for the month based on the occurrence days of the habit';

    public function handle()
    {
        $users = User::all();

        // Called at the start of the month, so this references the start of the month
        $startDate = Carbon::now();
        $endDate = Carbon::now()->endOfMonth();

        $users->map(function ($user) use ($startDate, $endDate) {
            $habits = $user->habits()->get();

            $habits->map(function ($habit) use ($user, $startDate, $endDate) {
                if ($habit['frequency']->value == Frequency::MONTHLY->value) {
                    HabitSchedule::factory()->create([
                        'habit_id' => $habit->id,
                        'user_id' => $user->id,
                        'scheduled_completion' => json_decode($habit['occurrence_days'])[0],
                    ]);
                } else {
                    $this->scheduledHabitsOverTimeframe($user, $habit, $startDate, $endDate);
                }
            });
        });
    }
}
