<?php

namespace App\Services\Tasks;

use App\Enums\Tasks\Frequency;

class TaskFrequency
{
    /**
     * @var string
     */
    protected $frequency;

    /**
     * @var array
     */
    protected $weekDays;

    /**
     * @var array
     */
    protected $months;

    /**
     * @var array
     */
    protected $monthDays;

    /**
     * Create new instance of TaskFrequency
     *
     * @param \App\Enums\Tasks\Frequency $frequency
     * @param array $weekDays
     * @param array $months
     * @param array $monthDays
     */
    public function __construct(Frequency $frequency, array $weekDays = [], array $months = [], array $monthDays = [])
    {
        $this->frequency = $frequency->value;
        $this->weekDays = $weekDays;
        $this->months = $months;
        $this->monthDays = $monthDays;
    }

    /**
     * Get frequency value
     *
     * @return int
     */
    public function getFrequency(): int
    {
        return $this->frequency;
    }

    /**
     * Check if frequency has week days
     *
     * @return bool
     */
    public function hasWeekDays(): bool
    {
        return !empty($this->weekDays);
    }

    /**
     * WeekDays
     *
     * @return array
     */
    public function getWeekDays(): array
    {
        return $this->weekDays;
    }

    /**
     * Check if frequency has months
     *
     * @return bool
     */
    public function hasMonths(): bool
    {
        return !empty($this->months);
    }

    /**
     * Get months
     *
     * @return array
     */
    public function getMonths(): array
    {
        return $this->months;
    }

    /**
     * Check if frequency has month days
     *
     * @return bool
     */
    public function hasMonthDays(): bool
    {
        return !empty($this->monthDays);
    }

    /**
     * Get Month days
     *
     * @return array
     */
    public function getMonthDays(): array
    {
        return $this->monthDays;
    }
}
