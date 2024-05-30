<?php

namespace App\Enums;

use App\Enums\Traits\HasValuesEnum;
use App\Enums\Traits\IsLocalizedEnum;

enum LeadStatus: string
{
    use HasValuesEnum, IsLocalizedEnum;

    case Unverified = 'unverified';
    case Verified = 'verified';
    case Assigned = 'assigned';

    public function getLocalizedLabel(): string
    {
        return match ($this) {
            static::Unverified => 'Não verificado',
            static::Verified => 'Verificado',
            static::Assigned => 'Atribuído'
        };
    }
}
