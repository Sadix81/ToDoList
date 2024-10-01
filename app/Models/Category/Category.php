<?php

namespace App\Models\Category;

use App\Models\Task\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
}
