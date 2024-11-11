<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Code\RegisterVerificationCodeRequest;
use App\Models\User;
use App\Repositories\Auth\AuthRepository;

class AuthController extends Controller
{
    private $authRepo;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepo = $authRepository;
    }

    public function register(RegisterRequest $request)
    {
        $error = $this->authRepo->register($request);
        if ($error === null) {
            return response()->json(['message' => 'Registration successful, please check your email for verification code.'] , 201);
        }

        return response()->json(['message' => __('messages.user.auth.register.failed')], 404);

    }

    public function check_verify_code(RegisterVerificationCodeRequest $request){
        $sessionCode = session('verificationCode');
        $userId = session('user');
        // dd($sessionCode, $userId);
        // dd((int)$request->code , $sessionCode);
        if($sessionCode !== (int)$request->code){
            return 'کد نادرست است';
        }

        if(! $userId){
            return 'یوزر پیدا نشده';
        }

        $user = User::find($userId);
        if ($user) {
            $user->email_verified_at = now();
            $user->save();

            session()->forget(['verificationCode', 'user']);
                
                return response()->json(['message' => __('email.verified.successfully.')], 200);
            }
        
        return response()->json(['message' => __('email.verified.failed.')], 404);
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
