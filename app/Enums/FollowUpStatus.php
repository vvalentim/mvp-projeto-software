<?php

namespace App\Enums;

use App\Enums\Traits\HasValuesEnum;
use App\Enums\Traits\IsKanbanStatus;
use App\Enums\Contracts\IsLocalizedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum FollowUpStatus: string implements HasColor, HasLabel, IsLocalizedEnum
{
    use HasValuesEnum, IsKanbanStatus;

    case Lead = 'lead';
    case Prospect = 'prospect';
    case Opportunity = 'opportunity';
    case Concluded = 'concluded';

    public static function localizedFilterOptions(): array
    {
        return [
            'lead' => 'Lead',
            'prospect' => 'Em visitação',
            'opportunity' => 'Proposta',
            'concluded' => 'Finalizado',
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            static::Lead => 'Lead',
            static::Prospect => 'Em visitação',
            static::Opportunity => 'Proposta',
            static::Concluded => 'Finalizado'
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Lead => 'danger',
            self::Prospect => 'warning',
            self::Opportunity => 'info',
            self::Concluded => 'success'
        };
    }
}
