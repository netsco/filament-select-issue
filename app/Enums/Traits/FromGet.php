<?php

namespace App\Enums\Traits;

trait FromGet
{
    public static function fromGet(string|self|null $value): ?self
    {
        return match (true) {
            $value instanceof self => $value,
            default => self::tryFrom($value),
        };
    }
}
