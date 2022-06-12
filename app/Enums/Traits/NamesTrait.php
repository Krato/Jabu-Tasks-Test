<?php

namespace App\Enums\Traits;

trait NamesTrait
{
    /**
     * Get the enum names
     *
     * @return array
     */
    public static function names(): array
    {
        $cases = self::cases();

        $names = [];

        foreach ($cases as $case) {
            $names[] = $case->name;
        }

        return $names;
    }
}
