<?php

use App\Enums\Tasks\Frequency;
use App\Models\User;
use App\Services\Tasks\TaskCreate;
use App\Services\Tasks\TaskFrequency;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(fn () => User::factory()->create());

test('can create a task', function () {
    $user = User::find(1);
    $start = new DateTime('2022-07-01');
    $finish = new DateTime('2022-07-15');

    // Dek 1 ak 15 de junio
    $frequency =  new TaskFrequency(Frequency::DAILY);
    (new TaskCreate)->create($user, $start, $finish, 'Junio 1 al 15', $frequency);

    $task = $user->tasks->first();

    $this->assertEquals('Junio 1 al 15', $task->title);
});

test('15 days task', function () {
    $user = User::find(1);
    $start = new DateTime('2022-07-01');
    $finish = new DateTime('2022-07-15');

    // Dek 1 ak 15 de junio
    $frequency =  new TaskFrequency(Frequency::DAILY);
    (new TaskCreate)->create($user, $start, $finish, 'Junio 1 al 15', $frequency);

    $task = $user->tasks->first();

    $this->assertCount(15, $task->items);
});

test('weekdays task', function () {
    $user = User::find(1);
    $start = new DateTime('2022-07-01');
    $finish = new DateTime('2022-08-01');

    $frequency =  new TaskFrequency(Frequency::MONTHLY, ['MO', 'TU']);
    (new TaskCreate)->create($user, $start, $finish, 'Monday and tuesday on a month', $frequency);

    $task = $user->tasks->first();

    foreach ($task->items as $item) {
        $isMondayOrTuesday = $item->start->format('N') == 1 || $item->start->format('N') == 2;
        expect($isMondayOrTuesday)->toBeTrue();
    }
});

test('month days task', function () {
    $user = User::find(1);
    $start = new DateTime('2022-07-01');
    $finish = new DateTime('2024-12-31');

    $frequency =  new TaskFrequency(Frequency::MONTHLY, [], [3, 5], [5, 7]);
    (new TaskCreate)->create($user, $start, $finish, 'All 5 and 7 of march and may', $frequency);

    $task = $user->tasks->first();

    foreach ($task->items as $item) {
        $isMarchOrMay = $item->start->format('m') == 3 || $item->start->format('m') == 5;
        $isFiveOrSeven = $item->start->format('d') == 5 || $item->start->format('d') == 7;

        // check two conditions
        expect($isMarchOrMay && $isFiveOrSeven)->toBeTrue();
    }
});

test('one task every year', function () {
    $user = User::find(1);
    $start = new DateTime('2022-01-01');
    $finish = new DateTime('2027-12-31');

    $frequency =  new TaskFrequency(Frequency::YEARLY, [], [1], [1]);
    (new TaskCreate)->create($user, $start, $finish, 'All day 1 of january year', $frequency);

    $task = $user->tasks->first();

    foreach ($task->items as $item) {
        $isDayOne = $item->start->format('d') == 1;

        // check two conditions
        expect($isDayOne)->toBeTrue();
    }

    $this->assertCount(6, $task->items);
});


test('task with a limit of n times', function () {
    $user = User::find(1);
    $start = new DateTime('2022-07-01');
    $finish = new DateTime('2022-07-15');

    // Dek 1 ak 15 de junio
    $frequency =  new TaskFrequency(Frequency::DAILY);
    (new TaskCreate)->create($user, $start, $finish, 'Junio 1 al 15', $frequency, 4);

    $task = $user->tasks->first();

    $this->assertCount(4, $task->items);
});
