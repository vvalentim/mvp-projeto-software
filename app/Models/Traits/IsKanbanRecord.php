<?php

namespace App\Models\Traits;

trait IsKanbanRecord
{
    public abstract function getKanbanRecordTitle(): string;
}
