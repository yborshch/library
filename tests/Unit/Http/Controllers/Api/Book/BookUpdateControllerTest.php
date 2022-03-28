<?php

namespace Unit\Http\Controllers\Api\Book;


use App\Models\Book;
use App\Models\User;
use Tests\TestCase;

class BookUpdateControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected string $token;

    /**
     * @var Book
     */
    protected $book;

    protected function setUp():void
    {
        parent::setUp();
        $this->token = User::factory()->create()->createToken('client')->accessToken;
        $this->book = Book::factory()->create();
    }

    public function testBookUpdateWithValidData(): void
    {
        $response = $this->postJson(route("book.update"), [
            'id' => $this->book->id,
            'category_id' => 1,
            'title' => $this->book->title . 'Test',
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $content);

        $book = Book::find($this->book->id);
        $this->assertNotNull($book);
        $this->assertEquals($this->book->title . 'Test', $book->title);
    }

    public function testBookUpdateWithInvalidId()
    {
        $response = $this->postJson(route("book.update"), [
            'id' => 100500,
            'category_id' => 1,
            'title' => $this->book->title . 'Test'
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(404);

        $message = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $message);
    }
}
