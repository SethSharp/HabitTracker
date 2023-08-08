<?php

namespace App\Models;

use App\Enums\Frequency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Habit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'frequency',
        'occurrence_days',
        'colour',
    ];

    protected $casts = [
        'user_id' => 'int',
        'frequency' => Frequency::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function habitSchedule(): BelongsTo
    {
        return $this->belongsTo(HabitSchedule::class, 'habit_id');
    }
}
