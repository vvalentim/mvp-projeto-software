<?php

namespace App\Enums;

use App\Enums\Traits\HasValuesEnum;
use App\Enums\Contracts\IsLocalizedEnum;
use Filament\Support\Contracts\HasLabel;

enum RealEstateTypes: string implements HasLabel, IsLocalizedEnum
{
    use HasValuesEnum;

    case House = 'house';
    case Apartment = 'apartment';
    case Condominium = 'condominium';
    case Landplot = 'landplot';

    public static function localizedFilterOptions(): array
    {
        return [
            static::House->value => 'Casa',
            static::Apartment->value => 'Apartamento',
            static::Condominium->value => 'Casa em condomínio',
            static::Landplot->value => 'Lote',
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            static::House => 'Casa',
            static::Apartment => 'Apartamento',
            static::Condominium => 'Casa em condomínio',
            static::Landplot => 'Lote',
        };
    }
}
