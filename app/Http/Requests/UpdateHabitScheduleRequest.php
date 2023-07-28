<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHabitScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // TODO
        return true;
    }

    public function rules(): array
    {
        return [
            'habits' => ['required'],
        ];
    }
}
