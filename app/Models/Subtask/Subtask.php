<?php

namespace App\Models\Subtask;

use App\Models\Task\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'task_id',
        'title',
        'description',
        'status',
        'image',
    ];

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function task(){
        return $this->belongsTo(Task::class);
    }

}
