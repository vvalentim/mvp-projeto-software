<?php

namespace App\Enums;

use App\Enums\Traits\HasValuesEnum;
use App\Enums\Contracts\IsLocalizedEnum;
use Filament\Support\Contracts\HasLabel;

enum MaritalStatus: string implements HasLabel, IsLocalizedEnum
{
    use HasValuesEnum;

    case Undefined = 'undefined';
    case Married = 'married';
    case Single = 'single';
    case Divorced = 'divorced';

    public static function localizedFilterOptions(): array
    {
        return [
            static::Undefined->value => 'Não informado',
            static::Married->value => 'Casado(a)',
            static::Single->value => 'Solteiro(a)',
            static::Divorced->value => 'Divorciado(a)',
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            static::Undefined => 'Não informado',
            static::Married => 'Casado(a)',
            static::Single => 'Solteiro(a)',
            static::Divorced => 'Divorciado(a)',
        };
    }
}
