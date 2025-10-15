<?php

namespace App\Enums;

use InvalidArgumentException;
use UnitEnum;

class EnumHelper
{
    /**
     * Convert an enum to an array of values.
     *
     * @param string $enum
     * @return array
     */
    public static function toArray(string $enum): array
    {
        if (!is_subclass_of($enum, UnitEnum::class)) {
            throw new InvalidArgumentException("The provided class must be an instance of UnitEnum.");
        }

        return array_map(fn($case) => $case->value, $enum::cases());
    }

    /**
     * Get an array of enum cases.
     *
     * @param string $enumClass
     * @return array
     */
    public static function toNames(string $enumClass): array
    {
        if (!is_subclass_of($enumClass, UnitEnum::class)) {
            throw new InvalidArgumentException("The provided class must be an instance of UnitEnum.");
        }

        return array_map(fn($case) => $case->name, $enumClass::cases());
    }
}
