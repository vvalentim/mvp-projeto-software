<?php

namespace App\Enums;

use App\Enums\Traits\HasValuesEnum;
use App\Enums\Traits\IsLocalizedEnum;

enum MaritalStatus: string
{
    use HasValuesEnum, IsLocalizedEnum;

    case Nullable = '';
    case Married = 'married';
    case Single = 'single';
    case Divorced = 'divorced';

    public function getLocalizedLabel(): string
    {
        return match ($this) {
            static::Married => 'Casado(a)',
            static::Single => 'Solteiro(a)',
            static::Divorced => 'Divorciado(a)',
            default => '',
        };
    }
}
