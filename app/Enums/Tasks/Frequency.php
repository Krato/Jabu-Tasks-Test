<?php

namespace App\Enums\Tasks;

enum Frequency: int
{
    case YEARLY = 0;
    case MONTHLY = 1;
    case WEEKLY = 2;
    case DAILY = 3;

    /**
     * Return label of current enum
     *
     * @return string
     */
    public function label(): string
    {
        return static::getLabel($this->value);
    }

    /**
     * Return label of enum
     *
     * @param int $value
     *
     * @return string
     */
    public static function getLabel(int $value): string
    {
        return match ($value) {
            Frequency::YEARLY->value => 'YEARLY',
            Frequency::MONTHLY->value => 'MONTHLY',
            Frequency::WEEKLY->value => 'WEEKLY',
            Frequency::DAILY->value => 'DAILY',
            default => 'DAILY'
        };
    }
}
