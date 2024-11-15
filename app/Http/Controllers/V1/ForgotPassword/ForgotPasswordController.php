<?php

namespace App\Http\Controllers\V1\ForgotPassword;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPassword\ChangePasswordRequest;
use App\Http\Requests\ForgotPassword\ForgotPasswordRequest;
use App\Http\Requests\ForgotPassword\Otp\ForgotPasswordCodeRequest;
use App\Models\User;
use App\Repositories\ForgotPassword\ForgotPasswordRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ForgotPasswordController extends Controller
{

    private $passRepo;

    public function __construct(ForgotPasswordRepository $forgotPassword)
    {
        $this->passRepo = $forgotPassword;
    }

    public function forgotpassword(ForgotPasswordRequest $request){

        $error = $this->passRepo->password($request);

        if($error === null){
            return response()->json(['message' => __('messages.otp.send.successfully')] , 200);
        }
        return response()->json(['message' => __('messages.otp.send.failed')] , 404);
    }

    public function check_otp_code(ForgotPasswordCodeRequest $request){
        
        $otpcode = session('otp');

        if($otpcode !== (int)$request->code){
            return 'کد نادرست است';
        }

        if($otpcode === (int)$request->code){

            session()->forget('otp');

            return response()->json(['message' => __('email.verified.successfully.')], 200);
        }

        return response()->json(['message' => __('email.verified.failed.')], 404);
    }

    public function ChangePassword(ChangePasswordRequest $request){

        $user_id = session('user');

        $user = User::find($user_id);

        if (password_verify($request->password, $user->password) || password_verify($request->confirmpassword, $user->password)) {
            return 'نمیتوان رمز فعلی را انتخاب کرد';
        }

        if($request->password !== $request->confirmpassword){
            return 'یکسان بودن دو مقدار الزامیست';
        }

        $error = $this->passRepo->ChangePassword($request);
        if($error === null){
            session()->forget('user');
            return response()->json(['message' => __('password.changed.successfully.')], 200);
        }

        return response()->json(['message' => __('password.changed.failed.')], 404);
    }
}
