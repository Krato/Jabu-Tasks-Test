<?php

namespace App\Enums\Traits;

use InvalidArgumentException;

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

    /**
     * Get the enum by name
     *
     * @return self
     */
    public static function getByName(string $name): self
    {
        $cases = self::cases();

        foreach ($cases as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        throw new \InvalidArgumentException('Invalid name');
    }
}
