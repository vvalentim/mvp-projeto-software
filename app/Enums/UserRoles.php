<?php

namespace App\Enums;

use App\Enums\Traits\HasValuesEnum;
use App\Enums\Traits\IsLocalizedEnum;

enum UserRoles: string
{
    use HasValuesEnum, IsLocalizedEnum;

    case Admin = 'admin';
    case Operator = 'operator';
    case Broker = 'broker';

    public static function localizedFilterOptions(): array
    {
        return [
            'unverified' => 'Não verificado',
            'verified' => 'Verificado',
            'assigned' => 'Atribuído'
        ];
    }

    public function getLocalizedLabel(): string
    {
        return match ($this) {
            static::Admin => 'Administrador',
            static::Operator => 'Operador',
            static::Broker => 'Corretor'
        };
    }
}
