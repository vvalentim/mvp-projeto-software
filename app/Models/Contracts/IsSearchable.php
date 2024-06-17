<?php

namespace App\Models\Contracts;

interface IsSearchable
{
    public function getSearchLabel(): string;
}
