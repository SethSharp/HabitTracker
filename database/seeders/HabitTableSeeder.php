<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Enums\Frequency;
use Illuminate\Support\Arr;
use App\Models\HabitSchedule;
use App\Domain\Iam\Models\User;
use Illuminate\Database\Seeder;
use App\Domain\Habits\Models\Habit;
use App\Http\Controllers\Traits\ScheduledHabits;

class HabitTableSeeder extends Seeder
{
    use ScheduledHabits;

    public function run(): void
    {
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
                Frequency::DAILY => '[1,3,5]',
                Frequency::WEEKLY => '[4]',
                Frequency::MONTHLY => json_encode([Carbon::now()->startOfMonth()->addWeek()->addDays(3)->toDateString()]),
                default => '[]',
            };

            $habit->save();

            $occurrences = json_decode($habit->occurrence_days);

            if ($freq->value == Frequency::MONTHLY->value) {
                HabitSchedule::factory()->create([
                    'habit_id' => $habit->id,
                    'user_id' => $user->id,
                    'scheduled_completion' => Carbon::now()->startOfMonth()->addWeek()->addDays(3)->toDateString(),
                ]);
            } else {
                $scheduledDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();

                while ($scheduledDate <= $endDate) {
                    // if today is a day in occurrences add to list
                    if (in_array($scheduledDate->dayOfWeek, $occurrences)) {
                        // if not in the past add to the schedule
                        if (! $scheduledDate <= Carbon::now()) {
                            // create schedule
                            HabitSchedule::factory()->create([
                                'habit_id' => $habit->id,
                                'user_id' => $user->id,
                                'scheduled_completion' => $scheduledDate
                            ]);
                        }
                    }
                    $scheduledDate->addDay();
                }
            }
        }
    }
}
