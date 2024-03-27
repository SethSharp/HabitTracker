<?php

namespace App\Domain\Habits\DataTransferObjects;

use Spatie\LaravelData\Data;
use App\App\Http\Requests\Habits\UpdateHabitRequest;

class UpdateHabitData extends Data
{
    public function __construct(
        public string $name,
        public string $description,
        public string $frequency,
        public string $colour,
        public string $scheduledTo,
    ) {
    }

    public static function fromRequest(
        UpdateHabitRequest $request,
        string             $freq,
        string             $scheduledTo,
    ): self {
        return new self(
            name: $request->input('name'),
            description: $request->input('description'),
            frequency: $freq,
            colour: $request->input('colour'),
            scheduledTo: $scheduledTo,
        );
    }
}
