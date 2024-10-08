<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
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
            return response()->json(['message' => __('messages.user.auth.register.success')], 201);
        }

        return response()->json(['message' => __('messages.user.auth.register.failed')], 404);

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
