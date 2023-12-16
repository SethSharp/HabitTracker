<?php

namespace App\Domain\Habits\DataTransferObjects;

use Spatie\LaravelData\Data;
use App\Http\Requests\Habits\StoreHabitRequest;

class StoreHabitData extends Data
{
    public function __construct(
        public int     $userId,
        public string  $name,
        public ?string $description,
        public string  $frequency,
        public ?string $scheduledTo,
        public string  $occurrenceDays,
        public string  $colour,
    ) {}

    public static function fromRequest(
        StoreHabitRequest $request,
        string            $freq,
        ?string           $scheduledTo,
        string            $occurrenceDays,
    ): self {
        return new self(
            userId: $request->user()->id,
            name: $request->input('name'),
            description: $request->input('description'),
            frequency: $freq,
            scheduledTo: $scheduledTo,
            occurrenceDays: $occurrenceDays,
            colour: $request->input('colour')
        );
    }
}
