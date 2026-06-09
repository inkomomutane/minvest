<?php

namespace App\Concerns;

trait HasEnumToString
{
    public static function toString(): string
    {
        return collect(static::cases())->map(fn ($enum) => $enum->name)->implode(', ');
    }

    public static function fromString(string $string): ?self
    {
        foreach (static::cases() as $enum) {
            if ($enum->name === $string || $enum->value === $string) {
                return $enum;
            }
        }

        return null;
    }
}
