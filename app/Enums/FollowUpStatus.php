<?php

namespace App\Enums;

use App\Enums\Traits\HasValuesEnum;
use App\Enums\Traits\IsKanbanStatus;

enum FollowUpStatus: string
{
    use HasValuesEnum, IsKanbanStatus;

    case Lead = 'lead';
    case Prospect = 'prospect';
    case Opportunity = 'opportunity';
    case Concluded = 'concluded';

    public function getTitle(): string
    {
        return $this->name;
    }
}
