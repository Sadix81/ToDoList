<?php

namespace App\Repositories\ForgotPassword;

interface ForgotPasswordRepositoryInterface
{
    public function password($request);

    public function ChangePassword($user, $request);
}
