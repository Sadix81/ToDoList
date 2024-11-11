<?php

namespace App\Repositories\Auth;

use App\Mail\MyEmail;
use App\Models\User;
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
            
            $verificationCode  = rand(11111 , 99999);
            session(['verificationCode' => $verificationCode]);
            session(['user' => $user->id]);

            // Log::info(session()->all());

            Mail::to($user->email)->send(new MyEmail($user->username , $verificationCode));

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
