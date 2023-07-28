<?php

namespace Tests\Traits;

use Illuminate\Foundation\Testing\RefreshDatabase as BaseRefreshDatabase;

trait RefreshDatabase
{
    use BaseRefreshDatabase;

    protected function refreshTestDatabase(): void
    {
        $this->beginDatabaseTransaction();
    }
}
