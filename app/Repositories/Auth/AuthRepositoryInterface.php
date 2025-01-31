<?php

namespace App\Repositories\Auth;

interface AuthRepositoryInterface
{
    public function register($request);

    public function login($request);

    public function ResendCode($request);

    public function logout();
}
