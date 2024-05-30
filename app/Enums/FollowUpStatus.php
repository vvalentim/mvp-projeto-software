<?php

namespace App\Enums;

use App\Enums\Traits\HasValuesEnum;
use App\Enums\Traits\IsKanbanStatus;
use App\Enums\Traits\IsLocalizedEnum;

enum FollowUpStatus: string
{
    use HasValuesEnum, IsKanbanStatus, IsLocalizedEnum;

    case Lead = 'lead';
    case Prospect = 'prospect';
    case Opportunity = 'opportunity';
    case Concluded = 'concluded';

    public function getLocalizedLabel(): string
    {
        return match ($this) {
            static::Lead => 'Lead',
            static::Prospect => 'Em visitação',
            static::Opportunity => 'Proposta',
            static::Concluded => 'Finalizado'
        };
    }
}
