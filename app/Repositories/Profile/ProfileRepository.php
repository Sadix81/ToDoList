<?php

namespace App\Repositories\Profile;

use Illuminate\Support\Facades\Auth;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function update($user, $request)
    {

        $user = Auth::user();

        if (! $user) {
            return 'عدم دسترسی کاربر';
        }

        $profile_image = $user->avatar;

        if (request()->hasFile('avatar')) {
            $profile_image = time().'-'.$request->username.'-'.$request->avatar->getClientOriginalName();
            $request->avatar->move(public_path('users/profile/avatars'), $profile_image);
        } elseif ($request->avatar === null) {
            $profile_image = null;
        }

        try {
            $user->update([
                'fullname' => $request->fullname ?? $user->fullname,
                'username' => $request->username ?? $user->username,
                'email' => $request->email ?? $user->email,
                'mobile' => $request->mobile ?? $user->mobile,
                'avatar' => $profile_image,
                // 'password' => $request->password ? $request->password : $user->password,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function changePassword($request){

        $user = Auth::user();
        
        try {
            $user->update([
                'password' => $request->password
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
