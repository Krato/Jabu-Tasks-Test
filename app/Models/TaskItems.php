<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'status',
    ];

    protected $casts = [
        'start' => 'datetime:Y-m-d',
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
            'status' => $this->task->status,
            'times' => $this->task->times,
            'timespent' => $this->task->timespent,
        ];
    }
}
