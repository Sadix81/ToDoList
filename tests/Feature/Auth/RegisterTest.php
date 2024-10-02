<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_auth_user_register(): void
    {
        $data = [
            'fullname' => 'SadraZargari',
            'username' => 'sadra',
            'email' => 'sadra@gmail.com',
            'mobile' => '09111770000',
            'password' => 'S@dra2091',
        ];

        $response = $this->postJson('api/v1/user/register', $data);

        $response->assertStatus(201);
    }
}
