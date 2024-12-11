<?php

namespace App\Models;

use App\Models\Group\Group;
use App\Models\Otp\Otp;
use App\Models\Subtask\Subtask;
use App\Models\Task\Task;
use App\Models\UserRole\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles , Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'username',
        'email',
        'mobile',
        'avatar',
        'password',
        'confirm_code',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function user_role()//pivot table between uers and groups
    {
        return $this->hasMany(UserRole::class);
    }
}
