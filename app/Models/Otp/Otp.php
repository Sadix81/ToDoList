<?php

namespace App\Models\Otp;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'otp',
        'expire_time',
        'user_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

}
