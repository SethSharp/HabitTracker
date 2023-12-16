<?php

namespace App\Domain\Iam\Models;

use Codinglabs\Roles\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use App\Domain\Habits\Models\Habit;
use Illuminate\Notifications\Notifiable;
use App\Domain\Emails\Models\EmailPreferences;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Domain\HabitSchedule\Models\HabitSchedule;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    const ROLE_ADMIN = 'admin';
    const ROLE_MEMBER = 'member';

    protected $fillable = [
        'name',
        'email',
        'password',
        // TODO: Remove
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function habits(): HasMany
    {
        return $this->hasMany(Habit::class);
    }

    public function scheduledHabits(): HasMany
    {
        return $this->hasMany(HabitSchedule::class, 'user_id');
    }

    public function emailPreferences(): HasOne
    {
        return $this->hasOne(EmailPreferences::class, 'user_id');
    }
}
