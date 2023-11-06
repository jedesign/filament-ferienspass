<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum CourseState: string implements HasLabel
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

    public function getLabel(): ?string
    {
        return __(Str::title($this->value));
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
