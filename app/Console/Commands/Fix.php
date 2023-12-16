<?php

namespace App\Console\Commands;

use App\Domain\Iam\Models\Role;
use App\Domain\Iam\Models\User;
use Illuminate\Console\Command;

class Fix extends Command
{
    protected $signature = 'fix:things';
    protected $description = 'give admin role to user with email.';

    public function handle()
    {
        $user = User::whereEmail('sesharp@outlook.com')->first();

        $user->assignRole(Role::whereName(User::ROLE_ADMIN));
    }
}
