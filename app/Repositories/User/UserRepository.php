<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function user_list($request){
        try {
            $username = $request->username;
            $users = User::when($username, function($query) use ($username) {
                return $query->where('username', 'like', '%' . $username . '%');
            })->get();
            return $users;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
