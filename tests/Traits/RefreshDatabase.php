<?php

namespace Tests\Traits;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\RefreshDatabase as BaseRefreshDatabase;

trait RefreshDatabase
{
    use BaseRefreshDatabase;

    protected function refreshTestDatabase(): void
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('migrate:fresh', ['--path' => 'database/migrations']);

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }
        $this->beginDatabaseTransaction();
    }
}
