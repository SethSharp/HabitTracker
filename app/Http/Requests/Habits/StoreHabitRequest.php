<?php

namespace App\Http\Requests\Habits;

use Illuminate\Foundation\Http\FormRequest;

class StoreHabitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string', 'required', 'min:5', 'max:30'],
            'description' => ['string', 'required', 'min:5', 'max:255'],
            'frequency' => ['int', 'required'],
            'daily_config' => ['required_if:frequency,0'],
            'weekly_config' => ['required_if:frequency,1'],
            'monthly_config' => ['required_if:frequency,2'],
            'start_next_week' => ['boolean'],
            'scheduled_to' => ['nullable', 'string'],
            'colour' => ['required']
        ];
    }
}
