<?php

namespace App\Models;

use App\Enums\Tasks\Status;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Task extends Model
{
    use HasFactory, Prunable;


    protected $fillable = [
        'start',
        'finish',
        'title',
        'times',
        'timespent',
        'status',
        'completed_at'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start' => 'datetime:Y-m-d',
        'finish' => 'datetime:Y-m-d',
        'completed_at' => 'datetime:Y-m-d H:i:s',
        'status'=> Status::class
    ];


    protected $with = ['items'];


    /**
     * Get items for the task.
     */
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaskItems::class);
    }

    /**
    * Get the prunable model query.
    *
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonth());
    }
}
