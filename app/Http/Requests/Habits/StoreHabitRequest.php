<?php

namespace App\Http\Requests\Habits;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHabitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string', 'required', 'min:5', 'max:30'],
            'description' => ['string', 'required', 'min:5', 'max:255'],
            'frequency' => ['int', 'required'],
            'daily_weekly_configuration' => ['array', 'required'],
            'monthly_configuration' => ['string', 'required'],
        ];
    }
}
