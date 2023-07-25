<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HabitSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheduled_completion',
        'completed',
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
        return $this->belongsTo(Habit::class, 'habit_id');
    }
}
