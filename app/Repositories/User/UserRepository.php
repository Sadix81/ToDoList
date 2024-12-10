<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function user_list($request)
    {
        try {

            $users = User::where('username', 'like', '%'.$request->username.'%')->get();

            return $users;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
