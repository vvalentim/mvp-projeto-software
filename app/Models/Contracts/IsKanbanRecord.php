<?php

namespace App\Models\Contracts;

interface IsKanbanRecord
{
    public function getKanbanRecordTitle(): string;

    public function getKanbanRecordContent(): string;
}
