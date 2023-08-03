<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class UpdateEmailPreferencesController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate([
            'daily_reminder' => ['required', 'boolean'],
        ]);

        $preference = $request->user()->emailPreferences()->get()->first();

        $preference->update([
           'daily_reminder' => $request['daily_reminder']
        ]);

        return Redirect::route('profile.edit');
    }
}
