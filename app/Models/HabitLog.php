<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HabitLog extends Model
{
    protected $fillable = [
        'habit_id',
        'user_id',
        'log_description',
        'log_type'
    ];

    protected $casts = [
        'user_id' => 'int',
        'habit_id' => 'int',
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
