<?php

namespace App\App\Http\Controllers\Auth;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Domain\Iam\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\App\Http\Controllers\Controller;
use App\App\Http\Events\RegisteredEvent;
use App\App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // TODO: Remove
            'email_verified_at' => now(),
        ]);

        event(new RegisteredEvent($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
