<?php

namespace App\Models\Group;

use App\Models\Task\Task;
use App\Models\User;
use App\Models\UserRole\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function userRoles()//pivot table between uers and groups
    {
        return $this->hasMany(UserRole::class);
    }
}
