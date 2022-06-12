<?php

namespace Database\Factories;

use App\Enums\Tasks\Frequency;
use App\Models\User;
use App\Services\Tasks\TaskCreate;
use App\Services\Tasks\TaskFrequency;
use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $tasksNumber = $this->faker->numberBetween(30, 100);

            for ($i = 0; $i < $tasksNumber; $i++) {
                $frequency = $this->faker->randomElement(Frequency::cases());
                $start = $this->faker->dateTimeBetween('today', '+15 days');
                $finish = $this->getFinishDate(clone $start, $frequency);
                $times = $this->faker->numberBetween(0, 10);

                match ($frequency) {
                    Frequency::DAILY => $this->createTaskForDays($user, $start, $finish, $frequency, $times),
                    Frequency::WEEKLY => $this->createTaskForWeeks($user, $start, $finish, $frequency, $times),
                    Frequency::MONTHLY => $this->createTasksForMonths($user, $start, $finish, $frequency, $times),
                    Frequency::YEARLY => $this->createTaskForYears($user, $start, $finish, $frequency, $times)
                };
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Get finish date for task
     * @param $start
     *
     * @return \DateTime
     */
    private function getFinishDate($start, $frequency)
    {
        $number = $this->faker->numberBetween(1, 10);
        return match ($frequency) {
            Frequency::DAILY => $start->add(new DateInterval('P'.$number.'D')),
            Frequency::WEEKLY => $start->add(new DateInterval('P'.$number.'W')),
            Frequency::MONTHLY => $start->add(new DateInterval('P'.$number.'M')),
            Frequency::YEARLY => $start->add(new DateInterval('P'.$number.'Y'))
        };
    }


    private function createTaskForDays(User $user, DateTime $start, DateTime $finish, Frequency $frequency, int $times = 0)
    {
        $frequency =  new TaskFrequency(Frequency::DAILY);
        $title = 'Daily task: '.$this->faker->word();

        return (new TaskCreate)->create($user, $start, $finish, $title, $frequency, $times);
    }

    private function createTaskForWeeks(User $user, DateTime $start, DateTime $finish, Frequency $frequency, $times = 0)
    {
        $weekDays = $this->pickRandomWeekDays();
        $frequency =  new TaskFrequency(Frequency::WEEKLY, $weekDays);

        $title = 'All days '.implode(',', $weekDays).' in the week';
        return (new TaskCreate)->create($user, $start, $finish, $title, $frequency, $times);
    }

    private function createTasksForMonths(User $user, DateTime $start, DateTime $finish, Frequency $frequency, $times = 0)
    {
        if ($this->faker->boolean()) {
            return $this->createTaskForDayInMonth($user, $start, $finish, $frequency, $times);
        }

        return $this->createTaskForDaysInMonths($user, $start, $finish, $frequency, $times);
    }

    public function createTaskForDayInMonth(User $user, DateTime $start, DateTime $finish, Frequency $frequency, $times = 0)
    {
        $day = $this->faker->numberBetween(1, 28);
        $frequency =  new TaskFrequency(Frequency::MONTHLY, [], [], [$day]);

        $title = 'All Day '.$day.' in the year';
        return (new TaskCreate)->create($user, $start, $finish, $title, $frequency, $times);
    }

    private function createTaskForDaysInMonths(User $user, DateTime $start, DateTime $finish, Frequency $frequency, $times = 0)
    {
        $months = $this->pickRandomMonths();
        $monthDays = $this->pickRandomMonthsDays();
        $frequency =  new TaskFrequency(Frequency::MONTHLY, [], $months, $monthDays);

        $title = 'All days '.implode(',', $monthDays).' in '.implode(',', $months).' months';
        return (new TaskCreate)->create($user, $start, $finish, $title, $frequency, $times);
    }


    private function createTaskForYears(User $user, DateTime $start, DateTime $finish, Frequency $frequency, $times = 0)
    {
        $month = $this->pickRandomMonths()[0];
        $monthDay = $this->pickRandomMonthsDays()[0];

        $frequency =  new TaskFrequency(Frequency::YEARLY, [], [$month], [$monthDay]);
        $title = 'All '.$monthDay.' in '.$month.' month';

        return  (new TaskCreate)->create($user, $start, $finish, $title, $frequency, $times);
    }

    /**
     * Pick random week days
     *
     * @return array
     */
    private function pickRandomWeekDays(): array
    {
        $days = ['MO','TU','WE','TH','FR','SA','SU',];

        return $this->faker->randomElements($days, $this->faker->numberBetween(1, 3));
    }

    /**
     * Pick random months
     *
     * @return array
     */
    private function pickRandomMonths(): array
    {
        $months = [1,2,3,4,5,6,7,8,9,10,11,12];

        return $this->faker->randomElements($months, $this->faker->numberBetween(1, 2));
    }


    /**
     * Pick randome month days
     *
     * @return array
     */
    private function pickRandomMonthsDays(): array
    {
        $days = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28];

        return $this->faker->randomElements($days, $this->faker->numberBetween(1, 2));
    }
}
