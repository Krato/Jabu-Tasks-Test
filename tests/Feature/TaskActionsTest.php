<?php

use App\Enums\Tasks\Frequency;
use App\Enums\Tasks\Status;
use App\Models\User;
use App\Services\Tasks\TaskAction;
use App\Services\Tasks\TaskCreate;
use App\Services\Tasks\TaskFrequency;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    $start = new DateTime('2022-07-01');
    $finish = new DateTime('2022-07-15');

    // Dek 1 ak 15 de junio
    $frequency =  new TaskFrequency(Frequency::DAILY);
    (new TaskCreate)->create($user, $start, $finish, 'Junio 1 al 15', $frequency, 4);
});

test('task item can be completed', function () {
    $user = User::find(1);
    $task = $user->tasks()->first();
    $item = $task->items()->first();

    (new TaskAction)->completeItem($item);

    $item->refresh();
    $this->assertTrue($item->status === Status::COMPLETED);
});

test('task item can be pending', function () {
    $user = User::find(1);
    $task = $user->tasks()->first();
    $item = $task->items()->first();

    (new TaskAction)->pendingItem($item);

    $item->refresh();
    $this->assertTrue($item->status === Status::PENDING);
});


test('task is completed when all items are completed', function () {
    $user = User::find(1);
    $task = $user->tasks()->first();

    foreach ($task->items as $item) {
        (new TaskAction)->completeItem($item);
    }
    $task->refresh();

    $this->assertTrue($task->status === Status::COMPLETED);
});


test('task can be completed', function () {
    $user = User::find(1);
    $task = $user->tasks()->first();

    (new TaskAction)->complete($task);

    $task->refresh();
    $this->assertTrue($task->status === Status::COMPLETED);

    foreach ($task->items as $item) {
        $this->assertTrue($item->status === Status::COMPLETED);
    }
});
