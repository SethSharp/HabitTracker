<?php

namespace App\App\Http\Controllers\Profile;

use Illuminate\Http\RedirectResponse;
use App\App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\App\Http\Requests\ProfileUpdateRequest;

class UpdateProfileController extends Controller
{
    public function __invoke(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }
}
