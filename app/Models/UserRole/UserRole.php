<?php

namespace App\Models\UserRole;

use App\Models\Group\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'user_role_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function group_roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
