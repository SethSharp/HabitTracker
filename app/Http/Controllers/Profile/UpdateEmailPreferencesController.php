<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Domain\Emails\Models\EmailPreferences;

class UpdateEmailPreferencesController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate([
            'daily_reminder' => ['required', 'boolean'],
            'goal_reminder' => ['required', 'boolean'],
        ]);

        $preference = $request->user()->emailPreferences()->get()->first();

        if (is_null($preference)) {
            EmailPreferences::factory()->create([
                'user_id' => $request->user(),
                'daily_reminder' => $request['daily_reminder'],
                'goal_reminder' => $request['goal_reminder']
            ]);
        } else {
            $preference->update([
                'daily_reminder' => $request['daily_reminder'],
                'goal_reminder' => $request['goal_reminder']
            ]);
        }

        return Redirect::route('profile.edit');
    }
}
