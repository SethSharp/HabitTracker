<?php

namespace App\Domain\Habits\Models;

use App\Domain\Iam\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Frequency\Enums\Frequency;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domain\HabitSchedule\Models\HabitSchedule;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// TODO: Should not have user_id, should have a pivot table user_habit
class Habit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'frequency',
        'occurrence_days',
        'icon',
        'colour',
        'scheduled_to'
    ];

    protected $casts = [
        'user_id' => 'int',
        'frequency' => Frequency::class,
    ];

    public function toCacheArray(): array
    {
        return [
            'name' => $this->name,
            'completed' => $this->completed,
            'colour' => $this->colour
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function habitSchedule(): BelongsTo
    {
        return $this->belongsTo(HabitSchedule::class, 'habit_id');
    }
}
