<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class KeyPair extends Data
{
    public function __construct(
        public string $key,
        public string $value,
    ) {
    }
}
