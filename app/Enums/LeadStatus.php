<?php

namespace App\Enums;

use App\Enums\Traits\HasValuesEnum;

enum LeadStatus: string
{
    use HasValuesEnum;

    case Unverified = 'unverified';
    case Verified = 'verified';
    case Assigned = 'assigned';
}
