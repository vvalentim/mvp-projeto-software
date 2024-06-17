<?php

namespace App\Enums;

use App\Enums\Traits\HasValuesEnum;
use App\Enums\Contracts\IsLocalizedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum LeadStatus: string implements HasLabel, HasColor, IsLocalizedEnum
{
    use HasValuesEnum;

    case Unverified = 'unverified';
    case Verified = 'verified';
    case Assigned = 'assigned';

    public static function localizedFilterOptions(): array
    {
        return [
            'unverified' => 'Não verificado',
            'verified' => 'Verificado',
            'assigned' => 'Atribuído'
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            static::Unverified => 'Não verificado',
            static::Verified => 'Verificado',
            static::Assigned => 'Atribuído'
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Unverified => 'danger',
            self::Verified => 'info',
            self::Assigned => 'success'
        };
    }
}
