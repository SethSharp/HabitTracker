<?php

namespace App\App\Console\Commands\ScheduledHabits;

use Carbon\Carbon;
use App\Support\CacheKeys;
use App\Domain\Iam\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Domain\Frequency\Enums\Frequency;
use App\Domain\HabitSchedule\Models\HabitSchedule;
use App\App\Http\Controllers\Traits\ScheduledHabits;

class ScheduleHabitsForMonth extends Command
{
    use ScheduledHabits;

    protected $signature = 'habits:schedule';
    protected $description = 'Set habits up for the month based on the occurrence days of the habit';

    public function handle(): void
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

            $cacheKey = CacheKeys::scheduledHabitsForTheMonth($user, Carbon::now()->monthName);

            Cache::forget($cacheKey);
        });
    }
}
