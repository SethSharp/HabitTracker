<?php

namespace App\Http\Requests\Habits;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHabitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('habit'));
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'required', 'min:5', 'max:30'],
            'description' => ['nullable', 'max:255'],
            'frequency' => ['int', 'required'],
            'daily_config' => ['required_if:frequency,0'],
            'weekly_config' => ['required_if:frequency,1'],
            'monthly_config' => ['required_if:frequency,2'],
            'colour' => ['required'],
            'scheduled_to' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'name' => "Name is required",
            'daily_config' => 'Select any day or days you would like your habit to repeat on',
            'weekly_config' => 'Select any day of the week you would like your habit to repeat on',
            'monthly_config' => 'Select any date of the month you would like your habit to repeat on',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->validate();

        $validator->after(function (Validator $validator) {
            $scheduledTo = $this->input('scheduled_to');
            if (is_null($this->route('habit')->scheduled_to)) {
                if ($scheduledTo['length'] > 0 && $scheduledTo['time'] === 0) {
                    $validator->errors()->add('scheduled_to', 'Select a time value');
                }
            }
        });
    }
}
