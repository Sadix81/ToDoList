<?php

namespace App\Jobs\Auth;

use App\Mail\Register\EmailValidation;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthJob implements ShouldQueue
{
    use Queueable;

    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(AuthRepository $authRepository)
    {

        $otp = rand(11111, 99999);
        $this->user->otps()->create([
            'user_id' => $this->user->id,
            'otp' => $otp,
            'expire_time' => Carbon::now()->addMinutes(120),
        ]);

        Log::info('Email validation Code for '.$this->user->id.': '.$otp);
        Mail::to($this->user->email)->send(new EmailValidation($this->user->username, $otp));
    }
}
