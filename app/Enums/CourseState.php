<?php

namespace App\Enums;

enum CourseState: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case TENTATIVE = 'tentative';
    case CANCELED = 'canceled';

    public static function values() : array
    {
        return collect(self::cases())
            ->map(fn($state) => $state->value)
            ->values()
            ->all();
    }

    public static function color($value) : string
    {
        return match ($value) {
            self::ACTIVE => 'success',
            self::TENTATIVE => 'warning',
            self::CANCELED => 'danger',
            default => 'gray',
        };
    }
}
