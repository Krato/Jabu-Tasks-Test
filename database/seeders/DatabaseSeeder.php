<?php

namespace Database\Seeders;

use App\Enums\Tasks\Frequency;
use App\Models\User;
use Database\Traits\DatabaseTasksTrait;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use DatabaseTasksTrait;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::factory()
            ->afterCreating(function (User $user) {
                if (app()->environment('testing')) {
                    return true;
                }

                $this->faker = Container::getInstance()->make(Generator::class);


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
            })
            ->create([
                'name' => 'Eric',
                'email' => 'ericlagarda@gmail.com',
                'password' => bcrypt('secret'),
            ]);
    }
}
