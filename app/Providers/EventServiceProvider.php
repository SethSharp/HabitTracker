<?php

namespace App\Providers;

use App\Http\Events\RegisteredEvent;
use Illuminate\Auth\Events\Verified;
use App\Http\Notifications\VerifyEmailNotification;
use App\Http\Notifications\RegisteredNotificationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        RegisteredEvent::class => [
            VerifyEmailNotification::class,
        ],
        Verified::class => [
            RegisteredNotificationListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
