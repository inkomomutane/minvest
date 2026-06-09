<?php

use Illuminate\Database\Eloquent\Relations\Relation;

if (! function_exists('getMorphAlias')) {
    function getMorphAlias(string $class): string
    {
        return Relation::getMorphAlias($class);
    }
}
