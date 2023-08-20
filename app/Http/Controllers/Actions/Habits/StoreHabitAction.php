<?php

namespace App\Http\Controllers\Actions\Habits;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Habit;
use Illuminate\Support\Collection;
use App\Http\Controllers\Traits\HabitStorageTrait;

class StoreHabitAction
{
    use HabitStorageTrait;

    public function __invoke(User $user, Habit $habit, Collection $data): void
    {
        $scheduledDate = Carbon::now()->startOfWeek();
        $endDate = isset($data['scheduled_to']) && ! is_null($data['scheduled_to']) ? Carbon::parse($data['scheduled_to']) : Carbon::now()->endOfMonth();

        if (isset($data['start_next_week']) && ! is_null($data['start_next_week']) && $data['start_next_week']) {
            $scheduledDate->addWeek();
        }

        $this->scheduledHabitsOverTimeframe($user, $habit, $scheduledDate, $endDate);
    }
}
