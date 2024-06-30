<?php

namespace App\Enums;

use App\Enums\Traits\HasValuesEnum;
use App\Enums\Contracts\IsLocalizedEnum;
use Filament\Support\Contracts\HasLabel;

enum UserRoles: string implements HasLabel, IsLocalizedEnum
{
    use HasValuesEnum;

    case Admin = 'admin';
    case Operator = 'operator';
    case Broker = 'broker';

    public static function localizedFilterOptions(): array
    {
        return [
            static::Admin->value => 'Administrador',
            static::Operator->value => 'Operador',
            static::Broker->value => 'Corretor'
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            static::Admin => 'Administrador',
            static::Operator => 'Operador',
            static::Broker => 'Corretor'
        };
    }
}
