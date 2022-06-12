<?php

use App\Enums\Tasks\Frequency;
use App\Enums\Tasks\ListBy;
use App\Enums\Tasks\Status;
use App\Models\User;
use App\Services\Tasks\TaskAction;
use App\Services\Tasks\TaskCreate;
use App\Services\Tasks\TaskFrequency;
use App\Services\Tasks\TaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    $start = new DateTime('2022-06-01');
    $finish = new DateTime('2022-06-15');

    // Dek 1 ak 15 de junio
    $frequency =  new TaskFrequency(Frequency::DAILY);
    (new TaskCreate)->create($user, $start, $finish, 'Junio 1 al 15', $frequency);
});


test('today task can be listed', function () {
    $user = User::find(1);

    $items = (new TaskList)->getTasks($user, ListBy::TODAY);

    expect($items)->toHaveKey('today');

    $todayItems = $items['today'];

    expect($todayItems)->toHaveCount(1);
});


test('tomorrow task can be listed', function () {
    $user = User::find(1);

    $items = (new TaskList)->getTasks($user, ListBy::TOMORROW);

    expect($items)->toHaveKey('tomorrow');

    $todayItems = $items['tomorrow'];

    expect($todayItems)->toHaveCount(1);
});


test('today completed task can be listed', function () {
    $user = User::find(1);

    $task = $user->tasks()->first();
    (new TaskAction)->complete($task);

    $items = (new TaskList)->getTasks($user, ListBy::TODAY, Status::COMPLETED);

    expect($items)->toHaveKey('today');

    $todayItems = $items['today'];

    expect($todayItems)->toHaveCount(1);
});
