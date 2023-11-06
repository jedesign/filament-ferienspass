<?php

namespace App\Enums;

enum DaySpan: string
{
    case FULL = 'full';
    case MORNING = 'morning';
    case AFTERNOON = 'afternoon';

    public static function values()
    {
        return collect(self::cases())
            ->map(fn($state) => $state->value)
            ->values()
            ->all();
    }

}
