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
}
