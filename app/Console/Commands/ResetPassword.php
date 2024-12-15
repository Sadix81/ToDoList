<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-password {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset super admin password';

    /**
     * Execute the console command.
     */
    public function handle()
    {    
        $newPassword = $this->argument('password');

        $user = User::find(1);

        if (!$user) {
            $this->error('User not found.');
            return;
        }

        $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->password = $hashPassword;
        $user->save();
        $this->info("Password reset successfully for $user->username to $newPassword.");
        //php artisan app:reset-password sadra1234
    }
}
