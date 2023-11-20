<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum CourseState: string implements HasLabel, HasColor, HasIcon
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

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::TENTATIVE => 'warning',
            self::CANCELED => 'danger',
            default => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DRAFT => 'heroicon-m-pencil',
            self::TENTATIVE => 'heroicon-m-eye',
            self::ACTIVE => 'heroicon-m-check',
            self::CANCELED => 'heroicon-m-x-mark',
        };
    }
}
