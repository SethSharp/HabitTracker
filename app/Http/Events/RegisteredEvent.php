<?php

namespace App\Http\Events;

use App\Models\User;
use Illuminate\Support\Facades\Event;

class RegisteredEvent extends Event
{
    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
