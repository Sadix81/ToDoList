<?php

namespace App\Models\Note;

use App\Models\Task\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'user_id',
        'task_id',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
