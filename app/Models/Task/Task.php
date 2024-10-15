<?php

namespace App\Models\Task;

use App\Models\Category\Category;
use App\Models\Group\Group;
use App\Models\Note\Note;
use App\Models\Subtask\Subtask;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'started_at',
        'finished_at',
        'priority',
        // 'reminder',
        // 'label',
        'status',
        'owner_id',
        'user_id',
        'group_id',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function notes(){
        return $this->hasMany(Note::class);
    }

    public function subtasks(){
        return $this->hasMany(Subtask::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
