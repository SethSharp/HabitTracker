<?php

namespace App\Console\Commands;

use Codinglabs\Roles\Role;
use App\Domain\Iam\Models\User;
use Illuminate\Console\Command;

class Bootstrap extends Command
{
    protected $signature = 'bootstrap';
    protected $description = 'Bootstrap all the things';

    public function handle(): void
    {
        Role::firstOrCreate(['name' => User::ROLE_ADMIN]);
    }
}
