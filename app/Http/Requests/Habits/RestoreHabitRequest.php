<?php

namespace App\Http\Requests\Habits;

use Illuminate\Foundation\Http\FormRequest;

class RestoreHabitRequest extends FormRequest
{
    public function authorize(): bool
    {
        ray($this->route('id'));
        return $this->user()->can('restore', $this->route('id'));
    }

    public function rules(): array
    {
        return [];
    }
}
