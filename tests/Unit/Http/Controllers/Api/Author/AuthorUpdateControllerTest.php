<?php

namespace Unit\Http\Controllers\Api\Author;


use App\Models\Author;
use App\Models\User;
use Tests\TestCase;

class AuthorUpdateControllerTest extends TestCase
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

    public function testAuthorUpdateWithValidData(): void
    {
        $response = $this->postJson(route("author.update"), [
            'id' => $this->author->id,
            'firstname' => $this->author->firstname . 'Test',
            'lastname' => $this->author->lastname . 'Test'
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $content);

        $author = Author::find($this->author->id);
        $this->assertNotNull($author);
        $this->assertEquals($this->author->firstname . 'Test', $author->firstname);
        $this->assertEquals($this->author->lastname . 'Test', $author->lastname);
    }

    public function testAuthorUpdateWithInvalidId()
    {
        $response = $this->postJson(route("author.update"), [
            'id' => 100500,
            'firstname' => $this->author->firstname . 'Test',
            'lastname' => $this->author->lastname . 'Test'
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(404);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $content);
    }
}
