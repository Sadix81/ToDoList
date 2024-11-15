<?php

namespace App\Repositories\ForgotPassword;

interface ForgotpasswordRepositoryInterface{
    
    public function password($request);

    public function ChangePassword($request);
}