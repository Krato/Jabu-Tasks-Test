<?php

namespace Database\Factories;

use App\Enums\Tasks\Frequency;
use App\Models\Task;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Task $task) {
            dd($task);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start = $this->faker->dateTimeBetween('today', '+1 year');
        $frequency = $this->faker->randomElement(Frequency::cases());

        return [
            'start' => $start->format('Y-m-d'),
            'finish'=> $this->getFinishDate($start, $frequency)->format('Y-m-d'),
            'title' => $frequency->label(),
            'times' => $this->faker->numberBetween(0, 10),
        ];
    }

    private function getFinishDate($start, $frequency)
    {
        $number = $this->faker->numberBetween(1, 10);
        return match ($frequency) {
            Frequency::DAILY => $start->add(new \DateInterval('P'.$number.'D')),
            Frequency::WEEKLY => $start->add(new \DateInterval('P'.$number.'W')),
            Frequency::MONTHLY => $start->add(new \DateInterval('P'.$number.'M')),
            Frequency::YEARLY => $start->add(new \DateInterval('P'.$number.'Y'))
        };
    }
}
