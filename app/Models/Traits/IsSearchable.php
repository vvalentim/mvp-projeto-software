<?php

namespace App\Models\Traits;

trait IsSearchable
{
    public abstract function getSearchLabel(): string;
}
