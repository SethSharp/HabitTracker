<?php

namespace App\Domain\Habits\DataTransferObjects;

use Spatie\LaravelData\Data;
use App\Http\Requests\Habits\UpdateHabitRequest;

class UpdateHabitData extends Data
{
    public function __construct(
        public string $name,
        public string $description,
        public string $frequency,
        public string $colour,
    ) {
    }

    public static function fromRequest(
        UpdateHabitRequest $request,
        string             $freq,
    ): self {
        return new self(
            name: $request->input('name'),
            description: $request->input('description'),
            frequency: $freq,
            colour: $request->input('colour')
        );
    }
}
