<?php

namespace App\Repositories\ForgotPassword;

use App\Mail\Password\ForgotPassword as PasswordForgotPassword;
use App\Models\Otp\Otp;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordRepository implements ForgotpasswordRepositoryInterface
{
    public function password($request)
    {
        $user_email = $request->email;

        $user = User::where('email', $user_email)->first();

        if (! $user) {
            return response()->json(['message' => 'کاربر مورد نظر وجود ندارد']);
        }

        $otp = rand(11111, 99999);
        $user = User::where('email', $user->email)->first();
        $user->otps()->create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expire_time' => Carbon::now()->addSecond(20),
        ]);

        $otps = $user->otps()->select('otp', 'user_id')->latest()->first(); // test for moer than 1 user

        Log::info("The Forgot Password Code for $user->username ".$user->id.': '.$otps);

        Mail::to($user->email)->send(new PasswordForgotPassword($user->username, $otp));

    }

    public function ChangePassword($user, $request)
    {

        $username = $request->username;
        $user = User::where('username', $username)->first();
        $user_otp = Otp::where('user_id', $user->id)->first();

        if (! $user_otp) {
            return response()->json(['message' => 'کاربر مورد نظر وجود ندارد'], 404);
        }

        try {
            $user->update([
                'password' => password_hash($request->password, PASSWORD_DEFAULT),
            ]);
            $user_otp->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
