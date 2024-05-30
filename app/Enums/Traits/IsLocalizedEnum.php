<?php

namespace App\Enums\Traits;

trait IsLocalizedEnum
{
    abstract public function getLocalizedLabel(): string;
}
