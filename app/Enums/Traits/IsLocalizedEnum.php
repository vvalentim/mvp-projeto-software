<?php

namespace App\Enums\Traits;

trait IsLocalizedEnum
{
    abstract public function getLocalizedLabel(): string;

    abstract public static function localizedFilterOptions(): array;
}
