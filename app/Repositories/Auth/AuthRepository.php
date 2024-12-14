<?php

namespace App\Repositories\Auth;

use App\Mail\Register\EmailValidation;
use App\Models\Otp\Otp;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Exists;

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

            return $user;

        } catch (\Throwable $th) {
            Log::error('Registration error: '.$th->getMessage());

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

    public function ResendCode($request){
        try {
            $user = User::where('email' , $request->email)->first();
            $last_user_otp = Otp::where('user_id' , $user->id)->first();
    
            if(!$user){
                return response()->json(['meesage => User not exist']);
            }

            if($last_user_otp){
                $last_user_otp->delete();
            }
    
            $otp = rand(11111, 99999);
            $user->otps()->create([
                'user_id' =>  $user->id,
                'otp' => $otp,
                'expire_time' => Carbon::now()->addMinutes(120),
            ]);
    
            Log::info('Email validation Code for '. $user->id.': '.$otp);
            Mail::to( $user->email)->send(new EmailValidation( $user->username, $otp));
        } catch (\Throwable $th) {
            throw $th;
        }
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
