<?php

namespace App\Console\Commands;

use Codinglabs\Roles\Role;
use App\Domain\Iam\Models\User;
use Illuminate\Console\Command;

class Fix extends Command
{
    protected $signature = 'fix:things';
    protected $description = 'give admin role to user with email.';

    public function handle()
    {
        $user = User::whereEmail('sesharp@outlook.com')->first();

        $user->roles()->attach(Role::whereName(User::ROLE_ADMIN)->first());
    }
}
