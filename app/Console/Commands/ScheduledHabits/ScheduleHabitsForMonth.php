<?php

namespace App\Console\Commands\ScheduledHabits;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\Frequency;
use App\Models\HabitSchedule;
use Illuminate\Console\Command;
use App\Http\Controllers\Traits\HabitStorage;
use App\Http\Controllers\Traits\ScheduledHabits;

class ScheduleHabitsForMonth extends Command
{
    use ScheduledHabits;
    use HabitStorage;

    protected $signature = 'habits:schedule';
    protected $description = 'Set habits up for the month based on the occurrence days of the habit';

    public function handle()
    {
        $users = User::all();

        $users->map(function ($user) {
            $habits = $user->habits()->get();

            $habits->map(function ($habit) use ($user) {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();

                if ($habit->frequency->value == Frequency::MONTHLY->value) {
                    HabitSchedule::factory()->create([
                        'habit_id' => $habit->id,
                        'user_id' => $user->id,
                        'scheduled_completion' => json_decode($habit->occurrence_days)[0],
                    ]);
                } else {
                    $occurrences = json_decode($habit->occurrence_days);

                    while ($startDate <= $endDate) {
                        if (in_array($startDate->dayOfWeek, $occurrences)) {
                            HabitSchedule::factory()->create([
                                'habit_id' => $habit->id,
                                'user_id' => $user->id,
                                'scheduled_completion' => $startDate
                            ]);
                        }
                        $startDate->addDay();
                    }
                }
            });
        });
    }
}
