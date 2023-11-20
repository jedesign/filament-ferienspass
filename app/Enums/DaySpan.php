<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DaySpan: string implements HasLabel
{
    case FULL = 'full';
    case MORNING = 'morning';
    case AFTERNOON = 'afternoon';

    public static function values(): array
    {
        return collect(self::cases())
            ->map(fn($state) => $state->value)
            ->values()
            ->all();
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::FULL => __("Full Day"),
            self::MORNING => __("Morning"),
            self::AFTERNOON => __("Afternoon"),
        };
    }
}
