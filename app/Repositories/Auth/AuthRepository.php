<?php

namespace App\Repositories\Auth;

use App\Mail\Register\EmailValidation;
use App\Models\Otp\Otp;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
class AuthRepository implements AuthRepositoryInterface
{
    public function register($request)
    {
        try {
            $user = User::create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => password_hash($request->password, PASSWORD_DEFAULT),
            ]);
            
            $otp  = rand(11111 , 99999);
            $user = User::where('email' , $user->email)->first();
            $user->otps()->create([
                'user_id' => $user->id,
                'otp' => $otp,
                'expire_time' => Carbon::now()->addMinutes(120)
            ]);

            $otps = $user->otps()->select('otp', 'user_id')->get();

            Log::info('Email validation Code for ' . $user->id . ': ' . $otps->toJson());
            Mail::to($user->email)->send(new EmailValidation($user->username , $otp));

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function login($request)
    {
        $user = User::where('username', $request->username)
            ->orWhere('email', $request->email)
            ->first();

        if (! $user) {
            return '.کاربر یافت نشد';
        }

        if ($user && password_verify($request->password, $user->password)) {
            $token = $user->createToken('__Token__')->accessToken;

            return $token;
        }

        return null;
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            $user->token()->revoke();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
