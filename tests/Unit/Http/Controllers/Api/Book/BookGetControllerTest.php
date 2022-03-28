<?php

namespace Unit\Http\Controllers\Api\Book;


use App\Models\Book;
use App\Models\User;
use Tests\TestCase;

class BookGetControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected string $token;

    /**
     * @var Book
     */
    protected $book;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = User::factory()->create()->createToken('client')->accessToken;
        $this->book = Book::factory()->create();
    }

    public function testBookGetValid(): void
    {
        $response = $this->getJson(route("book.get", [
            'id' => $this->book->id,
        ]), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $content['data'][0]);
        $this->assertArrayHasKey('title', $content['data'][0]);
        $this->assertCount(3, $content);
        $this->assertCount(1, $content['data']);
    }

    public function testBookGetWithIdInvalid(): void
    {
        $response = $this->getJson(route("book.get", [
            'id' => 100500,
        ]), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(404);
    }
}
