<?php

namespace App\Http\Requests\Habits;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHabitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('store', $this->route('habit'));
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'required', 'min:5', 'max:30'],
            'description' => ['string', 'required', 'min:5', 'max:255'],
            'frequency' => ['int', 'required'],
            'daily_config' => ['required_if:frequency,0'],
            'weekly_config' => ['required_if:frequency,1'],
            'monthly_config' => ['required_if:frequency,2'],
        ];
    }
}
