<?php

namespace Tests\Unit\Http\Controllers\Api\Queue;

use App\Models\Book;
use App\Models\User;
use Tests\TestCase;

class QueueBookAddControllerTest extends TestCase
{
    /**
     * @var string
     */
    protected string $token;

    protected Book $book;

    protected function setUp():void
    {
        parent::setUp();
        $this->token = User::factory()->create()->createToken('list')->accessToken;
        $this->book = Book::factory()->create();
    }

    public function testBookAddValid(): void
    {
        $data = [
            'id' => $this->book->id,
        ];

        $response = $this->postJson(route('queue.add'), $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);

        if (!empty($content)) {
            $this->assertEquals($content[0]['book_id'], $this->book->id);
        }
    }

    public function testBookAddInvalidId(): void
    {
        $data = [
            'id' => 100500,
        ];

        $response = $this->postJson(route('queue.add'), $data, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(500);
    }
}
