<?php

namespace App\Repositories\ForgotPassword;

use App\Mail\Password\ForgotPassword as PasswordForgotPassword;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordRepository implements ForgotpasswordRepositoryInterface{

    public function password($request)
    {
        $user_email = $request->email;
        
        $user = User::where('email' , $user_email)->first();

        if(! $user){
            return 'کاربر مورد نظر وجود ندارد';
        }

        $otp = rand(1111 , 9999);
        
        session(['user' => $user->id, 'otp' => $otp]);

        // Log sessions data
        Log::info('Session Data:', [
            'user' => $user->id,
            'otp' => $otp,
        ]);

        Mail::to($user->email)->send(new PasswordForgotPassword($user->username , $otp));
        
    }

    public function ChangePassword($request){
        $userId = session('user');
        $user = User::where('id' , $userId)->first();
        // dd($user->password);

        try {
            $user->update([
                'password' => password_hash($request->password, PASSWORD_DEFAULT),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}