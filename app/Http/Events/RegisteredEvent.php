<?php

namespace App\Http\Events;

use Illuminate\Support\Facades\Event;
use App\Models\User;

class RegisteredEvent extends Event
{
    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
