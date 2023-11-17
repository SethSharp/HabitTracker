<?php

namespace App\Domain\HabitSchedule\Models;

use App\Domain\Iam\Models\User;
use App\Domain\Habits\Models\Habit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// TODO: Should be a pivot table instead of a model
class HabitSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'habit_id',
        'user_id',
        'scheduled_completion',
        'completed',
        'cancelled'
    ];

    protected $casts = [
        'user_id' => 'int',
        'habit_id' => 'int',
        'auction_id' => 'int',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function habit(): BelongsTo
    {
        // TODO: Should not use withTrashed?...
        return $this->belongsTo(Habit::class, 'habit_id')->withTrashed();
    }
}
