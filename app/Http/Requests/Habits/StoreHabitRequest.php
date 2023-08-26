<?php

namespace App\Http\Requests\Habits;

use Illuminate\Validation\Validator;
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
            'scheduled_to' => ['required'],
            'colour' => ['required']
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->validate();

        $validator->after(function (Validator $validator) {
            $scheduledTo = $this->input('scheduled_to');
            if ($scheduledTo['length'] > 0 && $scheduledTo['time'] === 0) {
                $validator->errors()->add('scheduled_to', 'Select a time value');
            }
        });
    }
}
