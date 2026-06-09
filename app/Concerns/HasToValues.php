<?php

namespace App\Concerns;

trait HasToValues
{
    public static function toValues(): array
    {
        return collect(static::cases())->map(fn ($enum) => $enum->value)->toArray();
    }
}
