<?php

namespace App\App\Providers;

use App\Domain\Iam\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Domain\HabitSchedule\Policies\HabitSchedulePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        HabitSchedulePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('viewPulse', function (User $user) {
            return $user->hasRole(User::ROLE_ADMIN);
        });
    }
}
