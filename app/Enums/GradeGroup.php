<?php

namespace App\Enums;

enum GradeGroup: string
{
    case ALL = 'all';
    case LOWER = 'lower';
    case INTERMEDIATE = 'intermediate';

    public static function values()
    {
        return collect(self::cases())
            ->map(fn($state) => $state->value)
            ->values()
            ->all();
    }
}
