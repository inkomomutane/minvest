<?php

namespace App\Concerns;

use RuntimeException;

trait HasFromString
{
    public static function fromString(string $value)
    {

        foreach (static::cases() as $case) {
            if ($case->name === $value){
                return $case;
            }
        }
        throw new RuntimeException(__('Enum not found'));
    }
}
