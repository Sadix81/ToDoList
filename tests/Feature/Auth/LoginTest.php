<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_auth_user_login()
    {
        $data = [
            'username' => 'sadra',
            // 'email' => 'sadra@gmail.com',
            'password' => 'S@dra2091',
        ];

        $response = $this->postJson('api/v1/user/login', $data);

        //get json
        $response->assertJsonStructure(['__token__']);
        $response->assertStatus(200);
    }
}
