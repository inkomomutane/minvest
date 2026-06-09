<?php

namespace App\Concerns;
use App\Data\KeyPair;

trait HasToObjects
{
    public static function toObjects(): array
    {
        return collect(static::cases())->map(fn ($enum) => KeyPair::from([
            'key' => $enum->name,
            'value' => $enum->value,
        ]))->toArray();
    }
}
