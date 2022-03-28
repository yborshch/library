<?php

namespace Unit\Http\Controllers\Api\Auth;


use App\Models\User;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    public function testLoginWithValidData()
    {
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => "password",
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('token', $content);
    }

    public function testLoginWithoutEmail()
    {
        $response = $this->post(route('auth.login'), [
            'password' => "password",
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);
        $content = json_decode($response->getContent(), true);

        $this->arrayHasKey('errors', $content);
        $this->arrayHasKey('hash', $content);
        $this->assertCount(1, $content['errors']);
    }

    public function testLoginWithoutPassword()
    {
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);
        $content = json_decode($response->getContent(), true);

        $this->arrayHasKey('errors', $content);
        $this->arrayHasKey('hash', $content);
        $this->assertCount(1, $content['errors']);
    }
}
