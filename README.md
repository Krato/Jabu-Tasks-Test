<p align="center"><a href="https://gojbu.com" target="_blank"><img src="https://www.gojabu.com/wp-content/uploads/2021/08/FONDO-AZUL-1.png" width="400px" />
</a></p>



# Jabu FullStack Test

## Installation

**Local installation**

```bash
composer install
php artisan app:start
```

**Docker installation**

```bash
sail build
sail up
sail artisan app:start
```

Command `app:start` will create the db, seed the demo data and new user. User login details will be prompt.

## Web interface

You will be able to create periodic tasks with the ui.

## Technologies

* Laravel 9.17.0
* Livewire (for easy web ui)

## How it works

Once a task is created, the app will create all associated recurring task dates (called `items`).

In this way you can manage from 10 tasks to millions of tasks because the system will not suffer obtaining the data because they are already in DB, there are no loops or complitcated queries to perform.

The task will finish at the end date or at the max number of iterations saved when the task was created.

An item (sub task) can be completed as well. Once all subtask are completed the task will be mark as completed. You can mark as complete a task instead a item. Then all items will be marked as completed.

All tasks belongs to a User;

### Services

List of all task services

**TaskFrequency**: Create Task Frequency class

> Arguments:
> * Frequency: Frequency ENUM (DAILY, WEEKLY, MONTHLY, YEARLY)
> * WeekDays: array (['MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU'])
> * Months: array ([1,2,3,4,5,6,7,8,9,10,11,12])
> * MonthDays: array ([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31])


**TaskCreate**: Create a new task for given arguments. `Return Task model`.

> Arguments:
> * User: User model instance
> * StartDate: DateTime instance
> * EndDate: DateTime instance
> * Title: string (task title)
> * TaskFrequency: TaskFrequency instance
> * Limit: int (max number of iterations)
> * @return Task model instance

**TaskList**: Get all tasks for a given user. `Return array of formatted Tasks`.

If `ListBy` is not set then the list will get all ListBy cases

If `Status` is not set then the list will get all Status cases

> Arguments:
> * User: User model instance
> * ListBy: null, ListBy ENUM (TODAY,TOMORROW,NEXT_WEEK,NEXT_MONTH)
> * Status: null, Status ENUM (PENDING,COMPLETED)

**TaskAction**: Perform an action on a task or task item

> Methods:
> * AddCount: Add count to a task item
> * Complete: Mark a task as completed
> * CompleteItem: Mark a task item as completed
> * Pending: Mark a task as pending
> * PendingItem: Mark a task item as pending

### Creating a task - Some Examples

```php
//Get a user
$user = User::first();

// Start date
$start = new DateTime('2022-07-01');

// Finish date
$finish = new DateTime('2022-07-15');

// Create a Task frequency daily
$frequency =  new TaskFrequency(Frequency::DAILY);

// Create the task
$task = (new TaskCreate)->create($user, $start, $finish, 'June 1 to 15', $frequency);

// Create a week task
$finish = new DateTime('2022-10-01');
$frequency =  new TaskFrequency(Frequency::WEEKLY, ['MO', 'TU']);
(new TaskCreate)->create($user, $start, $finish, 'All Monday and tuesday', $frequency);

// Create a monthly task
$finish = new DateTime('2024-07-15');
$frequency =  new TaskFrequency(Frequency::MONTHLY, [],[3, 5], [5, 7]);
(new TaskCreate)->create($user, $start, $finish, 'All 5 and 7 days of march and may', $frequency);

// All 5 of each month
$frequency =  new TaskFrequency(Frequency::MONTHLY, [], [], [5]);
(new TaskCreate)->create($user, $start, $finish, 'All 5 of each month', $frequency);

// All days between the given dates max repeat of 3
$frequency =  new TaskFrequency(Frequency::DAILY);
(new TaskCreate)->create($user, $start, $finish, 'Max 3 task items', $frequency, 3);
```

## Tests

There are some tests included. To run them execute the following command:

```bash
./vendor/bin/pest
```
