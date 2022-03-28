<?php

namespace Unit\Http\Controllers\Api\Auth;

use App\Models\User;
use Tests\TestCase;

class RegistrationControllerTest extends TestCase
{
    public function testRegisterWithValidData(): void
    {
        $user = User::factory()->make();

        $response = $this->post(route('auth.register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(201);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals($user->name, $content['result']['name']);
        $this->assertEquals($user->email, $content['result']['email']);
    }

    public function testRegisterWithInvalidPasswordConfirmation()
    {
        $user = User::factory()->make();

        $response = $this->post(route('auth.register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password . '!'
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);
        $content = json_decode($response->getContent(), true);

        $this->arrayHasKey('errors', $content);
        $this->arrayHasKey('hash', $content);
        $this->assertCount(1, $content['errors']);
    }

    public function testRegisterWithEmptyPasswordConfirmation()
    {
        $user = User::factory()->make();

        $response = $this->post(route('auth.register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => ''
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);
        $content = json_decode($response->getContent(), true);

        $this->arrayHasKey('errors', $content);
        $this->arrayHasKey('hash', $content);
        $this->assertCount(1, $content['errors']);
    }

    public function testRegisterWithEmptyPassword()
    {
        $user = User::factory()->make();

        $response = $this->post(route('auth.register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => $user->password
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);
        $content = json_decode($response->getContent(), true);

        $this->arrayHasKey('errors', $content);
        $this->arrayHasKey('hash', $content);
        $this->assertCount(1, $content['errors']);
    }

    public function testRegisterWithoutEmail()
    {
        $user = User::factory()->make();

        $response = $this->post(route('auth.register'), [
            'name' => $user->name,
            'password' => $user->password,
            'password_confirmation' => $user->password
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);
        $content = json_decode($response->getContent(), true);

        $this->arrayHasKey('errors', $content);
        $this->arrayHasKey('hash', $content);
        $this->assertCount(1, $content['errors']);
    }

    public function testRegisterWithoutName()
    {
        $user = User::factory()->make();

        $response = $this->post(route('auth.register'), [
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password
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
