<?php

namespace App\Enums;

enum CourseState: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case TENTATIVE = 'tentative';
    case CANCELED = 'canceled';

    public static function values()
    {
        return collect(self::cases())
            ->map(fn($state) => $state->value)
            ->values()
            ->all();
    }
}
