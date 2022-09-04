<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
use ReflectionClass;

trait ConstantArrayable
{
    /**
     * @return array<string, string>
     */
    public static function toArray(): array
    {
        $reflectionClass = new ReflectionClass(__CLASS__);

        return $reflectionClass->getConstants();
    }

    /**
     * @return Collection<string, string>
     */
    public static function toCollection(): Collection
    {
        return collect(self::toArray());
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_values(self::toArray());
    }

    public static function rule(): In
    {
        return Rule::in(self::values());
    }
}
