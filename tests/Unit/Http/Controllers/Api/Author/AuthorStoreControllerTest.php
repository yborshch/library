<?php

namespace Unit\Http\Controllers\Api\Author;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class AuthorStoreControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected string $token;

    /**
     * @var Author
     */
    protected $author;

    protected function setUp():void
    {
        parent::setUp();
        $this->token = User::factory()->create()->createToken('client')->accessToken;
        $this->author = Author::factory()->makeOne();
    }

    public function testAuthorStoreValid(): void
    {
        $response = $this->postJson(route('author.store'), [
            'firstname' => $this->author->firstname,
            'lastname' => $this->author->lastname
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(201);

        $content = json_decode($response->getContent(), true);
        $this->assertEquals($content['data']['firstname'], $this->author->firstname);
        $this->assertEquals($content['data']['lastname'], $this->author->lastname);
    }

    public function testAuthorStoreWithoutFirstname(): void
    {
        $response = $this->postJson(route('author.store'), [
            'lastname' => $this->author->lastname
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);
        $response->assertStatus(422);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $content);
    }
}
