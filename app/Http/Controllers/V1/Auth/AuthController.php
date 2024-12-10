<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Code\RegisterVerificationCodeRequest;
use App\Jobs\Auth\AuthJob;
use App\Models\Otp\Otp;
use App\Models\User;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    private $authRepo;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepo = $authRepository;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authRepo->register($request);

        if ($user) {
            AuthJob::dispatch($user);

            return response()->json(['message' => 'Registration successful, please check your email for verification code.'], 201);
        }

        return response()->json(['message' => __('messages.user.auth.register.failed')], 404);

    }

    public function verify_otp_code(Otp $otp, RegisterVerificationCodeRequest $request)
    {

        $code = $request->code;

        $otp = Otp::where('otp', $code)
            ->where('expire_time', '>', Carbon::now())
            ->first();

        $user = User::find($otp->user_id);

        if ($request->code != $otp->otp) {
            return response()->json(['message' => 'کد نادرست است'], 404);
        }

        if ($request->code == $otp->otp) {
            $user->update(['email_verified_at' => Carbon::now()]);
            $otp->delete();

            return response()->json(['message' => __('code.verified.successfully.')], 200);
        }

        return response()->json(['message' => __('code.verified.failed.')], 404);
    }

    public function login(LoginRequest $request)
    {
        $accessToken = $this->authRepo->login($request);
        if ($accessToken) {
            return response()->json(['message' => __('messages.user.auth.login.success'), '__token__' => $accessToken], 200);
        }

        return response()->json(['message' => __('messages.user.auth.login.failed')], 403);
    }

    public function logout()
    {
        $accessToken = $this->authRepo->logout();

        return response()->json(['message' => __('messages.user.auth.logout.success'), '__token__' => $accessToken], 200);
    }
}
