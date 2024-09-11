<?php

namespace App\Models\Task;

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
        'reminder',
        'label',
        // 'storage_id',
        'status',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
