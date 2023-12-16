<?php

namespace App\Http\Requests\HabitSchedule;

use App\Domain\HabitSchedule\Models\HabitSchedule;
use Carbon\Carbon;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CompleteHabitScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
        return auth()->user()->can('manage', [HabitSchedule::class, $this->route('habitSchedule')]);
    }

    public function rules(): array
    {
        return [];
    }

    public function withValidator(Validator $validator)
    {
        $validator->validate();

        ray('here');

        $validator->after(function (Validator $validator) {
            if (Carbon::now()->lte(Carbon::parse($this->route('habitSchedule')->scheduled_completion))) {
                ray('error');
                $validator->errors()->add('habit_schedule', 'Cannot complete a future habit');
            }
        });
    }
}
