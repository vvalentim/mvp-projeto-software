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
            'undefined' => 'Não informado',
            'married' => 'Casado(a)',
            'single' => 'Solteiro(a)',
            'divorced' => 'Divorciado(a)',
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
