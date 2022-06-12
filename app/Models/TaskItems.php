<?php

namespace App\Models;

use App\Enums\Tasks\Status;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'start' => 'datetime:Y-m-d',
        'completed_at' => 'datetime:Y-m-d H:i:s',
        'status'=> Status::class
    ];

    /**
     * Get the task record associated with the task item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get given task item formatted
     */
    public function formatted()
    {
        $this->loadMissing('task');

        return [
            'id' => $this->task->id,
            'user_id' => $this->task->user_id,
            'title' => $this->task->title,
            'start' => $this->start,
            'status' => $this->task->status->name,
            'times' => $this->task->times,
            'timespent' => $this->task->timespent,
        ];
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'user_id' => $this->task->user_id,
            'title' => $this->task->title,
            'start' => $this->start->format('Y-m-d'),
            'status' => $this->status->name,
            'task_status' => $this->task->status->name,
            'times' => $this->task->times,
            'timespent' => $this->task->timespent,
        ];
        return array_merge($this->attributesToArray(), $this->relationsToArray());
    }
}
