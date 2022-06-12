<?php

namespace App\Enums\Tasks;

use App\Enums\Traits\NamesTrait;

enum ListBy
{
    use NamesTrait;

    case TODAY;
    case TOMORROW;
    case NEXT_WEEK;
    case NEXT_MONTH;

    public function getName()
    {
        return strtolower($this->name);
    }

    /**
     * Get dates for given task
     *
     * @return array
     */
    public function getDates(): array
    {
        return match ($this) {
            ListBy::TODAY => $this->byToday(),
            ListBy::TOMORROW => $this->byTomorrow(),
            ListBy::NEXT_WEEK => $this->byNextWeek(),
            ListBy::NEXT_MONTH => $this->byNextMonth(),
            default => $this->byToday(),
        };
    }


    /**
     * Filter by todat
     *
     * @return array
     */
    private function byToday()
    {
        return [
            'start' => now()->format('Y-m-d'),
            'end' => now()->format('Y-m-d'),
        ];
    }

    /**
     * Filter by tomorrow.
     *
     * @return array
     */
    private function byTomorrow()
    {
        return [
            'start' => now()->tomorrow()->format('Y-m-d'),
            'end' => now()->tomorrow()->format('Y-m-d'),
        ];
    }

    /**
     * Filter by next week.
     *
     * @return array
     */
    private function byNextWeek()
    {
        return [
            'start' => now()->addWeek()->startOfWeek()->format('Y-m-d'),
            'end' => now()->addWeek()->endOfWeek()->format('Y-m-d'),
        ];
    }

    /**
     * Filter by next month.
     *
     * @return array
     */
    private function byNextMonth()
    {
        return [
            'start' => now()->addMonth()->startOfMonth()->format('Y-m-d'),
            'end' => now()->addMonth()->endOfMonth()->format('Y-m-d'),
        ];
    }
}
