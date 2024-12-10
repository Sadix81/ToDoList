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
            'email_verified_at' => Carbon::now(),
        ]);

        // User::create([
        //     'fullname' => 'sadra',
        //     'username' => 'sadra',
        //     'email' => 'zsadra3@gmail.com',
        //     'mobile' => '09033440773',
        //     'password' => password_hash('@Dmin123', PASSWORD_DEFAULT),
        //     'email_verified_at' => Carbon::now()
        // ]);

        User::create([
            'fullname' => 'kasra',
            'username' => 'kasra',
            'email' => 'zzzzsadra30@gmail.com',
            'mobile' => '09117322373',
            'password' => password_hash('@Dmin123', PASSWORD_DEFAULT),
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'fullname' => 'arvin',
            'username' => 'arvin',
            'email' => 'arvin@gmail.com',
            'mobile' => '09117322375',
            'password' => password_hash('@Dmin123', PASSWORD_DEFAULT),
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'fullname' => 'ashkan',
            'username' => 'ashkan',
            'email' => 'ashkan@gmail.com',
            'mobile' => '09117322355',
            'password' => password_hash('@Dmin123', PASSWORD_DEFAULT),
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'fullname' => 'arash',
            'username' => 'arash',
            'email' => 'arash@gmail.com',
            'mobile' => '09117324473',
            'password' => password_hash('@Dmin123', PASSWORD_DEFAULT),
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
