<?php

namespace Unit\Http\Controllers\Api\Author;


use App\Models\Author;
use App\Models\User;
use Tests\TestCase;

class AuthorGetControllerTest extends TestCase
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
        $this->token = User::factory()->create()->createToken('list')->accessToken;
        $this->author = Author::factory()->create();
    }

    public function testAuthorGetValid(): void
    {
        $response = $this->getJson(route("author.get", [
            'id' => $this->author->id,
        ]), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $content['data'][0]);
        $this->assertArrayHasKey('firstname', $content['data'][0]);
        $this->assertArrayHasKey('lastname', $content['data'][0]);
        $this->assertCount(3, $content);
        $this->assertCount(5, $content['data'][0]);
    }

    public function testAuthorGetWithIdInvalid(): void
    {
        $response = $this->getJson(route("author.get", [
            'id' => 100500,
        ]), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(404);

        $content = json_decode($response->getContent(), true);

        $this->arrayHasKey('errors', $content);
        $this->arrayHasKey('hash', $content);
        $this->assertCount(1, $content['errors']);
    }
}
