<?php

namespace App\Services\Tasks;

use App\Enums\Tasks\Status;
use App\Models\Task;
use App\Models\User;
use App\Services\Tasks\TaskFrequency;
use DateTime;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use Recurr\Transformer\ArrayTransformerConfig;

class TaskCreate
{
    /**
     * Create task and dates for given task
     *
     * @param \App\Models\User $user
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param string $title
     * @param \App\Services\Tasks\TaskFrequency $taskFrequency
     * @param int $maxIterates
     *
     * @return \App\Models\Task
     */
    public function create(User $user, DateTime $startDate, DateTime $endDate, string $title, TaskFrequency $taskFrequency, $times = 0, $maxIterations = 732): Task
    {
        $rule = $this->getRule($startDate, $endDate, $taskFrequency);
        $dates = $this->getRuleDates($rule, $times);

        $task = $user->tasks()->create([
            'start' => $startDate,
            'finish' => $endDate,
            'title' => $title,
            'times' => $times,
            'status' => Status::PENDING,
        ]);

        $task->items()
            ->createMany(collect($dates)->map(function ($date) {
                return [
                    'start' => $date->getStart()->format('Y-m-d'),
                    'status' => Status::PENDING,
                ];
            })->toArray());

        return $task;
    }

    /**
     * Get date rule for task
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param bool $frequency
     * @param array $days
     *
     * @return \Recurr\Rule
     */
    private function getRule(DateTime $startDate, DateTime $endDate, TaskFrequency $taskFrequency): Rule
    {
        $rule = (new Rule)
            ->setStartDate($startDate)
            ->setUntil($endDate)
            ->setFreq($taskFrequency->getFrequency());

        if ($taskFrequency->hasWeekDays()) {
            $rule->setByDay($taskFrequency->getWeekDays());
        }

        if ($taskFrequency->hasMonths()) {
            $rule->setByMonth($taskFrequency->getMonths());
        }

        if ($taskFrequency->hasMonthDays()) {
            $rule->setByMonthDay($taskFrequency->getMonthDays());
        }

        return $rule;
    }


    /**
     * Get dates for task
     *
     * @param \Recurr\Rule $rule
     * @param int $maxIterates
     *
     * @return array
     */
    private function getRuleDates(Rule $rule, $maxIterates = 732): array
    {
        $transformerConfig = new ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        if ($maxIterates > 0) {
            $transformerConfig->setVirtualLimit($maxIterates);
        }

        $transformer = new ArrayTransformer();
        $transformer->setConfig($transformerConfig);
        $dates = $transformer->transform($rule);

        return $dates->toArray();
    }
}
