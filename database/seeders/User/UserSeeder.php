<?php

namespace Database\Seeders\User;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'fullname' => 'admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'mobile' => '12345678901',
            'password' => password_hash('@Dmin123', PASSWORD_DEFAULT),
            'email_verified_at' => Carbon::now()
        ]);

        User::create([
            'fullname' => 'sadra',
            'username' => 'sadra',
            'email' => 'zsadra3@gmail.com',
            'mobile' => '09033440773',
            'password' => password_hash('@Dmin123', PASSWORD_DEFAULT),
            'email_verified_at' => Carbon::now()
        ]);

        User::create([
            'fullname' => 'kasra',
            'username' => 'kasra',
            'email' => 'zzzzsadra30@gmail.com',
            'mobile' => '09117322373',
            'password' => password_hash('@Dmin123', PASSWORD_DEFAULT),
            'email_verified_at' => Carbon::now()
        ]);
    }
}
