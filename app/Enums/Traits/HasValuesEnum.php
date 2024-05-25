<?php

namespace App\Enums\Traits;

trait HasValuesEnum
{
    public static function values(): array
    {
        return array_map(fn ($enum) => $enum->value, static::cases());
    }
}
