<?php

namespace App\App\Http\Controllers\Profile;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EditProfileController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'emailPreferences' => $request->user()->emailPreferences()->get()->first()
        ]);
    }
}
