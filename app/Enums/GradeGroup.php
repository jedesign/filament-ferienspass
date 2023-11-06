<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum GradeGroup: string implements HasLabel
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

    public function getLabel(): ?string
    {
        return match ($this->value) {
            "all" => __("All grades"),
            "lower" => __("1. – 3. grade"),
            "intermediate" => __("4. – 6. grade"),
        };
    }
}
